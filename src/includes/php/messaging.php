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
                <button id="chatbox-report-btn" title="Report this user" style="display:none;">&#9872; Report</button>
                <label for="chatbox-toggle" style="cursor:pointer; font-size:1.3em; color:#fff;">&times;</label>
            </div>
        </div>
        <div id="chatbox-body">
            <div id="chatbox-contacts">
                <input type="text" id="contact-search" placeholder="Search for contacts" disabled> 
                <button id="contact-search-btn" disabled>Search</button>
            </div>
            <div id="chatbox-messages" class="centered-message">
                <span>Select a contact to start chatting</span>
            </div>
        </div>
        <div id="chatbox-error"></div>
        <div id="chatbox-input-area">
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
        <textarea id="report-reason" placeholder="Enter reason..." rows="4"></textarea>
        <div id="report-modal-error"></div>
        <div id="report-modal-actions">
            <button id="report-submit-btn">Submit Report</button>
            <button id="report-cancel-btn">Cancel</button>
        </div>
    </div>
</div>

<?php if(isset($_SESSION["user_id"])): ?>
    <script>
        if (typeof myUserId === 'undefined') {
            var myUserId = <?php echo json_encode($_SESSION['user_id']); ?>;
        }
    </script>
    <script src="/includes/js/messages.js"></script>
    <script src="/includes/js/utils.js"></script>
<?php endif; ?>