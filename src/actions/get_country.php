<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

header('Content-Type: application/json');

try{
    $data = getDestinations($pdo); // your function
    echo json_encode([
        "data" => $data
    ]);
} catch (Throwable $e) {
    echo json_encode([
        "data" => []
    ]);
}