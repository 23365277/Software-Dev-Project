<?php
require_once __DIR__ . '/functions.php';
session_start();

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
        SELECT id, sender_id, receiver_id, message, sent_at 
        FROM messages 
        WHERE sender_id = :uid OR receiver_id = :uid
        ORDER BY sent_at ASC
    ");
    $stmt->execute([':uid' => $loggedInUser]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
