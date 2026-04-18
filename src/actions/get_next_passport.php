<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/php/functions.php";

header('Content-Type: application/json');

$userId = $_SESSION["user_id"];
$selectedCountry = isset($_GET['trip_country']) ? normalizeLocation($_GET['trip_country']) : null;
$user = getNextPassport($pdo, $userId, $selectedCountry);
echo json_encode($user);
