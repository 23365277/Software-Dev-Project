<?php
session_start();
require_once __DIR__ . '/functions.php';
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
        SELECT DISTINCT u.id, u.email
        FROM messages m
        JOIN users u ON (u.id = m.sender_id OR u.id = m.receiver_id)
        WHERE (m.sender_id = :uid OR m.receiver_id = :uid2)
        AND u.id != :uid3
        AND NOT EXISTS (
            SELECT 1 FROM blocks
            WHERE (blocker_id = :uid4 AND blocked_id = u.id)
               OR (blocker_id = u.id AND blocked_id = :uid5)
        )
    ");

    $stmt->execute([
        ':uid'  => $loggedInUser,
        ':uid2' => $loggedInUser,
        ':uid3' => $loggedInUser,
        ':uid4' => $loggedInUser,
        ':uid5' => $loggedInUser,
    ]);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($contacts);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}