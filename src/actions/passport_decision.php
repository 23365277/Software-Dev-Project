<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$senderId   = (int) $_SESSION['user_id'];
$receiverId = (int) ($_POST['receiver_id'] ?? 0);
$action     = $_POST['action'] ?? '';

if (!$receiverId || $receiverId === $senderId) {
    echo json_encode(['success' => false, 'error' => 'Invalid receiver']);
    exit;
}

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

    if ($action === 'unlike') {
        $stmt = $pdo->prepare("DELETE FROM likes WHERE sender_id = :sender_id AND receiver_id = :receiver_id");
        $stmt->execute([':sender_id' => $senderId, ':receiver_id' => $receiverId]);

        $u1 = min($senderId, $receiverId);
        $u2 = max($senderId, $receiverId);
        $pdo->prepare("DELETE FROM matches WHERE user1_id = :u1 AND user2_id = :u2")
            ->execute([':u1' => $u1, ':u2' => $u2]);

        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'undislike') {
        $stmt = $pdo->prepare("DELETE FROM dislikes WHERE sender_id = :sender_id AND receiver_id = :receiver_id");
        $stmt->execute([':sender_id' => $senderId, ':receiver_id' => $receiverId]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === "dislike") {

        $stmt = $pdo->prepare("
            INSERT INTO dislikes (sender_id, receiver_id, cooldown_until)
            VALUES (:sender_id, :receiver_id, DATE_ADD(NOW(), INTERVAL 24 HOUR))
            ON DUPLICATE KEY UPDATE cooldown_until = DATE_ADD(NOW(), INTERVAL 24 HOUR)
        ");
        $stmt->execute([':sender_id' => $senderId, ':receiver_id' => $receiverId]);

        $stmt = $pdo->prepare("DELETE FROM likes WHERE sender_id = :sender_id AND receiver_id = :receiver_id");
        $stmt->execute([':sender_id' => $senderId, ':receiver_id' => $receiverId]);

        echo json_encode(['success' => true]);
        exit;
    }
    echo json_encode(['success' => false, 'error' => 'Unknown action']);
} catch (PDOException $e) {
    echo json_encode(['success' => false]);
}