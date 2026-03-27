<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

header('Content-Type: application/json');

$userId = $_SESSION["user_id"];
$user = getNextPassport($pdo, $userId);
echo json_encode($user);
