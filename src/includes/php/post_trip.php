<?php
ini_set('display_errors', '0');
ob_start();
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";
ob_end_clean();

header('Content-Type: application/json');

try {
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

    $destination = trim($_POST['destination'] ?? '');
    $start_date  = $_POST['start_date'] ?? '';
    $end_date    = $_POST['end_date'] ?? '';
    $activity    = trim($_POST['activity'] ?? '');

    if (!$destination || !$start_date || !$end_date || !$activity) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields.']);
        exit;
    }

    $result = postTrip($destination, $start_date, $end_date, $activity);
    echo json_encode($result);
} catch (Throwable $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;