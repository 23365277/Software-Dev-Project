<?php
$pdo = new PDO('mysql:host=roamance_db;dbname=roamance', 'user', 'userpassword');

// Assume user IDs 1–30
for ($user_id = 1; $user_id <= 30; $user_id++) {
    $n = rand(2, 5);  // random number of interests
    $stmt = $pdo->query("SELECT id FROM interests ORDER BY RAND() LIMIT $n");
    $interests = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($interests as $interest_id) {
        $pdo->exec("INSERT INTO user_interests (user_id, interest_id) VALUES ($user_id, $interest_id)");
    }
}
?>

