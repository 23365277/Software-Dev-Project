<?php
require_once __DIR__ . '/functions.php';
session_start();
header('Content-Type: application/json');

$loggedInUser = $_SESSION['user_id'] ?? null;
$otheruser = $_GET['other_user'] ?? null;

if (!$loggedInUser || !$otheruser) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing user info']);
    exit;
}

try {
    global $pdo;

    // FIX: PDO does not allow the same named parameter (e.g. :me, :them) to appear
    // more than once in a query by default. Use distinct param names (:me1/:them1 etc.)
    // OR enable emulate prepares. Using distinct names here for broad compatibility.
    $stmt = $pdo->prepare("
        SELECT id, sender_id, receiver_id, message, sent_at 
        FROM messages 
        WHERE 
            (sender_id = :me1 AND receiver_id = :them1)
            OR
            (sender_id = :them2 AND receiver_id = :me2)
        ORDER BY sent_at ASC
    ");

    $stmt->execute([
        ':me1'   => $loggedInUser,
        ':them1' => $otheruser,
        ':them2' => $otheruser,
        ':me2'   => $loggedInUser,
    ]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>