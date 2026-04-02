<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

header('Content-Type: application/json');

$senderId = $_SESSION['user_id'];
$recieverId = $_POST['receiver_id'];
$action = $_POST['action'];

try {
    if ($action === 'like') {
        $stmt = $pdo->prepare("INSERT IGNORE INTO likes (sender_id, receiver_id) VALUES (:sender_id, :receiver_id)");
        $stmt->execute(['sender_id' => $senderId, 'receiver_id' => $recieverId]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'dislike') {
        echo json_encode([
            'success' => true
        ]);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
    ]);
    exit;
}