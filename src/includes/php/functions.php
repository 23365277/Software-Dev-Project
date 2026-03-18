<?php

require_once __DIR__ . "/../../config/database.php";

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, email, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function registerNewUser($email, $password, $first_name, $last_name, $date_of_birth, $gender,
						 $age, $looking_for) {
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

	profile($userId, $first_name, $last_name, $date_of_birth);
	preferences($userId, $gender, $age);

    return $userId;
}

function preferences($userId, $gender, $age){
	global $pdo;

	$stmt1 = $pdo -> prepare("
		INSERT INTO preferences (id, gender, age)
		VALUES
		(:user_id, :gender, :age)
		");
	
	$stmt1 -> execute([
		':user_id' => $userId,
		':gender' => $gender,
		':age' => $age
	]);

}

function profile($userId, $first_name, $last_name, $date_of_birth){
	global $pdo;

	$stmt2 = $pdo->prepare("
	INSERT INTO profiles (user_id, first_name, last_name, date_of_birth,  created_at)
	VALUES
	(:user_id, :first_name, :last_name, :date_of_birth, NOW())"
    );
    
    $stmt2->execute([
        ':user_id' => $userId,
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':date_of_birth' => $date_of_birth
    ]);
}

// function interests($userId, $interest1, $interest2, $interest3, $interest4, $interest5){
// 	global $pdo;

// 	$interests = [$interest1, $interest2, $interest3, $interest4, $interest5];

// 	foreach($interests as $interest){



// 		$stmt = $pdo -> prepare("
// 			INSERT INTO interests($interest)
// 			VALUES
// 			(:name)
// 		");
// 	}
// }

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

function getTotalUsers() {
	global $pdo;
	$stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
	return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

function getTotalMatches() {
	global $pdo;
	$stmt = $pdo->query("SELECT COUNT(*) AS total FROM matches");
	return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

function getAllUsers() {
	global $pdo;
	$stmt = $pdo->query("SELECT id, email FROM users");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsersForGraph() {
    global $pdo;
	$days = 30;

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) AS total_before
		FROM users
		WHERE created_at < CURDATE() - INTERVAL $days DAY"
    );
	
	$stmt->execute();
    $totalBefore = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total_before'];

	$stmt = $pdo->prepare(
		"SELECT DATE(created_at) AS day, COUNT(*) AS count
		FROM users
		WHERE created_at >= CURDATE() - INTERVAL $days DAY
		GROUP BY day
		ORDER BY day ASC"
	);

	$stmt->execute();
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map DB results
    $countsByDay = [];
    foreach ($data as $row) {
		// echo "In foreach" . "<br>";
        $countsByDay[$row['day']] = (int) $row['count'];
		// echo "Row['count']: " . (int) $row['count'] . "<br>";
    }

	// echo "After foreach" . "<br>";

    $dates = [];
    $counts = [];

    $runningTotal = $totalBefore;
    $start = new DateTime("-" . ($days - 1) . " days");
    $end = new DateTime();

    while ($start <= $end) {
        $day = $start->format('Y-m-d');
		$dailyCount = $countsByDay[$day] ?? 0;
		$runningTotal += $dailyCount;

		// echo "Counts by day: " . ($countsByDay[$day] ?? 0) . "<br>";
		
		
		// echo "Running Total: $runningTotal" ."<br>";

        $dates[] = $start->format('M d');
        $counts[] = $runningTotal;

        $start->modify('+1 day');
    }

    return [
        'userDates' => $dates,
        'userCounts' => $counts
    ];
}

function getMatchesForGraph() {
	global $pdo;
	$days = 30;

	$stmt = $pdo->prepare(
		"SELECT COUNT(*) AS total_before
		FROM matches
		WHERE matched_at < CURDATE() - INTERVAL $days DAY"
	);
	
	$stmt->execute();
	$totalBefore = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total_before'];

	$stmt = $pdo->prepare(
		"SELECT DATE(matched_at) AS day, COUNT(*) AS count
		FROM matches
		WHERE matched_at >= CURDATE() - INTERVAL $days DAY
		GROUP BY day
		ORDER BY day ASC"
	);

	$stmt->execute();
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Map DB results
	$countsByDay = [];
	foreach ($data as $row) {
		$countsByDay[$row['day']] = (int) $row['count'];
	}

	$dates = [];
	$counts = [];

	$runningTotal = $totalBefore;
	$start = new DateTime("-" . ($days - 1) . " days");
	$end = new DateTime();

	while ($start <= $end) {
		$day = $start->format('Y-m-d');
		$dailyCount = $countsByDay[$day] ?? 0;
		$runningTotal += $dailyCount;

		$dates[] = $start->format('M d');
		$counts[] = $runningTotal;

		$start->modify('+1 day');
	}

	return [
		'matchDates' => $dates,
		'matchCounts' => $counts
	];
}

