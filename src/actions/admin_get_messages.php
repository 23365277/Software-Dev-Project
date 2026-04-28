<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'USER') !== 'ADMIN') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$user1 = (int)($_GET['user1'] ?? 0);
$user2 = (int)($_GET['user2'] ?? 0);

if (!$user1 || !$user2) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing user IDs']);
    exit;
}

// Find the match between the two users
$matchStmt = $pdo->prepare("
    SELECT match_id FROM matches
    WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)
    LIMIT 1
");
$matchStmt->execute([$user1, $user2, $user2, $user1]);
$match = $matchStmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    echo json_encode(['messages' => [], 'no_match' => true]);
    exit;
}

// Fetch messages
$msgStmt = $pdo->prepare("
    SELECT m.sender_id, m.message, m.sent_at, m.image_url,
           p.first_name, p.last_name
    FROM messages m
    JOIN profiles p ON p.user_id = m.sender_id
    WHERE m.match_id = ?
    ORDER BY m.sent_at ASC
");
$msgStmt->execute([$match['match_id']]);
$messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['messages' => $messages]);
