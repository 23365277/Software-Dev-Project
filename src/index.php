<?php
require 'config/database.php';

// Fetch users
$stmt = $pdo->query("SELECT username, email, first_name, last_name, date_of_birth, bio FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <?php include 'includes/header.php'; ?>
<body>
<h1>Users</h1>
<?php foreach ($users as $user): ?>
    <div>
        <h2><?= htmlspecialchars($user['username']) ?></h2>
        <p>Name: <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?></p>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
        <p>DOB: <?= htmlspecialchars($user['date_of_birth']) ?></p>
        <p>Bio: <?= htmlspecialchars($user['bio']) ?></p>
    </div>
    <hr>
<?php endforeach; ?>
<?php include 'includes/footer.php'; ?>

