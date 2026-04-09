<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";
if (isset($login_successful) && $login_successful) {
    $_SESSION['logged_in'] = true;
    $_SESSION['email'] = $user_email;
    header("Location: dashboard.php");
    exit();
}
$pageTitle = "Roamance - Home";
$pageCSS = "/assets/css/messaging.css";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
?>

<?php if (isset($_SESSION['user_id']) && !empty($_COOKIE['user_name'])): ?>
<div class="welcome-banner">
    Welcome back, <?php echo htmlspecialchars($_COOKIE['user_name']); ?>!
</div>
<?php endif; ?>

<div class="container col-12 row">
    <div class="card col-lg-4 col-md-6 col-sm-12">
        <h2 class="matches center-text">Matches and Likes</h2>
    </div>

    <div class="container col-lg-8 col-md-6 col-sm-12">

        <div id="home-messages-panel">
            <h2>Messages</h2>
            <div id="home-messages-body">
                <div id="home-contacts"></div>
                <div id="home-messages-area" class="centered-message">
                    <span><?php echo isset($_SESSION['user_id']) ? 'Select a conversation' : 'Log in to view your messages'; ?></span>
                </div>
            </div>
            <?php if(isset($_SESSION['user_id'])): ?>
            <div id="home-error"></div>
            <div id="home-input-area">
                <input type="text" id="home-msg-input" placeholder="Type your message..." disabled>
                <button id="home-msg-send" disabled>Send</button>
            </div>
            <?php endif; ?>
        </div>

        <div class="container row">
            <div class="card col-lg-5 col-md-5 col-sm-12 mt-4">
                <h2 class="center-text">Discovery Feed</h2>
            </div>
            <div class="card col-lg-5 col-md-5 col-sm-12 mt-4">
                <h2 class="center-text">Post A Trip</h2>
            </div>
        </div>
    </div>
</div>

<?php if(isset($_SESSION["user_id"])): ?>
<script>
const myUserId = <?php echo json_encode($_SESSION['user_id']); ?>;

(function () {
    let homeCurrentContact = null;

    const homeContactsEl = document.getElementById('home-contacts');
    const homeMessagesEl = document.getElementById('home-messages-area');
    const homeInputEl    = document.getElementById('home-msg-input');
    const homeSendBtn    = document.getElementById('home-msg-send');
    const homeErrorDiv   = document.getElementById('home-error');
    let homeErrorTimeout = null;

    const HOME_PHONE_REGEX = /(\+?\d[\s\-.()\[\]]{0,3}){7,}/;

    function homeShowError(msg) {
        homeErrorDiv.textContent = msg;
        homeErrorDiv.classList.add('visible');
        clearTimeout(homeErrorTimeout);
        homeErrorTimeout = setTimeout(() => homeErrorDiv.classList.remove('visible'), 3000);
    }

    async function homeLoadContacts() {
        try {
            const res = await fetch('/includes/php/get_contacts.php');
            const contacts = await res.json();
            homeContactsEl.innerHTML = '';
            if (contacts.length === 0) {
                const span = document.createElement('span');
                span.textContent = 'No contacts yet';
                span.style.padding = '10px';
                span.style.fontSize = '0.85em';
                homeContactsEl.appendChild(span);
                return;
            }
            contacts.forEach(contact => {
                const div = document.createElement('div');
                div.classList.add('home-contact');
                div.dataset.userid = contact.id;
                div.textContent = contact.email;
                div.addEventListener('click', () => homeSelectContact(contact.id, div));
                homeContactsEl.appendChild(div);
            });
        } catch (err) {
            console.error('homeLoadContacts error:', err);
            homeContactsEl.innerHTML = '<span style="padding:10px">Error loading contacts</span>';
        }
    }

    function homeSelectContact(contactId, contactEl) {
        homeCurrentContact = contactId;
        homeInputEl.disabled = false;
        homeSendBtn.disabled = false;
        homeContactsEl.querySelectorAll('.home-contact').forEach(c => c.classList.remove('active'));
        contactEl.classList.add('active');
        homeFetchMessages();
    }

    function homeFetchMessages() {
        if (!homeCurrentContact) return;
        fetch('/includes/php/get_message.php?other_user=' + homeCurrentContact)
            .then(res => res.json())
            .then(messages => {
                homeMessagesEl.classList.remove('centered-message');
                homeMessagesEl.innerHTML = '';
                if (!messages.length) {
                    homeMessagesEl.classList.add('centered-message');
                    homeMessagesEl.innerHTML = '<span>No messages yet</span>';
                    return;
                }
                messages.forEach(msg => {
                    const div = document.createElement('div');
                    div.classList.add('home-msg-bubble');
                    const isMine = String(msg.sender_id) === String(myUserId);
                    if (isMine) div.classList.add('sent');
                    div.textContent = (isMine ? 'You: ' : '') + msg.message;
                    homeMessagesEl.appendChild(div);
                });
                homeMessagesEl.scrollTop = homeMessagesEl.scrollHeight;
            })
            .catch(err => console.error('homeFetchMessages error:', err));
    }

    function homeSendMessage() {
        if (!homeCurrentContact) return;
        const message = homeInputEl.value.trim();
        if (!message) return;

        if (HOME_PHONE_REGEX.test(message)) {
            homeShowError('Phone numbers are not allowed in messages.');
            return;
        }

        fetch('/includes/php/send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                message: message,
                receiver_id: homeCurrentContact
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                homeInputEl.value = '';
                homeFetchMessages();
            } else {
                console.error('Send error:', data.error);
            }
        })
        .catch(err => console.error('homeSendMessage error:', err));
    }

    homeSendBtn.addEventListener('click', homeSendMessage);
    homeInputEl.addEventListener('keypress', e => { if (e.key === 'Enter') homeSendMessage(); });
    setInterval(homeFetchMessages, 3000);
    homeLoadContacts();
})();
</script>
<?php endif; ?>

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/messaging.php";
include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/login_welcome.php';
?>