<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$block_id = intval($_POST['block_id'] ?? 0);
if (!$block_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing user to block.']);
    exit;
}

$result = blockUser($block_id);
echo json_encode($result);
