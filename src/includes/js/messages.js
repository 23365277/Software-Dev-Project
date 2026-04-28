/**
 * messages.js — Unified Roamance Messaging Module
 */

const RoamanceMessaging = (() => {

    const PHONE_RE      = /(\+?\d[\s\-.()\[\]]{0,3}){7,}/;
    const POLL_INTERVAL = 3000;

    const EP = {
        contacts : '/includes/php/get_contacts.php',
        messages : '/includes/php/get_message.php',
        send     : '/includes/php/send_message.php',
        report   : '/includes/php/report_user.php',
        block    : '/includes/php/block_user.php',
    };

    function qs(selector, root = document) {
        if (!selector) return null;
        return typeof selector === 'string' ? root.querySelector(selector) : selector;
    }

    function showError(el, msg, duration = 3000) {
        if (!el) return;
        el.textContent = msg;
        el.classList.add('visible');
        setTimeout(() => el.classList.remove('visible'), duration);
    }

    function init(opts = {}) {

        const el = {
            contacts      : qs(opts.contactsEl),
            messages      : qs(opts.messagesEl),
            input         : qs(opts.inputEl),
            sendBtn       : qs(opts.sendBtnEl),
            error         : qs(opts.errorEl),
            actions       : qs(opts.actionsEl),
            actionsToggle : qs(opts.actionsToggleEl),
            actionsMenu   : qs(opts.actionsMenuEl),
            reportBtn     : qs(opts.reportBtnEl),
            blockBtn      : qs(opts.blockBtnEl),
            reportOverlay : qs(opts.reportOverlayEl),
            reportReason  : qs(opts.reportReasonEl),
            reportError   : qs(opts.reportErrorEl),
            reportSubmit  : qs(opts.reportSubmitEl),
            reportCancel  : qs(opts.reportCancelEl),
        };

        const myUserId = opts.myUserId;
        const mode     = opts.mode || 'chatbox';

        let currentContact = null;
        let currentMatchId = null;
        let pollTimer      = null;

        // In inbox mode, set currentContact from otherUserId immediately
        if (mode === 'inbox' && opts.otherUserId) {
            currentContact = opts.otherUserId;
        }

        /* ── loadContacts ── */
        async function loadContacts() {
            if (mode === 'inbox') return;
            if (!el.contacts) return;

            try {
                const res      = await fetch(EP.contacts);
                const contacts = await res.json();

                el.contacts.querySelectorAll('.rm-contact').forEach(c => c.remove());

                if (!contacts.length) {
                    const span = document.createElement('span');
                    span.className   = 'rm-contact rm-no-contacts';
                    span.textContent = 'No contacts yet';
                    el.contacts.appendChild(span);
                    return;
                }

                contacts.forEach(contact => {
                    const div           = document.createElement('div');
                    div.className       = 'rm-contact contact';
                    div.dataset.userid  = contact.id;
                    div.dataset.matchid = contact.match_id;

                    const nameSpan = document.createElement('span');
                    nameSpan.textContent = contact.name || contact.email;
                    div.appendChild(nameSpan);

                    if (parseInt(contact.unread_count) > 0) {
                        const dot = document.createElement('span');
                        dot.className = 'unread-dot';
                        div.appendChild(dot);
                    }

                    div.addEventListener('click', () => selectContact(contact.id, contact.match_id, div));
                    el.contacts.appendChild(div);
                });

            } catch (err) {
                console.error('[RoamanceMessaging] loadContacts:', err);
                if (el.contacts) {
                    el.contacts.innerHTML = '<span class="rm-contact">Error loading contacts</span>';
                }
            }
        }

        /* ── selectContact ── */
        function selectContact(contactId, matchId, contactEl) {
            currentContact = contactId;
            currentMatchId = matchId;
            lastMessageTimestamp = null;

            if (el.input)   el.input.disabled   = false;
            if (el.sendBtn) el.sendBtn.disabled  = false;
            if (el.actions) el.actions.style.display = 'block';

            if (el.contacts) {
                el.contacts.querySelectorAll('.rm-contact, .contact').forEach(c => c.classList.remove('active'));
            }
            if (contactEl) contactEl.classList.add('active');

            fetchMessages();
            opts.onContactSelected && opts.onContactSelected(contactId);
        }

        /* ── selectMatch ── */
        function selectMatch(matchId) {
            currentMatchId = matchId;
            lastMessageTimestamp = null;
            fetchMessages();
            opts.onContactSelected && opts.onContactSelected(matchId);
        }

        // Timestamp of the most recent message we've rendered
        let lastMessageTimestamp = null;

        // Tracks the last date divider shown, so pollMessages can add new ones
        let lastRenderedDate = null;

        /* ── buildMessageEl: builds a single message DOM node ── */
        function buildMessageEl(msg) {
            const isMine = String(msg.sender_id) === String(myUserId);
            const sentAt = msg.sent_at ? new Date(msg.sent_at) : null;

            if (mode === 'inbox') {
                const wrap = document.createElement('div');
                wrap.className = `msg${isMine ? ' sent' : ''}`;

                if (!isMine) {
                    const avatar = document.createElement('div');
                    avatar.className   = 'msg-avatar';
                    avatar.textContent = opts.otherInitials || '?';
                    wrap.appendChild(avatar);
                }

                const inner  = document.createElement('div');
                const bubble = document.createElement('div');
                bubble.className   = `bubble ${isMine ? 'sent' : 'received'}`;
                bubble.textContent = msg.message;

                if (msg.image_url) {
                    const img = document.createElement('img');
                    img.src = msg.image_url;
                    img.style.cssText = 'max-width:200px;border-radius:8px;display:block;margin-top:4px;cursor:zoom-in;';
                    img.addEventListener('click', () => {
                        if (typeof openPhotoLightbox === 'function') openPhotoLightbox(img.src);
                    });
                    bubble.appendChild(img);
                }

                inner.appendChild(bubble);

                const timeEl = document.createElement('div');
                timeEl.className   = `msg-time${isMine ? ' text-end' : ''}`;
                timeEl.textContent = sentAt
                    ? sentAt.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })
                    : '';
                inner.appendChild(timeEl);

                wrap.appendChild(inner);

                if (isMine) {
                    const avatar = document.createElement('div');
                    avatar.className        = 'msg-avatar';
                    avatar.style.background = '#534AB7';
                    avatar.style.color      = '#fff';
                    avatar.textContent      = 'Me';
                    wrap.appendChild(avatar);
                }

                return wrap;

            } else {
                const wrap = document.createElement('div');
                wrap.className = `rm-msg msg${isMine ? ' sent' : ''}`;

                const inner = document.createElement('div');

                if (msg.message) {
                    const bubble = document.createElement('div');
                    bubble.className   = `bubble ${isMine ? 'sent' : 'received'}`;
                    bubble.textContent = (isMine ? 'You: ' : '') + msg.message;
                    inner.appendChild(bubble);
                }

                if (msg.image_url) {
                    const img = document.createElement('img');
                    img.src = msg.image_url;
                    img.style.cssText = 'max-width:160px;border-radius:8px;display:block;margin-top:4px;cursor:zoom-in;';
                    img.addEventListener('click', () => {
                        if (typeof openPhotoLightbox === 'function') openPhotoLightbox(img.src);
                    });
                    inner.appendChild(img);
                }

                wrap.appendChild(inner);
                return wrap;
            }
        }

        /* ── appendMessages: renders an array of messages into el.messages ── */
        function appendMessages(messages, scrollIfNew) {
            const today = new Date().toISOString().slice(0, 10);

            messages.forEach(msg => {
                const sentAt  = msg.sent_at ? new Date(msg.sent_at) : null;
                const msgDate = sentAt ? sentAt.toISOString().slice(0, 10) : null;

                if (mode === 'inbox' && msgDate && msgDate !== lastRenderedDate) {
                    lastRenderedDate = msgDate;
                    const divider = document.createElement('div');
                    divider.className   = 'date-divider';
                    divider.textContent = msgDate === today
                        ? 'Today'
                        : sentAt.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                    el.messages.appendChild(divider);
                }

                el.messages.appendChild(buildMessageEl(msg));

                if (msg.sent_at) lastMessageTimestamp = msg.sent_at;
            });

            if (scrollIfNew) {
                const area = el.messages;
                const scrollToBottom = () => { area.scrollTop = area.scrollHeight; };
                scrollToBottom();
                area.querySelectorAll('img').forEach(img => {
                    if (!img.complete) {
                        img.addEventListener('load', scrollToBottom, { once: true });
                        img.addEventListener('error', scrollToBottom, { once: true });
                    } else {
                        requestAnimationFrame(() => requestAnimationFrame(scrollToBottom));
                    }
                });
            }
        }

        /* ── fetchMessages: full load, clears the feed (called on conversation switch) ── */
        function fetchMessages() {
            if (!el.messages) return;

            let url;
            if (currentMatchId) {
                url = `${EP.messages}?match_id=${currentMatchId}`;
            } else if (currentContact) {
                url = `${EP.messages}?other_user=${currentContact}`;
            } else {
                return;
            }

            fetch(url)
                .then(r => r.json())
                .then(messages => {
                    if (!Array.isArray(messages)) {
                        console.error('[RoamanceMessaging] fetchMessages unexpected response:', messages);
                        return;
                    }

                    lastMessageTimestamp = null;
                    lastRenderedDate     = null;
                    el.messages.classList.remove('centered-message');
                    el.messages.innerHTML = '';

                    if (!messages.length) {
                        el.messages.classList.add('centered-message');
                        el.messages.innerHTML = '<span>No messages yet</span>';
                        return;
                    }

                    appendMessages(messages, true);
                })
                .catch(err => console.error('[RoamanceMessaging] fetchMessages:', err));
        }

        /* ── pollMessages: incremental poll, only fetches and appends new messages ── */
        function pollMessages() {
            if (!el.messages || (!currentMatchId && !currentContact)) return;

            let url;
            if (currentMatchId) {
                url = `${EP.messages}?match_id=${currentMatchId}`;
            } else {
                url = `${EP.messages}?other_user=${currentContact}`;
            }
            if (lastMessageTimestamp) url += `&after=${encodeURIComponent(lastMessageTimestamp)}`;

            fetch(url)
                .then(r => r.json())
                .then(messages => {
                    if (!Array.isArray(messages) || !messages.length) return;
                    if (el.messages.classList.contains('centered-message')) {
                        el.messages.classList.remove('centered-message');
                        el.messages.innerHTML = '';
                        lastRenderedDate = null;
                    }
                    const area        = el.messages;
                    const isNearBottom = (area.scrollHeight - area.scrollTop - area.clientHeight) < 80;
                    appendMessages(messages, isNearBottom);
                })
                .catch(err => console.error('[RoamanceMessaging] pollMessages:', err));
        }

        /* ── sendMessage ── */
        function sendMessage() {
            const message = el.input ? el.input.value.trim() : '';
		const file = attachInput && attachInput.files[0];

            if (!message && !file) return;

            if (PHONE_RE.test(message)) {
                showError(el.error, 'Phone numbers are not allowed in messages.');
                return;
            }

		const body = new FormData();

		if(message) body.append('message', message);
		if(file) body.append('attachment', file);
		if(currentMatchId) body.append('match_id', currentMatchId);
		else if (currentContact) body.append('receiver_id', currentContact);

            // Disable input while sending to prevent double-sends
            if (el.input)   el.input.disabled   = true;
            if (el.sendBtn) el.sendBtn.disabled  = true;


		console.log('sending', message, file, currentMatchId, currentContact);
            fetch(EP.send, {
                method  : 'POST',
                body,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (el.input) el.input.value = '';
                    if (attachInput) attachInput.value = '';
                    clearAttachPreview();
                    pollMessages();
                } else {
                    console.error('[RoamanceMessaging] send error:', data.error);
                    showError(el.error, data.error || 'Failed to send message.');
                }
            })
            .catch(err => {
                console.error('[RoamanceMessaging] sendMessage network:', err);
                showError(el.error, 'Network error. Please try again.');
            })
            .finally(() => {
                if (el.input)   el.input.disabled   = false;
                if (el.sendBtn) el.sendBtn.disabled  = false;
                if (el.input)   el.input.focus();
            });
        }

        /* ── Actions dropdown ── */
        if (el.actionsToggle && el.actionsMenu) {
            el.actionsToggle.addEventListener('click', e => {
                e.stopPropagation();
                el.actionsMenu.style.display =
                    el.actionsMenu.style.display === 'block' ? 'none' : 'block';
            });
            document.addEventListener('click', () => {
                if (el.actionsMenu) el.actionsMenu.style.display = 'none';
            });
        }

        /* ── Report ── */
        if (el.reportBtn && el.reportOverlay) {
            el.reportBtn.addEventListener('click', () => {
                if (el.actionsMenu) el.actionsMenu.style.display = 'none';
                if (!currentContact && !currentMatchId) return;
                if (el.reportReason) el.reportReason.value = '';
                if (el.reportError)  el.reportError.textContent = '';
                el.reportOverlay.style.display = 'flex';
            });
        }

        if (el.reportCancel) {
            el.reportCancel.addEventListener('click', () => {
                el.reportOverlay.style.display = 'none';
            });
        }

        if (el.reportOverlay) {
            el.reportOverlay.addEventListener('click', e => {
                if (e.target === el.reportOverlay) el.reportOverlay.style.display = 'none';
            });
        }

        if (el.reportSubmit) {
            el.reportSubmit.addEventListener('click', () => {
                const reason = el.reportReason ? el.reportReason.value.trim() : '';
                if (!reason) {
                    if (el.reportError) el.reportError.textContent = 'Please enter a reason.';
                    return;
                }
                el.reportSubmit.disabled = true;

                fetch(EP.report, {
                    method  : 'POST',
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body    : new URLSearchParams({ reported_id: currentContact, reason }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        el.reportOverlay.style.display = 'none';
                        showError(el.error, 'User reported successfully.');
                    } else {
                        if (el.reportError)
                            el.reportError.textContent = data.error || 'Failed to submit report.';
                    }
                })
                .catch(() => {
                    if (el.reportError) el.reportError.textContent = 'Network error. Please try again.';
                })
                .finally(() => { el.reportSubmit.disabled = false; });
            });
        }

        /* ── Block ── */
        if (el.blockBtn) {
            el.blockBtn.addEventListener('click', () => {
                if (el.actionsMenu) el.actionsMenu.style.display = 'none';

                const blockId = currentContact;
                if (!blockId) {
                    console.warn('[RoamanceMessaging] blockBtn: no currentContact to block');
                    return;
                }

                fetch(EP.block, {
                    method  : 'POST',
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body    : new URLSearchParams({ block_id: blockId }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showError(el.error, 'User blocked successfully.');
                        if (el.actions) el.actions.style.display = 'none';
                        currentContact = null;
                        currentMatchId = null;
                        if (el.input)    el.input.disabled    = true;
                        if (el.sendBtn)  el.sendBtn.disabled  = true;
                        if (el.messages) {
                            el.messages.classList.add('centered-message');
                            el.messages.innerHTML = '<span>Select a conversation to start messaging</span>';
                        }
                        loadContacts();
                        opts.onBlocked && opts.onBlocked(blockId);
                    } else {
                        showError(el.error, data.error || 'Failed to block user.');
                    }
                })
                .catch(() => showError(el.error, 'Network error. Please try again.'));
            });
        }

        /* ── Attach preview ── */
        const attachInput   = qs(opts.attachInputEl);
        const attachPreview = qs(opts.attachPreviewEl);

        function clearAttachPreview() {
            if (!attachPreview) return;
            attachPreview.style.display = 'none';
            attachPreview.innerHTML = '';
        }

        const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (attachInput && attachPreview) {
            attachInput.addEventListener('change', () => {
                const file = attachInput.files[0];
                if (!file) { clearAttachPreview(); return; }

                if (!ALLOWED_TYPES.includes(file.type)) {
                    showError(el.error, 'Unsupported image format. Please use JPEG, PNG, GIF, or WebP.');
                    attachInput.value = '';
                    clearAttachPreview();
                    return;
                }

                attachPreview.innerHTML = '';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.cssText = 'height:52px;width:52px;object-fit:cover;border-radius:6px;flex-shrink:0;';
                attachPreview.appendChild(img);

                const name = document.createElement('span');
                name.textContent = file.name;
                name.style.cssText = 'font-size:12px;color:#555;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0;';
                attachPreview.appendChild(name);

                const clearBtn = document.createElement('button');
                clearBtn.textContent = '×';
                clearBtn.style.cssText = 'background:none;border:none;font-size:20px;line-height:1;cursor:pointer;color:#888;padding:0 2px;flex-shrink:0;';
                clearBtn.addEventListener('click', () => {
                    attachInput.value = '';
                    clearAttachPreview();
                });
                attachPreview.appendChild(clearBtn);

                attachPreview.style.display = 'flex';
            });
        }

        /* ── Input event listeners ── */
        if (el.sendBtn) el.sendBtn.addEventListener('click', sendMessage);
        if (el.input) {
            el.input.addEventListener('keypress', e => {
                if (e.key === 'Enter') sendMessage();
            });
        }

        /* ── Polling ── */
        pollTimer = setInterval(pollMessages, POLL_INTERVAL);

        /* ── Auto-init for inbox mode ── */
        if (mode === 'inbox' && opts.initialMatchId) {
            currentMatchId = opts.initialMatchId;
            fetchMessages();
        }

        /* ── Load contacts for chatbox / home ── */
        if (mode !== 'inbox') {
            loadContacts();
        }

        return {
            loadContacts,
            selectContact,
            selectMatch,
            fetchMessages,
            sendMessage,
            destroy() { clearInterval(pollTimer); },
        };
    }

    return { init };
})();
