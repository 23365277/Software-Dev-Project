<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/php/functions.php";

header('Content-Type: application/json');

$userId = $_SESSION["user_id"];
$selectedCountry = isset($_GET['trip_country']) ? normalizeLocation($_GET['trip_country']) : null;
$currentDisplayedUser = isset($_GET['displayed_user']) ? $_GET['displayed_user'] : null;
$user = getNextPassport($pdo, $userId, $selectedCountry, $currentDisplayedUser);
echo json_encode($user);
