<?php

require_once __DIR__ . "/../config/database.php";

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT email, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

function registerNewUser($email, $password, $first_name, $last_name, $date_of_birth) {
    global $pdo;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt1 = $pdo->prepare("
        INSERT INTO users (email, password_hash, account_status, role, created_at)
        VALUES (:email, :password_hash, 'ACTIVE', 'USER', NOW())
    ");
    $stmt1->execute([
        ':email' => $email,
        ':password_hash' => $hashedPassword
    ]);

    $userId = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare("
	INSERT INTO profiles (user_id, first_name, last_name, date_of_birth, created_at) 
	VALUES
	(:user_id, :first_name, :last_name, :date_of_birth, NOW())"
    );
    
    $stmt2->execute([
        ':user_id' => $userId,
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':date_of_birth' => $date_of_birth
    ]);

    return $userId;
}
function verifyLogin($email, $password) {
    $user = getUserByEmail($email);
    if ($user && password_verify($password, $user['password_hash'])){
        return $user['email'];
    }
    return false;
}
