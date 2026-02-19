<?php
$pdo = new PDO('mysql:host=roamance_db;dbname=roamance', 'user', 'userpassword');

$stmt = $pdo->query("SELECT id, password FROM users");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $hashed = password_hash($row['password'], PASSWORD_DEFAULT);

    $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->execute([$hashed, $row['id']]);
}

echo "Passwords hashed successfully!";
?>

