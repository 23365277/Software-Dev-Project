<?php
    require_once __DIR__ . '/../../config/database.php';
?>
<link rel="stylesheet" href="/assets/css/messaging.css">

<div id="chatbox-container">
    <input type="checkbox" id="chatbox-toggle" style="display:none;">
    <label for="chatbox-toggle" id="chatbox-arrow-btn" title="Inbox">
        Inbox <span style="font-size:1.7em; color:#fff;">&#8592;</span>
    </label>

    <div id="chatbox-window">
        <div id="chatbox-header">
            <span>Chat / Inbox</span>
            <div style="display:flex; align-items:center; gap:10px;">
                <div id="chatbox-actions" style="display:none; position:relative;">
                    <button id="chatbox-actions-toggle">Actions &#9662;</button>
                    <div id="chatbox-actions-menu" style="display:none;">
                        <button id="chatbox-report-btn">&#9872; Report User</button>
                        <button id="chatbox-block-btn">&#128683; Block User</button>
                    </div>
                </div>
                <label for="chatbox-toggle" style="cursor:pointer; font-size:1.3em; color:#fff;">&times;</label>
            </div>
        </div>

        <div id="chatbox-body">
            <div id="chatbox-contacts"></div>
            <div id="chatbox-messages" class="centered-message">
                <span>Select a contact to start chatting</span>
            </div>
        </div>

        <div id="chatbox-error"></div>
        <div id="chatbox-attach-preview" style="display:none;align-items:center;gap:8px;padding:5px 8px;background:#f5f5f5;border-top:1px solid #e2e8f0;"></div>
        <div id="chatbox-input-area">
            <label for="chatbox-attach-input" class="attach-btn" title="Attach image">📎
                <input id="chatbox-attach-input" type="file" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none;">
            </label>
            <input type="text" id="chatbox-input" placeholder="Type your message..." disabled>
            <button id="chatbox-send" disabled>Send</button>
        </div>
    </div>
</div>

<!-- Report User Modal -->
<div id="report-modal-overlay" style="display:none;">
    <div id="report-modal">
        <h3>Report User</h3>
        <p>Please describe the reason for this report:</p>
        <p style="font-size:12px;color:#888;background:#f5f5f5;border-radius:6px;padding:8px 10px;margin-bottom:8px;">
            &#9432; When a report is submitted, admins may review the conversation between you and this user as part of their investigation.
        </p>
        <textarea id="report-reason" placeholder="Enter reason..." rows="4"></textarea>
        <div id="report-modal-error"></div>
        <div id="report-modal-actions">
            <button id="report-submit-btn">Submit Report</button>
            <button id="report-cancel-btn">Cancel</button>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
<script>
    if (typeof myUserId === 'undefined') {
        var myUserId = <?= json_encode($_SESSION['user_id']) ?>;
    }
</script>
<!-- Single unified module -->
<script src="/includes/js/messages.js"></script>
<script>
    RoamanceMessaging.init({
        myUserId        : myUserId,
        mode            : 'chatbox',
        contactsEl      : '#chatbox-contacts',
        messagesEl      : '#chatbox-messages',
        attachInputEl   : '#chatbox-attach-input',
        attachPreviewEl : '#chatbox-attach-preview',
        inputEl         : '#chatbox-input',
        sendBtnEl       : '#chatbox-send',
        errorEl         : '#chatbox-error',
        actionsEl       : '#chatbox-actions',
        actionsToggleEl : '#chatbox-actions-toggle',
        actionsMenuEl   : '#chatbox-actions-menu',
        reportBtnEl     : '#chatbox-report-btn',
        blockBtnEl      : '#chatbox-block-btn',
        reportOverlayEl : '#report-modal-overlay',
        reportReasonEl  : '#report-reason',
        reportErrorEl   : '#report-modal-error',
        reportSubmitEl  : '#report-submit-btn',
        reportCancelEl  : '#report-cancel-btn',
    });
</script>
<?php endif; ?>

<!-- Photo lightbox (shared across chatbox and home messaging) -->
<div id="photo-lightbox" onclick="closePhotoLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:99999;align-items:center;justify-content:center;cursor:zoom-out;">
    <img id="photo-lightbox-img" src="" alt="" style="max-width:90vw;max-height:90vh;border-radius:8px;box-shadow:0 8px 40px rgba(0,0,0,0.6);">
</div>
<script>
function openPhotoLightbox(src) {
    const lb = document.getElementById('photo-lightbox');
    document.getElementById('photo-lightbox-img').src = src;
    lb.style.display = 'flex';
}
function closePhotoLightbox() {
    document.getElementById('photo-lightbox').style.display = 'none';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePhotoLightbox();
});
</script>