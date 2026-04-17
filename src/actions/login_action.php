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

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password are required']);
    exit;
}

$result = verifyLogin($email, $password);

if ($result === false) {
    http_response_code(401);
    echo json_encode(['error' => 'Incorrect email or password']);
    exit;
}

if (is_array($result)) {
    if ($result['error'] === 'banned') {
        echo json_encode(['error' => 'Your account has been permanently banned.']);
    } elseif ($result['error'] === 'suspended') {
        $msg = 'Your account has been suspended.';
        if (!empty($result['reason']))   $msg .= ' Reason: ' . $result['reason'] . '.';
        if (!empty($result['duration'])) $msg .= ' Duration: ' . formatSuspensionDuration($result['duration']) . '.';
        echo json_encode(['error' => $msg]);
    }
    exit;
}

$user_id = $result;

$_SESSION['email'] = $email;

$profile   = getProfileInfoById($user_id);
$firstName = $profile['first_name'] ?? '';
if ($firstName) {
    setcookie('user_name', $firstName, time() + (30 * 24 * 60 * 60), '/', '', true, false);
}

if (!empty($_POST['remember_me'])) {
    $token = setRememberToken($user_id);
    setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
}

echo json_encode(['success' => true]);
