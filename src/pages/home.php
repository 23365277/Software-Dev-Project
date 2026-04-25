<?php
$pageTitle = "Roamance - Home";
$pageCSS = ["/assets/css/messaging.css",
            "/assets/css/connections_passport.css",
            "/assets/css/home.css?v=" . filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/css/home.css')];
include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";

$userId = $_SESSION["user_id"];

$matches = getMatches($pdo, $userId);
$likes = getLikes($pdo, $userId);

$latestMatch = $matches[0] ?? null;
$latestLike = $likes[0] ?? null;

$homeProfile = getProfileInfoById($userId);
$welcomeName = $homeProfile['first_name'] ?? '';

?>

<?php if ($welcomeName): ?>
<div class="welcome-banner">
    Welcome back, <?php echo htmlspecialchars($welcomeName); ?>!
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
    </div>


    <div class="col-lg-6 col-md-6 col-sm-12">

        <!-- ── Messaging panel ── -->

        <!-- ── Messaging panel ── -->
        <div id="home-messages-panel">
            <h2 style="display:flex; justify-content:space-between; align-items:center;">
                Messages
                <?php if (isset($_SESSION['user_id'])): ?>
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
            <?php if (isset($_SESSION['user_id'])): ?>
            <div id="home-error"></div>
            <div id="home-attach-preview" style="display:none;align-items:center;gap:8px;padding:6px 12px;background:#f5f5f5;border-top:1px solid #e0e0e0;"></div>
            <div id="home-input-area">
                <label for="home-attach-input" class="attach-btn" title="Attach image">📎
                    <input id="home-attach-input" type="file" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none;">
                </label>
                <input type="text" id="home-msg-input" placeholder="Type your message..." disabled>
                <button id="home-msg-send" disabled>Send</button>
            </div>
            <?php endif; ?>
        </div>

        <!-- Report User Modal -->
        <div id="home-report-modal-overlay" style="display:none;">
            <div id="home-report-modal">
                <h3>Report User</h3>
                <p>Please describe the reason for this report:</p>
                <p style="font-size:12px;color:#888;background:#f5f5f5;border-radius:6px;padding:8px 10px;margin-bottom:8px;">
                    &#9432; When a report is submitted, admins may review the conversation between you and this user as part of their investigation.
                </p>
                <textarea id="home-report-reason" placeholder="Enter reason..." rows="4"></textarea>
                <div id="home-report-modal-error"></div>
                <div id="home-report-modal-actions">
                    <button id="home-report-submit-btn">Submit Report</button>
                    <button id="home-report-cancel-btn">Cancel</button>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-3 pages-row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <a href="/pages/discovery_feed.php" class="page-link">
                    <div class="page-card page-card-discovery-feed">
                        <div class="page-card-overlay"></div>
                        <div class="page-card-content">
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
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="/pages/matches_likes.php" class="page-link">
            <div class="page-card page-card-matches-likes">
                <div class="page-card-content">
                    <h3>Matches and Likes</h3>
                    <span class="page-card-instruction">VIEW CONNECTIONS</span>
                </div>
        </a>
                <div class="dashboard-connections-list">
                    <?php if ($latestMatch): ?>
                        <?php
                            $profile = $latestMatch;
                            $cardMode = 'dashboard';
                            $cardLabel = 'Latest Match';
                            $cardHref = '/pages/matches_likes.php';
                            include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/connections_passport.php';
                        ?>
                    <?php endif; ?>
                    <?php if ($latestLike): ?>
                        <?php
                            $profile = $latestLike;
                            $cardMode = 'dashboard';
                            $cardLabel = 'Latest Passport Liked';
                            $cardHref = '/pages/matches_likes.php';
                            include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/connections_passport.php';
                        ?>
                    <?php endif; ?>
                    <?php if (!$latestMatch && !$latestLike): ?>
                        <div class="dashboard-connections-empty">
                            <p>No new matches or likes yet.</p>
                            <a href="/pages/discovery_feed.php" class="dashboard-empty-btn">Go to Discovery</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
    </div>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
<script>
    const myUserId = <?php echo json_encode($_SESSION['user_id']); ?>;
</script>
<script src="/includes/js/messages.js"></script>
<script>
    RoamanceMessaging.init({
        myUserId        : myUserId,
        mode            : 'home',
        contactsEl      : '#home-contacts',
        messagesEl      : '#home-messages-area',
        attachInputEl   : '#home-attach-input',
        attachPreviewEl : '#home-attach-preview',
        inputEl         : '#home-msg-input',
        sendBtnEl       : '#home-msg-send',
        errorEl         : '#home-error',
        actionsEl       : '#home-actions',
        actionsToggleEl : '#home-actions-toggle',
        actionsMenuEl   : '#home-actions-menu',
        reportBtnEl     : '#home-report-btn',
        blockBtnEl      : '#home-block-btn',
        reportOverlayEl : '#home-report-modal-overlay',
        reportReasonEl  : '#home-report-reason',
        reportErrorEl   : '#home-report-modal-error',
        reportSubmitEl  : '#home-report-submit-btn',
        reportCancelEl  : '#home-report-cancel-btn',
    });
</script>
<?php endif; ?>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/login_welcome.php';
?>