<?php

require_once __DIR__ . "/../../config/database.php";

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, email, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function registerNewUser($email, $password, $first_name, $last_name, $date_of_birth, $gender, $Pgender,
						 $age, $looking_for, $interest1, $interest2, $interest3, $interest4, $interest5) {
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

	profile($userId, $first_name, $last_name, $date_of_birth, $gender, $looking_for, $country, $city, $height_cm, $bio);
	preferences($userId, $Pgender, $age);
	interests($userId, $interest1, $interest2, $interest3, $interest4, $interest5);

    return $userId;
}

function preferences($userId, $Pgender, $age){
	global $pdo;

	$stmt1 = $pdo -> prepare("
		INSERT INTO preferences (id, gender, age)
		VALUES
		(:user_id, :gender, :age)
		");
	
	$stmt1 -> execute([
		':user_id' => $userId,
		':gender' => $Pgender,
		':age' => $age
	]);

}

function profile($userId, $first_name, $last_name, $date_of_birth, $gender, $looking_for, $country, $city, $height_cm, $bio){
	global $pdo;

	$stmt2 = $pdo->prepare("
	INSERT INTO profiles (user_id, first_name, last_name, date_of_birth, gender, bio, height_cm, city, country, looking_for, created_at)
	VALUES
	(:user_id, :first_name, :last_name, :date_of_birth, :gender, :bio, :height_cm, :city, :country, :looking_for, NOW())"
    );
    
    $stmt2->execute([
        ':user_id' => $userId,
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':date_of_birth' => $date_of_birth,
		':gender' => $gender,
		':bio' => $bio,
		':height_cm' => $height_cm,
		':city' => $city,
		':country' => $country,
		':looking_for' => $looking_for
    ]);
}

function interests($userId, $interest1, $interest2, $interest3, $interest4, $interest5){
	global $pdo;

	$interests = [$interest1, $interest2, $interest3, $interest4, $interest5];

	foreach($interests as $interest){

		if(empty($interest)) continue;

		$stmt1 = $pdo -> prepare(
			"SELECT id FROM interests WHERE name = ?"
		);
		$stmt1 -> execute([$interest]);
		$interest_id = $stmt1 -> fetchColumn();

		if(!$interest_id){

			$stmt2 = $pdo -> prepare("
				INSERT INTO interests(name)
				VALUES
				(:name)
			");

			$stmt2 -> execute([
				':name' => $interest
			]);

			$interest_id = $pdo -> lastInsertId();
		}

		$stmt3 = $pdo -> prepare("
			INSERT INTO user_interests(user_id, interest_id)
			VALUES
			(:user_id, :interest_id)
		");
		$stmt3 -> execute([
			':user_id' => $userId,
			':interest_id' => $interest_id
		]);
	}
}

function verifyLogin($email, $password) {
    $user = getUserByEmail($email);
    if ($user && password_verify($password, $user['password_hash'])){
	    $_SESSION["user_id"] = $user["id"];
	    $_SESSION["user_email"] = $user["email"];
        return true;
    }
    return false;
}

function sendMessage($sender_id, $receiver_id, $message){
	global $pdo;

	if(empty(trim($message))) {
		return ['success' => false, 'error' => 'Message is empty'];
	}

	try{
		$stmnt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)");
		$stmnt->execute([
			':sender_id' => $sender_id,
			':receiver_id' => $receiver_id,
			':message' => $message
		]);

		return ['success' => true];
	} catch (PDOException $e) {
		return ['success' => false, 'error' => $e->getMessage()];
	}
}

function blockUser($user_id) {
	global $pdo;
	
	$loggedInUser = $_SESSION['user_id'];

	if($user_id == $loggedInUser) {
		return ['success' => false, 'error' => "You cannot block yourself."];
	}

	try{
		if($user_id && $loggedInUser) {
			$stmnt = $pdo->prepare(
				"INSERT INTO blocks 
				(blocker_id, blocked_id, blocked_at) 
				SELECT 
				:blocker_id, :blocked_id, NOW()
				WHERE NOT EXISTS (
					SELECT 1 FROM blocks
					WHERE blocker_id = :blocker_id
					AND blocked_id = :blocked_id
				)
			");
			
			$stmnt->execute([
				':blocker_id' => $loggedInUser,
				':blocked_id' => $user_id
			]);
	
			return ['success' => true];
		}

	return ['success' => false, 'error' => 'Invalid user.'];
	} catch (PDOException $e) {
		return ['success' => false, 'error' => $e->getMessage()];
	}
}

function reportUser($reported_id, $reason) {
	global $pdo;

	$reporter_id = $_SESSION['user_id'];

	if ($reporter_id == $reported_id) {
		return ['success' => false, 'error' => "You cannot report yourself."];
	}

	try {	
		if($reporter_id && $reported_id) {
			$stmnt = $pdo->prepare("
				INSERT INTO reports
				(reporter_id, reported_id, reason, created_at)
				SELECT
				:reporter_id, :reported_id, :reason, NOW()
				WHERE NOT EXISTS (
					SELECT 1 FROM reports
					WHERE reporter_id = :reporter_id
					AND reported_id = :reported_id
				)
			");

			$stmnt->execute([
				':reporter_id' => $reporter_id,
				':reported_id' => $reported_id,
				':reason' => $reason
			]);

			return ['success' => true];
		}
		
		return ['success' => false, 'error' => "Invalid User."];
	} catch (PDOException $e) {
		return ['success' => false, 'error' => $e->getMessage()];
	}
}
