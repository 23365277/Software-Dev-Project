<?php
require 'config/database.php';

// Fetch users
$stmt = $pdo->query("SELECT username, email, first_name, last_name, date_of_birth, bio FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'pages/login.php'; 
