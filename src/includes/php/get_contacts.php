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
        WHERE (m.sender_id = :uid OR m.receiver_id = :uid)
        AND u.id != :uid
    ");

    $stmt->execute([':uid' => $loggedInUser]);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($contacts);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}