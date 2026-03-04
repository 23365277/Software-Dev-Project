<?php
require_once __DIR__ . '/functions.php';
session_start();

header('Content-Type: application/json');

$loggedInUser = $_SESSION['user_id'] ?? null;
$otheruser = $_GET['other_user'] ?? null;

if (!$loggedInUser || !$otheruser) {
    http_response_code(400);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

try {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT id, sender_id, receiver_id, message, sent_at 
        FROM messages 
        WHERE 
            (sender_id = :me AND receiver_id = :them)
            OR
            (sender_id = :them AND receiver_id = :me)
        ORDER BY sent_at ASC
    ");

    $stmt->execute([':me' => $loggedInUser, ':them' => $otheruser]);
    

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

    

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
