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
$pageCSS = ["/assets/css/messaging.css",
            "/assets/css/home.css"];
include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
?>

<?php if (isset($_SESSION['user_id']) && !empty($_COOKIE['user_name'])): ?>
<div class="welcome-banner">
    Welcome back, <?php echo htmlspecialchars($_COOKIE['user_name']); ?>!
</div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-lg-3 col-md-12 col-sm-12">
        <div class="page-card-home">
            <div class="home-content">
                <span class="home-title">Welcome to Roamance</span>

                <h2>Where every journey can lead to a new connection.</h2>

                <p>
                    Explore traveller profiles, share your next destination, shape your passport, and connect with people who are drawn to the same places, plans and adventures as you.
                </p>
            </div>
        </div>
        <a href="/pages/profile_view.php" class="page-link">
            <div class="page-card page-card-profile">
                <div class="page-card-content">
                    <h3>Your Passport</h3>
                    <p>
                        Update your bio, photos, preferences and personal details
                    </p>
                    <span class="page-card-instruction">OPEN YOUR PROFILE</span>
                </div>
            </div>
        </a>
<div class="row g-3">
    <div class="col-lg-3 col-md-12 col-sm-12">
        <div class="page-card-home">
            <div class="home-content">
                <span class="home-title">Welcome to Roamance</span>

                <h2>Where every journey can lead to a new connection.</h2>

                <p>
                    Explore traveller profiles, share your next destination, shape your passport, and connect with people who are drawn to the same places, plans and adventures as you.
                </p>
            </div>
        </div>
        <a href="/pages/profile_view.php" class="page-link">
            <div class="page-card page-card-profile">
                <div class="page-card-content">
                    <h3>Your Passport</h3>
                    <p>
                        Update your bio, photos, preferences and personal details
                    </p>
                    <span class="page-card-instruction">OPEN YOUR PROFILE</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div id="home-messages-panel">
            <h2 style="display:flex; justify-content:space-between; align-items:center;">
                Messages
                <?php if(isset($_SESSION['user_id'])): ?>
                <div id="home-actions" style="display:none; position:relative;">
                    <button id="home-actions-toggle">Actions &#9662;</button>
                    <div id="home-actions-menu" style="display:none;">
                        <button id="home-report-btn">&#9872; Report User</button>
                        <button id="home-block-btn">&#128683; Block User</button>
                    </div>
                </div>
                <?php endif; ?>
            </h2>
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
        <div class="row mt-4 g-3 pages-row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/pages/discovery_feed.php" class="page-link">
                    <div class="page-card page-card-discovery-feed">
                        <div class="page-card-overlay"></div>
                        <div class= "page-card-content">
                            <h3>Passports</h3>
                            <p>
                                Browse traveller profiles and discover people who share the same travel desires.
                            </p>
                            <span class="page-card-instruction">ENTER FEED</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/pages/post_a_trip.php" class="page-link">
                    <div class="page-card page-card-trip">
                        <div class="page-card-content">
                            <h3>Post A Trip</h3>
                            <p>
                                Add an upcoming or past trip to your profile to show off and connect with travellers heading the same way.
                            </p>
                            <span class="page-card-instruction">ADD TRIP</span>
                        </div>
                    </div>
                </a>
        <div class="row mt-4 g-3 pages-row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/pages/discovery_feed.php" class="page-link">
                    <div class="page-card page-card-discovery-feed">
                        <div class="page-card-overlay"></div>
                        <div class= "page-card-content">
                            <h3>Passports</h3>
                            <p>
                                Browse traveller profiles and discover people who share the same travel desires.
                            </p>
                            <span class="page-card-instruction">ENTER FEED</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/pages/post_a_trip.php" class="page-link">
                    <div class="page-card page-card-trip">
                        <div class="page-card-content">
                            <h3>Post A Trip</h3>
                            <p>
                                Add an upcoming or past trip to your profile to show off and connect with travellers heading the same way.
                            </p>
                            <span class="page-card-instruction">ADD TRIP</span>
                        </div>
                    </div>
                </a>

        <!-- Home Report User Modal -->
        <div id="home-report-modal-overlay" style="display:none;">
            <div id="home-report-modal">
                <h3>Report User</h3>
                <p>Please describe the reason for this report:</p>
                <textarea id="home-report-reason" placeholder="Enter reason..." rows="4"></textarea>
                <div id="home-report-modal-error"></div>
                <div id="home-report-modal-actions">
                    <button id="home-report-submit-btn">Submit Report</button>
                    <button id="home-report-cancel-btn">Cancel</button>
                </div>
            </div>
        </div>

        <div class="container row">
            <div class="card col-lg-5 col-md-5 col-sm-12 mt-4">
                <h2 class="center-text">Discovery Feed</h2>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/pages/destination_search.php" class="page-link">
                    <div class="page-card page-card-atlas">
                        <div class="page-card-content">
                            <h3>Atlas</h3>
                            <p>
                                View your travel stats and also select a country to add trip preferences in discovery feed.
                            </p>
                            <span class="page-card-instruction">VIEW YOUR STATS</span>
                        </div>
                    </div>
                </a>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/pages/destination_search.php" class="page-link">
                    <div class="page-card page-card-atlas">
                        <div class="page-card-content">
                            <h3>Atlas</h3>
                            <p>
                                View your travel stats and also select a country to add trip preferences in discovery feed.
                            </p>
                            <span class="page-card-instruction">VIEW YOUR STATS</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="card col-lg-3 col-md-6 col-sm-12">
        <h2 class="matches center-text">Matches and Likes</h2>
    </div>
    <div class="card col-lg-3 col-md-6 col-sm-12">
        <h2 class="matches center-text">Matches and Likes</h2>
    </div>
</div>

<?php if(isset($_SESSION["user_id"])): ?>
<script>
const myUserId = <?php echo json_encode($_SESSION['user_id']); ?>;

(function () {
    let homeCurrentContact = null;

    const homeContactsEl    = document.getElementById('home-contacts');
    const homeMessagesEl    = document.getElementById('home-messages-area');
    const homeInputEl       = document.getElementById('home-msg-input');
    const homeSendBtn       = document.getElementById('home-msg-send');
    const homeErrorDiv      = document.getElementById('home-error');
    const homeActionsContainer = document.getElementById('home-actions');
    const homeActionsToggle = document.getElementById('home-actions-toggle');
    const homeActionsMenu   = document.getElementById('home-actions-menu');
    const homeReportBtn     = document.getElementById('home-report-btn');
    const homeBlockBtn      = document.getElementById('home-block-btn');
    const homeReportOverlay = document.getElementById('home-report-modal-overlay');
    const homeReportReason  = document.getElementById('home-report-reason');
    const homeReportError   = document.getElementById('home-report-modal-error');
    const homeReportSubmit  = document.getElementById('home-report-submit-btn');
    const homeReportCancel  = document.getElementById('home-report-cancel-btn');
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
        if (homeActionsContainer) homeActionsContainer.style.display = 'block';
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

    // --- Actions dropdown ---

    if (homeActionsToggle) {
        homeActionsToggle.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = homeActionsMenu.style.display === 'block';
            homeActionsMenu.style.display = isOpen ? 'none' : 'block';
        });
    }

    document.addEventListener('click', () => {
        if (homeActionsMenu) homeActionsMenu.style.display = 'none';
    });

    // --- Report ---

    if (homeReportBtn) {
        homeReportBtn.addEventListener('click', () => {
            homeActionsMenu.style.display = 'none';
            if (!homeCurrentContact) return;
            homeReportReason.value = '';
            homeReportError.textContent = '';
            homeReportOverlay.style.display = 'flex';
        });
    }

    if (homeReportCancel) {
        homeReportCancel.addEventListener('click', () => {
            homeReportOverlay.style.display = 'none';
        });
    }

    if (homeReportOverlay) {
        homeReportOverlay.addEventListener('click', e => {
            if (e.target === homeReportOverlay) homeReportOverlay.style.display = 'none';
        });
    }

    if (homeReportSubmit) {
        homeReportSubmit.addEventListener('click', () => {
            const reason = homeReportReason.value.trim();
            if (!reason) {
                homeReportError.textContent = 'Please enter a reason.';
                return;
            }
            homeReportSubmit.disabled = true;
            fetch('/includes/php/report_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ reported_id: homeCurrentContact, reason: reason })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    homeReportOverlay.style.display = 'none';
                    homeShowError('User reported successfully.');
                } else {
                    homeReportError.textContent = data.error || 'Failed to submit report.';
                }
            })
            .catch(() => { homeReportError.textContent = 'Network error. Please try again.'; })
            .finally(() => { homeReportSubmit.disabled = false; });
        });
    }

    // --- Block ---

    if (homeBlockBtn) {
        homeBlockBtn.addEventListener('click', () => {
            homeActionsMenu.style.display = 'none';
            if (!homeCurrentContact) return;
            fetch('/includes/php/block_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ block_id: homeCurrentContact })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    homeShowError('User blocked successfully.');
                    homeActionsContainer.style.display = 'none';
                    homeCurrentContact = null;
                    homeInputEl.disabled = true;
                    homeSendBtn.disabled = true;
                    homeMessagesEl.classList.add('centered-message');
                    homeMessagesEl.innerHTML = '<span>Select a conversation</span>';
                    homeLoadContacts();
                } else {
                    homeShowError(data.error || 'Failed to block user.');
                }
            })
            .catch(() => { homeShowError('Network error. Please try again.'); });
        });
    }
})();
</script>
<?php endif; ?>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/login_welcome.php';
?>