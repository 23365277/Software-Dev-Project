<?php
/**
 * get_message.php
 *
 * GET params (one required):
 *   ?match_id=<id>     — preferred, match-scoped, respects soft-delete
 *   ?other_user=<id>   — fallback user-to-user (chatbox without a match yet)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

$loggedInUser = $_SESSION['user_id'] ?? null;
if (!$loggedInUser) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$match_id   = $_GET['match_id']   ?? null;
$other_user = $_GET['other_user'] ?? null;

if (!$match_id && !$other_user) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing match_id or other_user']);
    exit;
}

try {
    global $pdo;

    if ($match_id) {
        $stmt = $pdo->prepare("
            SELECT sender_id, receiver_id, message, sent_at, image_url
            FROM messages
            WHERE match_id = ?
              AND (
                    (sender_id   = ? AND deleted_sender   = 0) OR
                    (receiver_id = ? AND deleted_receiver = 0)
                  )
            ORDER BY sent_at ASC
        ");
        $stmt->execute([$match_id, $loggedInUser, $loggedInUser]);
    } else {
        $stmt = $pdo->prepare("
            SELECT id, sender_id, receiver_id, message, sent_at, image_url
            FROM messages
            WHERE (sender_id = :me1 AND receiver_id = :them1)
               OR (sender_id = :them2 AND receiver_id = :me2)
            ORDER BY sent_at ASC
        ");
        $stmt->execute([
            ':me1'   => $loggedInUser,
            ':them1' => $other_user,
            ':them2' => $other_user,
            ':me2'   => $loggedInUser,
        ]);
    }

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]); // remove before going live
}
