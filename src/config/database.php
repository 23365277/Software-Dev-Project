<?php
// src/config/database.php

$host = 'db';               // MySQL service name from docker-compose
$db   = 'roamance';         // Database name
$user = 'user';             // MySQL user
$pass = 'userpassword';     // MySQL password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    // Throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Connection failed
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

