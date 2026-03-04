<?php
session_start();
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

// Check if user is logged in
$sender_id = $_SESSION['user_id'] ?? null;
if (!$sender_id) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

// Get POST data
$message = trim($_POST['message'] ?? '');
$receiver_id = $_POST['receiver_id'] ?? null;

// Validate input
if (!$message || !$receiver_id) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing data']);
    exit;
}

// Try to send the message
try {
    sendMessage($sender_id, $receiver_id, $message);
    echo json_encode(['success' => true, 'message' => 'Message sent']);
} catch (Exception $e) {
    http_response_code(500); // Server error
    echo json_encode(['error' => 'Error sending message']);
}