<?php
// src/config/database.php

$host = getenv('DB_HOST');      // Fly database hostname
$db   = getenv('DB_NAME');      // Database name
$user = getenv('DB_USER');      // DB user
$pass = getenv('DB_PASS');      // DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}
