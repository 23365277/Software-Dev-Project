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
<?php include 'pages/landing-page.php'; ?>
<?php include 'includes/footer.php'; ?>

