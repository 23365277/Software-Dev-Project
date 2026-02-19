<?php

require_once __DIR__ . "/../config/database.php";

function getUserByEmail($email) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
	$stmt->execute(['email' => $email]);
	return $stmt->fetch();
}

function registerNewUser($email, $password){
	global $pdo;
	$hashedpassword = password_hash($password, PASSWORD_DEFAULT);
	$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
	$stmt->execute([
		'email' => $email,
		'password' => $hashedpassword
	]);
}

function verifyLogin($email, $password) {
	global $pdo;
	$user = getUserByEmail($email);
	if ($user && password_verify($password, $user['password'])){
		return $user['id'];
	}
	return false;
}
