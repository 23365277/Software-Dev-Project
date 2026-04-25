<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$userId = (int) $_SESSION['user_id'];
$type   = $_POST['type'] ?? '';

if ($type === 'matching') {
    $prefGender = $_POST['pref_gender'] ?? '';
    $lookingFor = $_POST['looking_for'] ?? '';
    $minAge     = max(18, min(99, (int) ($_POST['min_age'] ?? 18)));
    $maxAge     = max(18, min(99, (int) ($_POST['max_age'] ?? 99)));

    if ($minAge >= $maxAge) $minAge = $maxAge - 1;

    $allowedGenders = ['Male', 'Female', 'other', 'Any'];
    $allowedLooking = ['RELATIONSHIP', 'CASUAL'];

    if (!in_array($prefGender, $allowedGenders) || !in_array($lookingFor, $allowedLooking)) {
        echo json_encode(['success' => false, 'error' => 'Invalid value']);
        exit;
    }

    updateFunction($userId, $prefGender, 'pref_gender');
    updateFunction($userId, $lookingFor, 'looking_for');
    updateUserAgePreference($userId, $minAge, $maxAge);

    echo json_encode(['success' => true]);

} elseif ($type === 'interests') {
    $interestIds = isset($_POST['interests']) ? array_map('intval', (array) $_POST['interests']) : [];
    if (count($interestIds) > 5) {
        echo json_encode(['success' => false, 'error' => 'Maximum 5 interests allowed']);
        exit;
    }
    $result = updateUserInterests($userId, $interestIds);
    echo json_encode(['success' => (bool) $result]);

} elseif ($type === 'field') {
    $column  = $_POST['column'] ?? '';
    $value   = $_POST['value']  ?? '';
    $allowed = ['pref_gender', 'looking_for', 'height_cm'];

    if (!in_array($column, $allowed)) {
        echo json_encode(['success' => false, 'error' => 'Invalid column']);
        exit;
    }

    updateFunction($userId, $value, $column);
    echo json_encode(['success' => true]);

} else {
    echo json_encode(['success' => false, 'error' => 'Unknown type']);
}
