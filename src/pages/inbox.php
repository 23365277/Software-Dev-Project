<?php
	$pageTitle = "Roamance - Inbox";
	$pageCSS = "/assets/css/inbox.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>
<div class="inbox-layout">
    <div class="inbox-sidebar">
        <h2 class="inbox-panel-title">People</h2>
    </div>
    <div class="inbox-chat">
        <div class="inbox-chat-header">
            <div class="inbox-chat-info">
                <h2>Name: </h2>
                <h3>Age: </h3>
            </div>
            <img src="/assets/images/profile-pic.jpg" class="rounded-circle inbox-avatar" alt="Profile Image">
        </div>
        <div class="inbox-messages"></div>
        <div class="inbox-message-bar">
            <h2 class="inbox-panel-title">Message Bar</h2>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
