<?php
/**
 * get_contacts.php
 * Sources contacts from the matches table.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';
// $pdo is now available via global — functions.php already requires database.php

header('Content-Type: application/json');

$loggedInUser = $_SESSION['user_id'] ?? null;
if (!$loggedInUser) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

try {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT
            m.match_id,
            CASE WHEN m.user1_id = :uid  THEN m.user2_id  ELSE m.user1_id  END AS id,
            CONCAT(p.first_name, ' ', p.last_name) AS name,
            p.profile_picture,
            (
                SELECT COUNT(*)
                FROM   messages msg
                WHERE  msg.match_id    = m.match_id
                AND    msg.receiver_id = :uid2
                AND    msg.seen        = 0
            ) AS unread_count,
            (
                SELECT MAX(msg2.sent_at)
                FROM   messages msg2
                WHERE  msg2.match_id = m.match_id
            ) AS last_message_at
        FROM  matches m
        JOIN  profiles p
              ON p.user_id = CASE WHEN m.user1_id = :uid3 THEN m.user2_id ELSE m.user1_id END
        WHERE (m.user1_id = :uid4 OR m.user2_id = :uid5)
          AND NOT EXISTS (
                SELECT 1 FROM blocks b
                WHERE  (b.blocker_id = :uid6  AND b.blocked_id = p.user_id)
                    OR (b.blocker_id = p.user_id AND b.blocked_id = :uid7)
          )
        ORDER BY last_message_at DESC, m.matched_at DESC
    ");

    $stmt->execute([
        ':uid'  => $loggedInUser,
        ':uid2' => $loggedInUser,
        ':uid3' => $loggedInUser,
        ':uid4' => $loggedInUser,
        ':uid5' => $loggedInUser,
        ':uid6' => $loggedInUser,
        ':uid7' => $loggedInUser,
    ]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]); // remove before going live
}