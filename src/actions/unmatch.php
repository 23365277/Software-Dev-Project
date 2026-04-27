<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$userId    = (int) $_SESSION['user_id'];
$matchedId = (int) ($_POST['matched_id'] ?? 0);

if (!$matchedId || $matchedId === $userId) {
    echo json_encode(['success' => false]);
    exit;
}

$u1 = min($userId, $matchedId);
$u2 = max($userId, $matchedId);

$pdo->prepare("DELETE FROM matches WHERE user1_id = ? AND user2_id = ?")->execute([$u1, $u2]);
$pdo->prepare("DELETE FROM likes WHERE sender_id = ? AND receiver_id = ?")->execute([$userId, $matchedId]);
$pdo->prepare("DELETE FROM likes WHERE sender_id = ? AND receiver_id = ?")->execute([$matchedId, $userId]);

echo json_encode(['success' => true]);
