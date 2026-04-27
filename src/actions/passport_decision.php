<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

header('Content-Type: application/json');

$senderId = $_SESSION['user_id'];
$receiverId = $_POST['receiver_id'];
$action = $_POST['action'];

try {
    if ($action === 'like') {
        $checkMatchStmt = $pdo->prepare("SELECT 1 FROM likes WHERE sender_id = :receiver_id AND receiver_id = :sender_id LIMIT 1");
        $checkMatchStmt->execute(['receiver_id' => $receiverId, 'sender_id' => $senderId]);

        $stmt = $pdo->prepare("INSERT IGNORE INTO likes (sender_id, receiver_id) VALUES (:sender_id, :receiver_id)");
        $stmt->execute(['sender_id' => $senderId, 'receiver_id' => $receiverId]);
        
        if ($checkMatchStmt->fetch()) {
            $user1 = min($senderId, $receiverId);
            $user2 = max($senderId, $receiverId);

            $matchStmt = $pdo->prepare("
                INSERT IGNORE INTO matches (user1_id, user2_id)
                VALUES (:user1_id, :user2_id)
            ");
            $matchStmt->execute([
                'user1_id' => $user1,
                'user2_id' => $user2
            ]);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === "dislike") {

        $stmt = $pdo->prepare("
            INSERT INTO dislikes (sender_id, receiver_id, cooldown_until)
            VALUES (:sender_id, :receiver_id, DATE_ADD(NOW(), INTERVAL 24 HOUR))
            ON DUPLICATE KEY UPDATE cooldown_until = DATE_ADD(NOW(), INTERVAL 24 HOUR)
        ");

        $stmt->execute([
            ':sender_id' => $senderId,
            ':receiver_id' => $receiverId,
        ]);

        echo json_encode(['success' => true]);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
    ]);
    exit;
}