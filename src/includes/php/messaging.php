<?php
    // Only include database if needed for functionality. For now, comment out to avoid errors.
	require_once __DIR__ . '/../../config/database.php';
	
?>

<link rel="stylesheet" href="/assets/css/messaging.css">

<div id="chatbox-container">
    <input type="checkbox" id="chatbox-toggle" style="display:none;">
    <label for="chatbox-toggle" id="chatbox-arrow-btn" title="Open Chat"> Open Chat 
			
	</label>
        <span style="font-size:1.7em; color:#fff;">&#8592;</span>
    </label>
    <div id="chatbox-window">
        <div id="chatbox-header">
            <span>Chat / Inbox</span>
            <label for="chatbox-toggle" style="cursor:pointer; font-size:1.3em; color:#fff;">&times;</label>
        </div>
        <div id="chatbox-messages" class="centered-message">
		<?php if(!isset($_SESSION['user_id'])): ?>
			<span>Please log in to use chat.</span>
		<?php else: ?>
			<span>No messages yet</span>
		<?php endif; ?>
	</div>
	<input type="text" name="Message Box" placeholder="Start typing to send a message..." id="messageBox">
    </div>
</div>

<script src="includes/js/utils.js"></script>
