<?php
    // Only include database if needed for functionality. For now, comment out to avoid errors.
	require_once __DIR__ . '/../../config/database.php';
	
?>

<link rel="stylesheet" href="/assets/css/messaging.css">

<div id="chatbox-container">
    <input type="checkbox" id="chatbox-toggle" style="display:none;">

    <!-- Chat open/close button -->
    <label for="chatbox-toggle" id="chatbox-arrow-btn" title="Inbox">
        Inbox <span style="font-size:1.7em; color:#fff;">&#8592;</span>
    </label>

    <!-- Chat window -->
    <div id="chatbox-window">
        <div id="chatbox-header">
            <span>Chat / Inbox</span>
            <label for="chatbox-toggle" style="cursor:pointer; font-size:1.3em; color:#fff;">&times;</label>
        </div>

        <div id="chatbox-body">
            <!-- Sidebar: contacts -->
            <div id="chatbox-contacts">
                <input type="text" id="contact-search" placeholder="Search for contacts" disabled>
                <button id = "chatbox-send" disabled>Search</button>
            </div>

            <!-- Messages area -->
            <div id="chatbox-messages" class="centered-message">
                <span>Select a contact to start chatting</span>
            </div>
        </div>

        <!-- Input area -->
        <div id="chatbox-input-area">
            <input type="text" id="chatbox-input" placeholder="Type your message..." disabled>
            <button id="chatbox-send" disabled>Send</button>
        </div>
    </div>
</div>

<?php if(isset($_SESSION["user_id"])): ?>
    <script src="/includes/js/messages.js"></script>
<?php endif; ?>

<?php if(isset($_SESSION["user_id"])): ?>
    <script src="/includes/js/utils.js"></script>
<?php endif; ?>
