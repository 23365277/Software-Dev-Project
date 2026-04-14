<?php

require_once __DIR__ . "/../../config/database.php";

function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, email, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function registerNewUser($email, $password, $first_name, $last_name, $date_of_birth, $gender, $Pgender,
						 $min_age, $max_age, $looking_for, $country, $city, $profile_picture, $height_cm, $bio, $interest1, $interest2, $interest3, $interest4, $interest5) {
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

	profile($userId, $first_name, $last_name, $date_of_birth, $gender, $looking_for, $country, $city, $profile_picture, $height_cm, $bio);
	preferences($userId, $Pgender, $min_age, $max_age);
	interests($userId, $interest1, $interest2, $interest3, $interest4, $interest5);

    return $userId;
}

function preferences($userId, $Pgender, $min_age, $max_age){
	global $pdo;

	$stmt1 = $pdo -> prepare("
		INSERT INTO preferences (id, pref_gender, min_age, max_age)
		VALUES
		(:user_id, :gender, :min_age, :max_age)
		");
	
	$stmt1 -> execute([
		':user_id' => $userId,
		':gender' => $Pgender,
		':min_age' => $min_age,
		':max_age' => $max_age
	]);

}

function profile($userId, $first_name, $last_name, $date_of_birth, $gender, $looking_for, $country, $city, $profile_picture, $height_cm, $bio){
	global $pdo;

	$stmt2 = $pdo->prepare("
	INSERT INTO profiles (user_id, first_name, last_name, date_of_birth, gender, bio, height_cm, city, country, looking_for, profile_picture, created_at)
	VALUES
	(:user_id, :first_name, :last_name, :date_of_birth, :gender, :bio, :height_cm, :city, :country, :looking_for, :profile_picture, NOW())"
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
		':profile_picture' => $profile_picture,
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

function updateFunction($value, $column){
	global $pdo;
	$profileCols = ['bio', 'height_cm', 'city', 'country', 'looking_for','profile_picture'];
	$preferencesCols = ['pref_gender', 'min_age', 'max_age'];

	if (in_array($column, $profileCols)) {
		updateProfile($value, $column);
	}
	
	if (in_array($column, $preferencesCols)) {
		updatePreferences($value, $column);
	}

}

function updatePreferences($value, $column){
	global $pdo;

	if (!isset($_SESSION["user_id"])) {
        return false;
    }

	$userId = $_SESSION["user_id"];

	$stmt = $pdo->prepare("
		UPDATE preferences
		SET $column = :value
		WHERE id = :user_id
	");

	$stmt->execute([
		':value' => $value,
		':user_id' => $userId
	]);
}

function updateProfile($value, $column){
	global $pdo;

	if (!isset($_SESSION["user_id"])) {
        return false;
    }

	$userId = $_SESSION["user_id"];

	$stmt = $pdo->prepare("
		UPDATE profiles
		SET $column = :value
		WHERE user_id = :user_id
	");

	$stmt->execute([
		':value' => $value,
		':user_id' => $userId
	]);
}

function updateInterests(){
	global $pdo;


}

function getAllInterests() {
    global $pdo;

    $stmt = $pdo->query("SELECT id, name FROM interests ORDER BY name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserInterests($userId, $interestIds) {
    global $pdo;

    // if (count($interestIds) > 5) {
    //     throw new Exception("You can only select up to 5 interests.");
    // }

    $pdo->beginTransaction();

    try {
        // Delete old interests
        $stmt = $pdo->prepare("DELETE FROM user_interests WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Insert new ones
        $stmt = $pdo->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)");

        foreach ($interestIds as $interestId) {
            $stmt->execute([$userId, $interestId]);
        }

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function verifyLogin($email, $password) {
    $user = getUserByEmail($email);
    if ($user && password_verify($password, $user['password_hash'])){
        session_regenerate_id(true);
	    $_SESSION["user_id"] = $user["id"];
	    $_SESSION["user_email"] = $user["email"];
        return $user["id"];
    }
    return false;
}

function setRememberToken($userId) {
    global $pdo;
    $token = bin2hex(random_bytes(32));
    $hash  = hash('sha256', $token);
    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
    $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $hash, $expires]);
    return $token;
}

function getUserByRememberToken($token) {
    global $pdo;
    $hash = hash('sha256', $token);
    $stmt = $pdo->prepare("
        SELECT u.id, u.email FROM users u
        JOIN remember_tokens rt ON rt.user_id = u.id
        WHERE rt.token_hash = ? AND rt.expires_at > NOW()
    ");
    $stmt->execute([$hash]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteRememberToken($token) {
    global $pdo;
    $hash = hash('sha256', $token);
    $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE token_hash = ?");
    $stmt->execute([$hash]);
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
					WHERE blocker_id = :blocker_id2
					AND blocked_id = :blocked_id2
				)
			");

			$stmnt->execute([
				':blocker_id'  => $loggedInUser,
				':blocked_id'  => $user_id,
				':blocker_id2' => $loggedInUser,
				':blocked_id2' => $user_id,
			]);

			if ($stmnt->rowCount() === 0) {
				return ['success' => false, 'error' => 'You have already blocked this user.'];
			}

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
					WHERE reporter_id = :reporter_id2
					AND reported_id = :reported_id2
				)
			");

			$stmnt->execute([
				':reporter_id'  => $reporter_id,
				':reported_id'  => $reported_id,
				':reason'       => $reason,
				':reporter_id2' => $reporter_id,
				':reported_id2' => $reported_id,
			]);

			if ($stmnt->rowCount() === 0) {
				return ['success' => false, 'error' => 'You have already reported this user.'];
			}

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
	$stmt = $pdo->query("SELECT id, email, created_at FROM users ORDER BY created_at DESC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBannedUsers() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT 
            u.id,
            u.email,
            u.created_at,
            bu.admin_id
        FROM banned_users bu
        INNER JOIN users u ON u.id = bu.target_id
        ORDER BY u.created_at DESC
    ");
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

function getNewestUsers($limit = 50) {
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
		WHERE r.status = 'PENDING'	
		ORDER BY r.created_at DESC
		LIMIT $limit
	");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getRecentActivity($limit = 15) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT type, ref_id, email, extra, message, created_at FROM (
            (SELECT 'signup' AS type, id AS ref_id, email, NULL AS extra, NULL AS message, created_at FROM users ORDER BY created_at DESC LIMIT :limit)
            UNION ALL
            (SELECT 'report' AS type, r.report_id AS ref_id, u.email, r.reason AS extra, NULL AS message, r.created_at FROM reports r JOIN users u ON r.reported_id = u.id ORDER BY r.created_at DESC LIMIT :limit)
            UNION ALL
            (SELECT 'contact' AS type, c.id AS ref_id, u.email, c.subject AS extra, c.message AS message, c.created_at FROM contact_admin c JOIN users u ON c.contacter_id = u.id ORDER BY c.created_at DESC LIMIT :limit)
        ) combined
        ORDER BY created_at DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getNextPassport(PDO $pdo, $userId, $tripCountry = null) {

	$preferences = getPreferenceInfoById($userId);
	
	if (!$preferences) {
		$preferences = [
			'min_age' => null,
			'max_age' => null,
			'gender' => null
		];
	}

	$stmt = $pdo->prepare("SELECT p.user_id, p.profile_picture, p.first_name, p.last_name, p.country, p.date_of_birth, p.bio, p.gender
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
	AND (:trip_country IS NULL OR EXISTS 
		(SELECT 1
		FROM trips t
		WHERE t.user_id = p.user_id 
		AND t.location = :trip_country
		AND t.start_date >= CURDATE()))
	AND (:preferred_gender IS NULL OR p.gender = :preferred_gender)
	AND (
		(:min_age IS NULL OR TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) >= :min_age)
		AND 
		(:max_age IS NULL OR TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) <= :max_age))
        ORDER BY RAND()
        LIMIT 1
    ");

	$params = [
		':userId' => $userId,
		':trip_country' => $tripCountry,
		':preferred_gender' => $preferences['gender'] ?? null,
		':min_age' => $preferences['min_age'] ?? null,
		':max_age' => $preferences['max_age'] ?? null
	];

    $stmt->execute($params);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return null;
    }

	$today = new DateTime();
	$user['age'] = $today->diff(new DateTime($user['date_of_birth']))->y;

	$photoStmt = $pdo->prepare("SELECT image_url 
	FROM photos 
	WHERE user_id = :userId
	ORDER BY uploaded_at DESC 
	LIMIT 6");
	$photoStmt->execute(['userId' => $user['user_id']]);
	$user['galleryImages'] = $photoStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

	$user['nextTrip'] = getUserTrips($pdo, $user['user_id']);
	$destinations = getUserStamps($pdo, $user['user_id']);
	$user['stamps'] = array_map(function ($destination) {
		return [
			'country' => $destination['location'],
			'icon' => getCountryFlag($destination['location']),
			'date' => $destination['visited_date'],
			'desc' => $destination['description']
		];
	}, $destinations);

	return $user;
}


function getUserTrips(PDO $pdo, $userId) {
	$tripStmt = $pdo->prepare("
	SELECT location, start_date, end_date
	FROM trips
	WHERE user_id = :userId
		AND start_date >= CURDATE()
	ORDER BY start_date ASC
	LIMIT 1");
	$tripStmt->execute(['userId' => $userId]);
	return $tripStmt->fetch(PDO::FETCH_ASSOC) ?: null;
}


function getUserStamps(PDO $pdo, $userId) {
	$destinationStmt = $pdo->prepare("
	SELECT d.location, ud.visited_date, ud.description
	FROM user_destinations ud
	INNER JOIN destinations d ON d.id = ud.destination_id
	WHERE ud.user_id = :userId
	ORDER BY ud.visited_date DESC
	LIMIT 10");

	$destinationStmt->execute(['userId' => $userId]);
	return $destinationStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}


function getCountryFlag(string $country): string {

    $flags = [
        // A
        'Afghanistan' => '🇦🇫',
        'Albania' => '🇦🇱',
        'Algeria' => '🇩🇿',
        'Andorra' => '🇦🇩',
        'Angola' => '🇦🇴',
        'Argentina' => '🇦🇷',
        'Armenia' => '🇦🇲',
        'Australia' => '🇦🇺',
        'Austria' => '🇦🇹',

        // B
        'Bahamas' => '🇧🇸',
        'Bahrain' => '🇧🇭',
        'Bangladesh' => '🇧🇩',
        'Belarus' => '🇧🇾',
        'Belgium' => '🇧🇪',
        'Belize' => '🇧🇿',
        'Benin' => '🇧🇯',
        'Bhutan' => '🇧🇹',
        'Bolivia' => '🇧🇴',
        'Bosnia and Herzegovina' => '🇧🇦',
        'Botswana' => '🇧🇼',
        'Brazil' => '🇧🇷',
        'Brunei' => '🇧🇳',
        'Bulgaria' => '🇧🇬',

        // C
        'Cambodia' => '🇰🇭',
        'Cameroon' => '🇨🇲',
        'Canada' => '🇨🇦',
        'Chile' => '🇨🇱',
        'China' => '🇨🇳',
        'Colombia' => '🇨🇴',
        'Costa Rica' => '🇨🇷',
        'Croatia' => '🇭🇷',
        'Cuba' => '🇨🇺',
        'Cyprus' => '🇨🇾',
        'Czech Republic' => '🇨🇿',

        // D
        'Denmark' => '🇩🇰',
        'Dominican Republic' => '🇩🇴',

        // E
        'Ecuador' => '🇪🇨',
        'Egypt' => '🇪🇬',
		'Eritrea' => '🇪🇷',
		'Estonia' => '🇪🇪',

        // F
        'Finland' => '🇫🇮',
        'France' => '🇫🇷',

        // G
        'Germany' => '🇩🇪',
        'Ghana' => '🇬🇭',
        'Greece' => '🇬🇷',

        // H
        'Hungary' => '🇭🇺',

        // I
        'Iceland' => '🇮🇸',
        'India' => '🇮🇳',
        'Indonesia' => '🇮🇩',
        'Iran' => '🇮🇷',
        'Iraq' => '🇮🇶',
        'Ireland' => '🇮🇪',
        'Israel' => '🇮🇱',
        'Italy' => '🇮🇹',

        // J
        'Japan' => '🇯🇵',
        'Jordan' => '🇯🇴',

        // K
        'Kazakhstan' => '🇰🇿',
        'Kenya' => '🇰🇪',
        'Kuwait' => '🇰🇼',

        // L
        'Latvia' => '🇱🇻',
        'Lebanon' => '🇱🇧',
        'Lithuania' => '🇱🇹',
        'Luxembourg' => '🇱🇺',

        // M
        'Malaysia' => '🇲🇾',
        'Mexico' => '🇲🇽',
        'Morocco' => '🇲🇦',

        // N
        'Netherlands' => '🇳🇱',
        'New Zealand' => '🇳🇿',
        'Nigeria' => '🇳🇬',
        'Norway' => '🇳🇴',

        // P
        'Pakistan' => '🇵🇰',
        'Peru' => '🇵🇪',
        'Philippines' => '🇵🇭',
        'Poland' => '🇵🇱',
        'Portugal' => '🇵🇹',

        // Q
        'Qatar' => '🇶🇦',

        // R
        'Romania' => '🇷🇴',
        'Russia' => '🇷🇺',

        // S
        'Saudi Arabia' => '🇸🇦',
        'Serbia' => '🇷🇸',
        'Singapore' => '🇸🇬',
        'Slovakia' => '🇸🇰',
        'Slovenia' => '🇸🇮',
        'South Africa' => '🇿🇦',
        'South Korea' => '🇰🇷',
        'Spain' => '🇪🇸',
        'Sweden' => '🇸🇪',
        'Switzerland' => '🇨🇭',

        // T
        'Thailand' => '🇹🇭',
        'Turkey' => '🇹🇷',

        // U
        'Ukraine' => '🇺🇦',
        'United Arab Emirates' => '🇦🇪',
        'United Kingdom' => '🇬🇧',
        'United States' => '🇺🇸',

        // V
        'Vietnam' => '🇻🇳',

        // Z
        'Zambia' => '🇿🇲',
        'Zimbabwe' => '🇿🇼'
    ];

    return $flags[$country] ?? '🌍';
}


function getMatches(PDO $pdo, $userId): array {
	$stmt = $pdo->prepare("SELECT p.user_id, p.first_name, p.last_name, p.country, p.date_of_birth, p.profile_picture, p.bio, m.matched_at
		FROM matches m
		JOIN profiles p 
			ON p.user_id = CASE 
				WHEN m.user1_id = :userId THEN m.user2_id 
				ELSE m.user1_id 
			END
		WHERE m.user1_id = :userId OR m.user2_id = :userId
		ORDER BY m.matched_at DESC");
	$stmt->execute(['userId' => $userId]);
	$today = new DateTime();
	$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($matches as &$profile) {
		$profile['age'] = $today->diff(new DateTime($profile['date_of_birth']))->y;
	}
	return $matches;
}


function getLikes(PDO $pdo, $userId): array {
	$stmt = $pdo->prepare("SELECT p.user_id, p.first_name, p.last_name, p.country, p.date_of_birth, p.profile_picture, p.bio, l.created_at
		FROM likes l
		JOIN profiles p ON p.user_id = l.receiver_id
		WHERE l.sender_id = :userId
		AND NOT EXISTS (
			SELECT 1
			FROM matches m
			WHERE (m.user1_id = :userId AND m.user2_id = l.receiver_id)
			   OR (m.user2_id = :userId AND m.user1_id = l.receiver_id)
		ORDER BY l.created_at DESC)"
	);
	$stmt->execute(['userId' => $userId]);
	$today = new DateTime();
	$likes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($likes as &$profile) {
		$profile['age'] = $today->diff(new DateTime($profile['date_of_birth']))->y;
	}
	return $likes;
}

function banUser($targetId) {
    global $pdo;
    $adminId = $_SESSION['user_id'];

    if ($targetId == $adminId) {
        return ['success' => false, 'error' => 'You cannot ban yourself.'];
    }

    try {
        $stmt = $pdo->prepare("UPDATE users SET account_status = 'BANNED' WHERE id = :id");
        $stmt->execute([':id' => $targetId]);

        $stmt = $pdo->prepare("
            INSERT INTO banned_users (target_id, admin_id)
            SELECT :target_id, :admin_id
            WHERE NOT EXISTS (
                SELECT 1 FROM banned_users WHERE target_id = :target_id
            )
        ");
        $stmt->execute([':target_id' => $targetId, ':admin_id' => $adminId]);

		$stmnt = $pdo->prepare("UPDATE reports SET status = 'RESOLVED' WHERE reported_id = :id");
		$stmnt->execute([':id' => $targetId]);


        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function suspendUser($targetId, $days) {
    global $pdo;
    $adminId = $_SESSION['user_id'];

    if ($targetId == $adminId) {
        return ['success' => false, 'error' => 'You cannot suspend yourself.'];
    }

    try {
        $stmt = $pdo->prepare("UPDATE users SET account_status = 'SUSPENDED' WHERE id = :id");
        $stmt->execute([':id' => $targetId]);
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function unbanUser($targetId) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("UPDATE users SET account_status = 'ACTIVE' WHERE id = :id");
        $stmt->execute([':id' => $targetId]);

        $stmt = $pdo->prepare("DELETE FROM banned_users WHERE target_id = :id");
        $stmt->execute([':id' => $targetId]);

        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function resolveReport($reportId) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("UPDATE reports SET status = 'RESOLVED' WHERE report_id = :id");
        $stmt->execute([':id' => $reportId]);
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function getProfileInfoById($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPreferenceInfoById($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM preferences WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserInterestsById($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT interests.name
        FROM user_interests
        JOIN interests ON user_interests.interest_id = interests.id
        WHERE user_interests.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function normalizeLocation($location) {
	$map = [
		'England' => 'United Kingdom',
		'Scotland' => 'United Kingdom',
		'Wales' => 'United Kingdom',
		'Northern Ireland' => 'United Kingdom',
		'Great Britain' => 'United Kingdom',
		'Britain' => 'United Kingdom',
		'United States of America' => 'United States',
		'USA' => 'United States',
		'US' => 'United States',
		'America' => 'United States',
		'Czechia' => 'Czech Republic',
	];
	return $map[$location] ?? $location;
}

function addToVisited($pdo, $userId, $location, $visited_date, $description) {
	$pdo->prepare("INSERT IGNORE INTO destinations (location) VALUES (?)")->execute([$location]);
	$stmt = $pdo->prepare("SELECT id FROM destinations WHERE location = ?");
	$stmt->execute([$location]);
	$dest_id = $stmt->fetchColumn();

	$pdo->prepare("
		INSERT IGNORE INTO user_destinations (user_id, destination_id, visited_date, description)
		VALUES (?, ?, ?, ?)
	")->execute([$userId, $dest_id, $visited_date, $description]);
}

function postTrip($destination, $start_date, $end_date, $description){
	global $pdo;

	if (!isset($_SESSION["user_id"])) {
		return ['success' => false, 'error' => 'User not logged in'];
	}

	$destination = normalizeLocation($destination);
	$userId = $_SESSION["user_id"];

	if ($end_date < date('Y-m-d')) {
		addToVisited($pdo, $userId, $destination, $end_date, $description);
		return ['success' => true];
	}

	$pdo->prepare("
		INSERT INTO trips (location, description, start_date, end_date, user_id)
		VALUES (?, ?, ?, ?, ?)
	")->execute([$destination, $description, $start_date, $end_date, $userId]);
	return ['success' => true];
}