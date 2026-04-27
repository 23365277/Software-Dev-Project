<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$userId = (int) $_SESSION['user_id'];

try {
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);

    session_destroy();
    setcookie('remember_me', '', time() - 1, '/', '', true, true);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false]);
}
