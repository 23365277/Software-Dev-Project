<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$contacter_id = $_SESSION['user_id'] ?? null;
if (!$contacter_id) {
    http_response_code(401);
    echo json_encode(['error' => 'You must be logged in to contact support']);
    exit;
}

$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$subject || !$message) {
    http_response_code(400);
    echo json_encode(['error' => 'Subject and message are required']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO contact_admin (contacter_id, subject, message) VALUES (?, ?, ?)");
    $stmt->execute([$contacter_id, $subject, $message]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message, please try again']);
}
