<?php
    $pageTitle = "Roamance - Inbox";
    $pageCSS   = "/assets/css/inbox.css";
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';

    $currentUserId = $_SESSION['user_id'];

    // ── Fetch all matches ─────────────────────────────────────────────────────
    $matchesQuery = $pdo->prepare("
        SELECT 
            m.match_id,
            m.matched_at,
            CASE WHEN m.user1_id = ? THEN m.user2_id ELSE m.user1_id END AS other_user_id,
            p.first_name,
            p.last_name,
            p.profile_picture,
            p.date_of_birth,
            p.city,
            p.country,
            (SELECT message  FROM messages WHERE match_id = m.match_id ORDER BY sent_at DESC LIMIT 1) AS last_message,
            (SELECT sent_at  FROM messages WHERE match_id = m.match_id ORDER BY sent_at DESC LIMIT 1) AS last_message_at,
            (SELECT COUNT(*) FROM messages WHERE match_id = m.match_id AND receiver_id = ? AND seen = 0) AS unread_count
        FROM matches m
        JOIN profiles p ON p.user_id = CASE WHEN m.user1_id = ? THEN m.user2_id ELSE m.user1_id END
        WHERE m.user1_id = ? OR m.user2_id = ?
        ORDER BY last_message_at DESC
    ");
    $matchesQuery->execute([$currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId]);
    $matches = $matchesQuery->fetchAll(PDO::FETCH_ASSOC);

    // ── Selected match ────────────────────────────────────────────────────────
    $selectedMatchId = $_GET['match_id'] ?? ($matches[0]['match_id'] ?? null);

    $selectedMatch = null;
    foreach ($matches as $match) {
        if ($match['match_id'] == $selectedMatchId) {
            $selectedMatch = $match;
            break;
        }
    }

    // ── Fetch messages and mark seen ──────────────────────────────────────────
    $messages = [];
    if ($selectedMatchId) {
        $pdo->prepare("UPDATE messages SET seen = 1 WHERE match_id = ? AND receiver_id = ?")
            ->execute([$selectedMatchId, $currentUserId]);

        $msgQ = $pdo->prepare("
            SELECT sender_id, message, sent_at 
            FROM messages 
            WHERE match_id = ?
              AND (
                    (sender_id   = ? AND deleted_sender   = 0) OR
                    (receiver_id = ? AND deleted_receiver = 0)
                  )
            ORDER BY sent_at ASC
        ");
        $msgQ->execute([$selectedMatchId, $currentUserId, $currentUserId]);
        $messages = $msgQ->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Incoming likes (people who liked you, not yet matched) ───────────────
    $incomingLikesQ = $pdo->prepare("
        SELECT p.user_id, p.first_name, p.last_name, p.profile_picture
        FROM likes l
        JOIN profiles p ON p.user_id = l.sender_id
        WHERE l.receiver_id = ?
          AND NOT EXISTS (
              SELECT 1 FROM matches m
              WHERE (m.user1_id = ? AND m.user2_id = l.sender_id)
                 OR (m.user2_id = ? AND m.user1_id = l.sender_id)
          )
        ORDER BY l.created_at DESC
        LIMIT 20
    ");
    $incomingLikesQ->execute([$currentUserId, $currentUserId, $currentUserId]);
    $incomingLikes = $incomingLikesQ->fetchAll(PDO::FETCH_ASSOC);

    // ── Helpers ───────────────────────────────────────────────────────────────
    function getAge($dob) {
        return (new DateTime($dob))->diff(new DateTime())->y;
    }

    function formatTime($datetime) {
        if (!$datetime) return '';
        $diff = time() - strtotime($datetime);
        if ($diff < 3600)   return round($diff / 60) . 'm';
        if ($diff < 86400)  return round($diff / 3600) . 'h';
        if ($diff < 604800) return date('D', strtotime($datetime));
        return date('d M', strtotime($datetime));
    }
?>

<div class="inbox-page">
<h1 class="inbox-page-title">Messages</h1>
<div class="inbox-wrap">

    <!-- ── Sidebar ── -->
    <div class="sidebar">
        <div class="sidebar-header"><h2>Messages</h2></div>
        <div class="search-box">
            <input type="text" placeholder="Search conversations...">
        </div>
        <div class="people-list">
            <?php foreach ($matches as $match): ?>
                <a href="?match_id=<?= $match['match_id'] ?>"
                   class="person-row <?= $match['match_id'] == $selectedMatchId ? 'active' : '' ?>">

                    <?php if ($match['profile_picture']): ?>
                        <img src="<?= htmlspecialchars($match['profile_picture']) ?>" class="avatar" alt="Profile">
                    <?php else: ?>
                        <div class="avatar">
                            <?= strtoupper(substr($match['first_name'], 0, 1) . substr($match['last_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>

                    <div class="person-info">
                        <div class="person-name"><?= htmlspecialchars($match['first_name'] . ' ' . $match['last_name']) ?></div>
                        <div class="person-preview"><?= htmlspecialchars($match['last_message'] ?? 'No messages yet') ?></div>
                    </div>

                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                        <span class="person-time"><?= formatTime($match['last_message_at']) ?></span>
                        <?php if ($match['unread_count'] > 0): ?>
                            <span class="unread-dot"></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ── Chat panel ── -->
    <div class="chat-panel">
        <?php if ($selectedMatch): ?>

            <div class="chat-header">
                <?php if ($selectedMatch['profile_picture']): ?>
                    <img src="<?= htmlspecialchars($selectedMatch['profile_picture']) ?>" class="chat-header-avatar" alt="Profile">
                <?php else: ?>
                    <div class="chat-header-avatar">
                        <?= strtoupper(substr($selectedMatch['first_name'], 0, 1) . substr($selectedMatch['last_name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="chat-header-info">
                    <h2><?= htmlspecialchars($selectedMatch['first_name'] . ' ' . $selectedMatch['last_name']) ?></h2>
                    <p><?= getAge($selectedMatch['date_of_birth']) ?> · <?= htmlspecialchars($selectedMatch['city'] . ', ' . $selectedMatch['country']) ?></p>
                </div>
                <!-- Actions dropdown (report/block) -->
                <div id="inbox-actions" style="margin-left:auto; position:relative;">
                    <button id="inbox-actions-toggle">Actions &#9662;</button>
                    <div id="inbox-actions-menu" style="display:none;">
                        <button id="inbox-report-btn">&#9872; Report User</button>
                        <button id="inbox-block-btn">&#128683; Block User</button>
                    </div>
                </div>
            </div>

            <!-- Messages are rendered server-side on initial load for performance,
                 then the JS module takes over polling for new ones. -->
            <div class="messages-area" id="inbox-messages">
                <?php
                $lastDate = null;
                foreach ($messages as $msg):
                    $msgDate = date('Y-m-d', strtotime($msg['sent_at']));
                    $isSent  = $msg['sender_id'] == $currentUserId;
                ?>
                    <?php if ($msgDate !== $lastDate): $lastDate = $msgDate; ?>
                        <div class="date-divider">
                            <?= $msgDate === date('Y-m-d')
                                ? 'Today'
                                : date('d M Y', strtotime($msg['sent_at'])) ?>
                        </div>
                    <?php endif; ?>

                    <div class="msg <?= $isSent ? 'sent' : '' ?>">
                        <?php if (!$isSent): ?>
                            <div class="msg-avatar">
                                <?= strtoupper(substr($selectedMatch['first_name'], 0, 1) . substr($selectedMatch['last_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <div class="bubble <?= $isSent ? 'sent' : 'received' ?>">
                                <?= htmlspecialchars($msg['message']) ?>
                            </div>
                            <div class="msg-time <?= $isSent ? 'text-end' : '' ?>">
                                <?= date('g:i A', strtotime($msg['sent_at'])) ?>
                            </div>
                        </div>
                        <?php if ($isSent): ?>
                            <div class="msg-avatar" style="background:#534AB7;color:#fff;">Me</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="message-bar">
                <button class="attach-btn">+</button>
                <input class="msg-input" id="inbox-msg-input" type="text" placeholder="Write a message...">
                <button class="send-btn" id="inbox-send-btn">&#10148;</button>
            </div>

            <!-- Report modal -->
            <div id="inbox-report-modal-overlay" style="display:none;">
                <div id="inbox-report-modal">
                    <h3>Report User</h3>
                    <p>Please describe the reason for this report:</p>
                    <textarea id="inbox-report-reason" placeholder="Enter reason..." rows="4"></textarea>
                    <div id="inbox-report-modal-error"></div>
                    <div id="inbox-report-modal-actions">
                        <button id="inbox-report-submit-btn">Submit Report</button>
                        <button id="inbox-report-cancel-btn">Cancel</button>
                    </div>
                </div>
            </div>

            <script>
                if (typeof myUserId === 'undefined') {
                    var myUserId = <?= json_encode($currentUserId) ?>;
                }
            </script>
            <script src="/includes/js/messages.js"></script>
            <script>
                // The other_user_id is needed for report/block — store it on the instance.
                const inboxOtherUserId = <?= json_encode((int)$selectedMatch['other_user_id']) ?>;

                const inboxMs = RoamanceMessaging.init({
                    myUserId        : myUserId,
                    mode            : 'inbox',
                    initialMatchId  : <?= json_encode((int)$selectedMatchId) ?>,
                    otherUserId     : <?= json_encode((int)$selectedMatch['other_user_id']) ?>,   // ← ADD
                    otherInitials   : <?= json_encode(strtoupper(substr($selectedMatch['first_name'],0,1).substr($selectedMatch['last_name'],0,1))) ?>,  // ← ADD
                    messagesEl      : '#inbox-messages',
                    inputEl         : '#inbox-msg-input',
                    sendBtnEl       : '#inbox-send-btn',
                    actionsEl       : '#inbox-actions',
                    actionsToggleEl : '#inbox-actions-toggle',
                    actionsMenuEl   : '#inbox-actions-menu',
                    reportBtnEl     : '#inbox-report-btn',
                    blockBtnEl      : '#inbox-block-btn',
                    reportOverlayEl : '#inbox-report-modal-overlay',
                    reportReasonEl  : '#inbox-report-reason',
                    reportErrorEl   : '#inbox-report-modal-error',
                    reportSubmitEl  : '#inbox-report-submit-btn',
                    reportCancelEl  : '#inbox-report-cancel-btn',
                    onBlocked() {
                        window.location.href = '/pages/inbox.php';
                    },
                });

                // Scroll to bottom of pre-rendered messages on first load
                document.addEventListener('DOMContentLoaded', () => {
                    const area = document.getElementById('inbox-messages');
                    if (area) area.scrollTop = area.scrollHeight;
                });
            </script>

        <?php else: ?>
            <div class="messages-area" style="align-items:center;justify-content:center;">
                <p style="color:#666;">Select a conversation to start messaging</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- ── Connections panel ── -->
    <div class="connections-panel">

        <div class="conn-box">
            <h3 class="conn-box-title">💓 Matches</h3>
            <div class="conn-list">
                <?php if (empty($matches)): ?>
                    <p class="conn-empty">No matches yet</p>
                <?php else: ?>
                    <?php foreach ($matches as $m): ?>
                        <a href="?match_id=<?= $m['match_id'] ?>" class="conn-row <?= $m['match_id'] == $selectedMatchId ? 'active' : '' ?>">
                            <?php if ($m['profile_picture']): ?>
                                <img src="<?= htmlspecialchars($m['profile_picture']) ?>" class="conn-avatar" alt="">
                            <?php else: ?>
                                <div class="conn-avatar"><?= strtoupper(substr($m['first_name'],0,1).substr($m['last_name'],0,1)) ?></div>
                            <?php endif; ?>
                            <span class="conn-name"><?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="conn-box">
            <h3 class="conn-box-title">⭐ Liked You</h3>
            <div class="conn-list">
                <?php if (empty($incomingLikes)): ?>
                    <p class="conn-empty">No likes yet</p>
                <?php else: ?>
                    <?php foreach ($incomingLikes as $l): ?>
                        <a href="/pages/discovery_feed.php" class="conn-row">
                            <?php if ($l['profile_picture']): ?>
                                <img src="<?= htmlspecialchars($l['profile_picture']) ?>" class="conn-avatar" alt="">
                            <?php else: ?>
                                <div class="conn-avatar"><?= strtoupper(substr($l['first_name'],0,1).substr($l['last_name'],0,1)) ?></div>
                            <?php endif; ?>
                            <span class="conn-name"><?= htmlspecialchars($l['first_name'].' '.$l['last_name']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div><!-- /.inbox-wrap -->
</div><!-- /.inbox-page -->