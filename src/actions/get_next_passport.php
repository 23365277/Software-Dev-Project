<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/php/functions.php";

header('Content-Type: application/json');

$userId = $_SESSION["user_id"];
$tripCountries = [];
if (!empty($_GET['trip_countries'])) {
    $tripCountries = array_values(array_filter(array_map(
        fn($c) => normalizeLocation(trim(urldecode($c))),
        explode(',', $_GET['trip_countries'])
    )));
} elseif (!empty($_GET['trip_country'])) {
    $tripCountries = [normalizeLocation($_GET['trip_country'])];
}
$excludeUserId = isset($_GET['displayed_user']) ? (int)$_GET['displayed_user'] : null;
$user = getNextPassport($pdo, $userId, $tripCountries ?: null, $excludeUserId);
echo json_encode($user);
