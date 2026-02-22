<?php

require_once __DIR__ . "/../config/database.php";

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

function registerNewUser($username, $email, $password, $first_name, $last_name, $date_of_birth, $bio, $profile_picture){
	global $pdo;
	$hashedpassword = password_hash($password, PASSWORD_DEFAULT);
	$stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, date_of_birth, bio, profile_picture)
						   VALUES (:username, :email, :password, :first_name, :last_name, :date_of_birth, :bio, :profile_picture)");
	$stmt->execute([
		'username' => $username,
		'email' => $email,
		'password' => $hashedpassword,
		'first_name' => $first_name,
		'last_name' => $last_name,
		'date_of_birth' => $date_of_birth,
		'bio' => $bio,
		'profile_picture' => $profile_picture
	]);
}

// --- Verify login credentials ---
function verifyLogin($email, $password) {
    $user = getUserByEmail($email);
    if ($user && password_verify($password, $user['password_hash'])){
        return $user['id'];
    }
    return false;
}
