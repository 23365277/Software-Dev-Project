<?php

require_once __DIR__ . "/../../config/database.php";

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, email, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function registerNewUser($email, $password, $first_name, $last_name, $date_of_birth, $gender, $Pgender,
						 $age, $looking_for, $country, $city, $height_cm, $bio, $interest1, $interest2, $interest3, $interest4, $interest5) {
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

function getProfileInfo(){
	global $pdo;

	if (!isset($_SESSION["user_id"])) {
		return false;
	}

	$userId = $_SESSION["user_id"];

	$stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
	$stmt->execute([$userId]);

	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPreferenceInfo(){
	global $pdo;

	if (!isset($_SESSION["user_id"])) {
		return false;
	}

	$userId = $_SESSION["user_id"];

	$stmt = $pdo->prepare("SELECT * FROM preferences WHERE id = ?");
	$stmt->execute([$userId]);

	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserInterests() {
    global $pdo;

    if (!isset($_SESSION["user_id"])) {
        return false;
    }

    $userId = $_SESSION["user_id"];

    $stmt = $pdo->prepare("
        SELECT interests.name
        FROM user_interests
        JOIN interests ON user_interests.interest_id = interests.id
        WHERE user_interests.user_id = ?
    ");

    $stmt->execute([$userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

function getNewestUsers($limit = 5) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT id, email, created_at FROM users ORDER BY created_at DESC LIMIT $limit");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRecentReports($limit = 5) {
	global $pdo;
	$stmt = $pdo->prepare("
		SELECT
			r.report_id AS id,
			r.reporter_id,
			r.reported_id AS reported_user_id,
			r.reason,
			r.created_at,
			u.email AS reported_email
		FROM reports r
		JOIN users u ON r.reported_id = u.id
		ORDER BY r.created_at DESC
		LIMIT $limit
	");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getNextPassport(PDO $pdo, $userId) {
	$stmt = $pdo->prepare("SELECT user_id, profile_picture, first_name, last_name, country, date_of_birth, bio 
	FROM profiles p 
	WHERE p.user_id != :userId 
	AND p.user_id NOT IN ( 
		SELECT l.receiver_id 
		FROM likes l 
		WHERE l.sender_id = :userId) 
	AND p.user_id NOT IN ( 
		SELECT b.blocked_id 
		FROM blocks b 
		WHERE b.blocker_id = :userId) 
	ORDER BY RAND() LIMIT 1");
	$stmt->execute(['userId' => $userId]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	$today = new DateTime();
	$user['age'] = $today->diff(new DateTime($user['date_of_birth']))->y;

	$photoStmt = $pdo->prepare("SELECT image_url 
	FROM photos 
	WHERE user_id = :userId
	ORDER BY uploaded_at DESC 
	LIMIT 6");
	$photoStmt->execute(['userId' => $user['user_id']]);
	$user['galleryImages'] = $photoStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

	return $user;
}

function getMatches(PDO $pdo, $userId): array {
	$stmt = $pdo->prepare("SELECT p.user_id, p.first_name, p.last_name, p.country, p.date_of_birth, p.profile_picture, p.bio
		FROM matches m
		JOIN profiles p 
			ON p.user_id = CASE 
				WHEN m.user1_id = :userId THEN m.user2_id 
				ELSE m.user1_id 
			END
		WHERE m.user1_id = :userId OR m.user2_id = :userId");
	$stmt->execute(['userId' => $userId]);
	$today = new DateTime();
	$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($matches as &$profile) {
		$profile['age'] = $today->diff(new DateTime($profile['date_of_birth']))->y;
	}
	return $matches;
}


function getLikes(PDO $pdo, $userId): array {
	$stmt = $pdo->prepare("SELECT p.user_id, p.first_name, p.last_name, p.country, p.date_of_birth, p.profile_picture, p.bio
		FROM likes l
		JOIN profiles p ON p.user_id = l.receiver_id
		WHERE l.sender_id = :userId");
	$stmt->execute(['userId' => $userId]);
	$today = new DateTime();
	$likes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($likes as &$profile) {
		$profile['age'] = $today->diff(new DateTime($profile['date_of_birth']))->y;
	}
	return $likes;
}