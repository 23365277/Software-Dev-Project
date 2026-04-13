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

$reporter_id = $_SESSION['user_id'] ?? null;
if (!$reporter_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$reported_id = intval($_POST['reported_id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');

if (!$reported_id || !$reason) {
    http_response_code(400);
    echo json_encode(['error' => 'Please provide a reason for the report.']);
    exit;
}

$result = reportUser($reported_id, $reason);
echo json_encode($result);
