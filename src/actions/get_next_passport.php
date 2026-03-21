<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

header('Content-Type: application/json');

$user = getNextPassport($pdo);
echo json_encode($user);
