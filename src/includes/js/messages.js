/**
 * messaging.js — Unified Roamance Messaging Module
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
/**
 * messaging.js — Unified Roamance Messaging Module
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

        // ── FIX 1: In inbox mode, set currentContact from otherUserId immediately ──
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
        let currentContact = null;
        let currentMatchId = null;
        let pollTimer      = null;

        // ── FIX 1: In inbox mode, set currentContact from otherUserId immediately ──
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
                el.contacts.querySelectorAll('.rm-contact').forEach(c => c.remove());

                if (!contacts.length) {
                    const span = document.createElement('span');
                    span.className   = 'rm-contact rm-no-contacts';
                    span.textContent = 'No contacts yet';
                    el.contacts.appendChild(span);
                    return;
                }
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

            if (el.input)   el.input.disabled   = false;
            if (el.sendBtn) el.sendBtn.disabled  = false;
            if (el.actions) el.actions.style.display = 'block';
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

            if (el.input)   el.input.disabled   = false;
            if (el.sendBtn) el.sendBtn.disabled  = false;
            if (el.actions) el.actions.style.display = 'block';

            if (el.contacts) {
                el.contacts.querySelectorAll('.rm-contact, .contact').forEach(c => c.classList.remove('active'));
            }
            if (contactEl) contactEl.classList.add('active');
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
            fetchMessages();
            opts.onContactSelected && opts.onContactSelected(matchId);
        }
            fetchMessages();
            opts.onContactSelected && opts.onContactSelected(contactId);
        }

        /* ── selectMatch ── */
        function selectMatch(matchId) {
            currentMatchId = matchId;
            fetchMessages();
            opts.onContactSelected && opts.onContactSelected(matchId);
        }

        /* ── fetchMessages ── */
        function fetchMessages() {
            if (!el.messages) return;

            let url;
            if (currentMatchId) {
                url = `${EP.messages}?match_id=${currentMatchId}`;
            } else if (currentContact) {
                url = `${EP.messages}?other_user=${currentContact}`;
            } else {
        /* ── fetchMessages ── */
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
                    // ── FIX 2: guard against error responses ──
                    if (!Array.isArray(messages)) {
                        console.error('[RoamanceMessaging] fetchMessages unexpected response:', messages);
                        return;
                    }

                    el.messages.classList.remove('centered-message');
                    el.messages.innerHTML = '';

                    if (!messages.length) {
                        el.messages.classList.add('centered-message');
                        el.messages.innerHTML = '<span>No messages yet</span>';
                        return;
                    }

                    let lastDate = null;

            fetch(url)
                .then(r => r.json())
                .then(messages => {
                    // ── FIX 2: guard against error responses ──
                    if (!Array.isArray(messages)) {
                        console.error('[RoamanceMessaging] fetchMessages unexpected response:', messages);
                        return;
                    }

                    el.messages.classList.remove('centered-message');
                    el.messages.innerHTML = '';

                    if (!messages.length) {
                        el.messages.classList.add('centered-message');
                        el.messages.innerHTML = '<span>No messages yet</span>';
                        return;
                    }

                    let lastDate = null;

                    messages.forEach(msg => {
                        const isMine  = String(msg.sender_id) === String(myUserId);
                        const sentAt  = msg.sent_at ? new Date(msg.sent_at) : null;
                        const msgDate = sentAt ? sentAt.toISOString().slice(0, 10) : null;

                        // ── Date divider ──
                        if (mode === 'inbox' && msgDate && msgDate !== lastDate) {
                            lastDate = msgDate;
                            const divider = document.createElement('div');
                            divider.className = 'date-divider';
                            const today = new Date().toISOString().slice(0, 10);
                            divider.textContent = msgDate === today
                                ? 'Today'
                                : sentAt.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                            el.messages.appendChild(divider);
                        }

                        // ── FIX 3: inbox render now matches PHP-rendered HTML structure ──
                        if (mode === 'inbox') {
                            const wrap = document.createElement('div');
                            wrap.className = `msg${isMine ? ' sent' : ''}`;

                            if (!isMine) {
                                // Other person's avatar (initials)
                                const avatar = document.createElement('div');
                                avatar.className   = 'msg-avatar';
                                avatar.textContent = opts.otherInitials || '?';
                                wrap.appendChild(avatar);
                            }

                            const inner = document.createElement('div');

                            const bubble = document.createElement('div');
                            bubble.className   = `bubble ${isMine ? 'sent' : 'received'}`;
                            bubble.textContent = msg.message;
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
                                avatar.className         = 'msg-avatar';
                                avatar.style.background  = '#534AB7';
                                avatar.style.color       = '#fff';
                                avatar.textContent       = 'Me';
                                wrap.appendChild(avatar);
                            }

                            el.messages.appendChild(wrap);

                        } else {
                            // Compact layout for chatbox / home
                            const wrap   = document.createElement('div');
                            wrap.className = `rm-msg msg${isMine ? ' sent' : ''}`;

                            const bubble = document.createElement('div');
                            bubble.className   = `bubble ${isMine ? 'sent' : 'received'}`;
                            bubble.textContent = (isMine ? 'You: ' : '') + msg.message;
                            wrap.appendChild(bubble);

                            el.messages.appendChild(wrap);
                        }
                    });

                    el.messages.scrollTop = el.messages.scrollHeight;
                })
                .catch(err => console.error('[RoamanceMessaging] fetchMessages:', err));
        }

        /* ── sendMessage ── */
        function sendMessage() {
            const message = el.input ? el.input.value.trim() : '';
            if (!message) return;
                    messages.forEach(msg => {
                        const isMine  = String(msg.sender_id) === String(myUserId);
                        const sentAt  = msg.sent_at ? new Date(msg.sent_at) : null;
                        const msgDate = sentAt ? sentAt.toISOString().slice(0, 10) : null;

                        // ── Date divider ──
                        if (mode === 'inbox' && msgDate && msgDate !== lastDate) {
                            lastDate = msgDate;
                            const divider = document.createElement('div');
                            divider.className = 'date-divider';
                            const today = new Date().toISOString().slice(0, 10);
                            divider.textContent = msgDate === today
                                ? 'Today'
                                : sentAt.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                            el.messages.appendChild(divider);
                        }

                        // ── FIX 3: inbox render now matches PHP-rendered HTML structure ──
                        if (mode === 'inbox') {
                            const wrap = document.createElement('div');
                            wrap.className = `msg${isMine ? ' sent' : ''}`;

                            if (!isMine) {
                                // Other person's avatar (initials)
                                const avatar = document.createElement('div');
                                avatar.className   = 'msg-avatar';
                                avatar.textContent = opts.otherInitials || '?';
                                wrap.appendChild(avatar);
                            }

                            const inner = document.createElement('div');

                            const bubble = document.createElement('div');
                            bubble.className   = `bubble ${isMine ? 'sent' : 'received'}`;
                            bubble.textContent = msg.message;
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
                                avatar.className         = 'msg-avatar';
                                avatar.style.background  = '#534AB7';
                                avatar.style.color       = '#fff';
                                avatar.textContent       = 'Me';
                                wrap.appendChild(avatar);
                            }

                            el.messages.appendChild(wrap);

                        } else {
                            // Compact layout for chatbox / home
                            const wrap   = document.createElement('div');
                            wrap.className = `rm-msg msg${isMine ? ' sent' : ''}`;

                            const bubble = document.createElement('div');
                            bubble.className   = `bubble ${isMine ? 'sent' : 'received'}`;
                            bubble.textContent = (isMine ? 'You: ' : '') + msg.message;
                            wrap.appendChild(bubble);

                            el.messages.appendChild(wrap);
                        }
                    });

                    el.messages.scrollTop = el.messages.scrollHeight;
                })
                .catch(err => console.error('[RoamanceMessaging] fetchMessages:', err));
        }

        /* ── sendMessage ── */
        function sendMessage() {
            const message = el.input ? el.input.value.trim() : '';
            if (!message) return;

            if (PHONE_RE.test(message)) {
                showError(el.error, 'Phone numbers are not allowed in messages.');
                return;
            }

            const body = new URLSearchParams({ message });

            if (currentMatchId) {
                body.set('match_id', currentMatchId);
            } else if (currentContact) {
                body.set('receiver_id', currentContact);
            } else {
                console.warn('[RoamanceMessaging] sendMessage: no match or contact selected');
                return;
            }
            if (PHONE_RE.test(message)) {
                showError(el.error, 'Phone numbers are not allowed in messages.');
                return;
            }

            const body = new URLSearchParams({ message });

            if (currentMatchId) {
                body.set('match_id', currentMatchId);
            } else if (currentContact) {
                body.set('receiver_id', currentContact);
            } else {
                console.warn('[RoamanceMessaging] sendMessage: no match or contact selected');
                return;
            }

            // ── FIX 4: disable input while sending to prevent double-sends ──
            if (el.input)   el.input.disabled   = true;
            if (el.sendBtn) el.sendBtn.disabled  = true;

            fetch(EP.send, {
                method  : 'POST',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                body,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (el.input) el.input.value = '';
                    fetchMessages();
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
                // Re-enable input after send completes
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
                // ── FIX 5: inbox mode uses currentContact set from otherUserId ──
                if (!currentContact && !currentMatchId) return;
                if (el.reportReason) el.reportReason.value = '';
                if (el.reportError)  el.reportError.textContent = '';
                el.reportOverlay.style.display = 'flex';
            });
        }
            // ── FIX 4: disable input while sending to prevent double-sends ──
            if (el.input)   el.input.disabled   = true;
            if (el.sendBtn) el.sendBtn.disabled  = true;

            fetch(EP.send, {
                method  : 'POST',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                body,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (el.input) el.input.value = '';
                    fetchMessages();
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
                // Re-enable input after send completes
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
                // ── FIX 5: inbox mode uses currentContact set from otherUserId ──
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

                // FIX 6: was using currentContact twice (copy-paste bug) — now correct
                const reportedId = currentContact;

                fetch(EP.report, {
                    method  : 'POST',
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body    : new URLSearchParams({ reported_id: reportedId, reason }),
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
        if (el.reportSubmit) {
            el.reportSubmit.addEventListener('click', () => {
                const reason = el.reportReason ? el.reportReason.value.trim() : '';
                if (!reason) {
                    if (el.reportError) el.reportError.textContent = 'Please enter a reason.';
                    return;
                }
                el.reportSubmit.disabled = true;

                // FIX 6: was using currentContact twice (copy-paste bug) — now correct
                const reportedId = currentContact;

                fetch(EP.report, {
                    method  : 'POST',
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body    : new URLSearchParams({ reported_id: reportedId, reason }),
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

                // FIX 7: was bailing out immediately in inbox mode because currentContact was null
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

        /* ── Input event listeners ── */
        if (el.sendBtn) el.sendBtn.addEventListener('click', sendMessage);
        if (el.input) {
            el.input.addEventListener('keypress', e => {
                if (e.key === 'Enter') sendMessage();
            });
        }

        /* ── Polling ── */
        pollTimer = setInterval(fetchMessages, POLL_INTERVAL);

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