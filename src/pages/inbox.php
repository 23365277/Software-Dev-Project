<?php
    $pageTitle = "Roamance - Inbox";
    $pageCSS = "/assets/css/inbox.css";
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';

    $currentUserId = $_SESSION['user_id'];

    // Fetch all matches for the logged-in user, with the other person's profile
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
            -- Latest message preview for sidebar
            (SELECT message FROM messages 
             WHERE match_id = m.match_id 
             ORDER BY sent_at DESC LIMIT 1) AS last_message,
            (SELECT sent_at FROM messages 
             WHERE match_id = m.match_id 
             ORDER BY sent_at DESC LIMIT 1) AS last_message_at,
            -- Unread count for dot indicator
            (SELECT COUNT(*) FROM messages 
             WHERE match_id = m.match_id 
             AND receiver_id = ? AND seen = 0) AS unread_count
        FROM matches m
        JOIN profiles p ON p.user_id = CASE WHEN m.user1_id = ? THEN m.user2_id ELSE m.user1_id END
        WHERE m.user1_id = ? OR m.user2_id = ?
        ORDER BY last_message_at DESC
    ");
    $matchesQuery->execute([$currentUserId, $currentUserId, $currentUserId, $currentUserId, $currentUserId]);
    $matches = $matchesQuery->fetchAll(PDO::FETCH_ASSOC);

    // Get selected match from URL, default to first match
    $selectedMatchId = $_GET['match_id'] ?? ($matches[0]['match_id'] ?? null);

    // Find the selected match's other user for the chat header
    $selectedMatch = null;
    foreach ($matches as $match) {
        if ($match['match_id'] == $selectedMatchId) {
            $selectedMatch = $match;
            break;
        }
    }

    // Fetch messages for the selected match
    $messages = [];
    if ($selectedMatchId) {
        // Mark messages as seen
        $pdo->prepare("
            UPDATE messages SET seen = 1 
            WHERE match_id = ? AND receiver_id = ?
        ")->execute([$selectedMatchId, $currentUserId]);

        $messagesQuery = $pdo->prepare("
            SELECT sender_id, message, sent_at 
            FROM messages 
            WHERE match_id = ?
            AND (
                (sender_id = ? AND deleted_sender = 0) OR
                (receiver_id = ? AND deleted_receiver = 0)
            )
            ORDER BY sent_at ASC
        ");
        $messagesQuery->execute([$selectedMatchId, $currentUserId, $currentUserId]);
        $messages = $messagesQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    // Helper: calculate age from date_of_birth
    function getAge($dob) {
        return (new DateTime($dob))->diff(new DateTime())->y;
    }

    // Helper: format last message timestamp for sidebar
    function formatTime($datetime) {
        if (!$datetime) return '';
        $diff = time() - strtotime($datetime);
        if ($diff < 3600) return round($diff / 60) . 'm';
        if ($diff < 86400) return round($diff / 3600) . 'h';
        if ($diff < 604800) return date('D', strtotime($datetime));
        return date('d M', strtotime($datetime));
    }
?>

<div class="inbox-wrap ms-4">

    <!-- ── Sidebar: list of matched conversations ── -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Messages</h2>
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search conversations...">
        </div>
        <div class="people-list">
            <?php foreach ($matches as $match): ?>
                <a href="?match_id=<?= $match['match_id'] ?>" class="person-row <?= $match['match_id'] == $selectedMatchId ? 'active' : '' ?>">
                    <!-- Profile picture or initials avatar -->
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
                        <!-- Unread dot -->
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

            <!-- Chat header with matched user's info -->
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
            </div>

            <!-- Messages -->
            <div class="messages-area">
                <?php
                $lastDate = null;
                foreach ($messages as $msg):
                    $msgDate = date('Y-m-d', strtotime($msg['sent_at']));
                    $isSent = $msg['sender_id'] == $currentUserId;
                ?>
                    <!-- Date divider when the day changes -->
                    <?php if ($msgDate !== $lastDate): $lastDate = $msgDate; ?>
                        <div class="date-divider">
                            <?= $msgDate === date('Y-m-d') ? 'Today' : date('d M Y', strtotime($msg['sent_at'])) ?>
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

            <!-- Message input -->
            <div class="message-bar">
                <button class="attach-btn">+</button>
                <input class="msg-input" type="text" placeholder="Write a message...">
                <button class="send-btn">&#10148;</button>
            </div>

        <?php else: ?>
            <!-- No match selected -->
            <div class="messages-area" style="align-items:center;justify-content:center;">
                <p style="color:#666;">Select a conversation to start messaging</p>
            </div>
        <?php endif; ?>
    </div>

