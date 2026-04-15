<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');

if (!$email) {
    echo json_encode(["status" => "empty"]);
    exit;
}

$exists = getUserByEmail($email);

echo json_encode([
    "exists" => $exists
]);