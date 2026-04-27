<?php
/**
 * send_message.php
 *
 * POST params:
 *   match_id  + message   — inbox / home / chatbox (preferred)
 *   receiver_id + message — fallback if no match_id
 *
 * NOTE: functions.php's sendMessage() does NOT accept match_id,
 * so we insert directly here to ensure match_id is stored on the row.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

$sender_id = $_SESSION['user_id'] ?? null;
if (!$sender_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$message     = trim($_POST['message']     ?? '');
$receiver_id = $_POST['receiver_id']      ?? null;
$match_id    = $_POST['match_id']         ?? null;

if (!$message && !isset($_FILES['attachment'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing message']);
    exit;
}

if (preg_match('/(\+?\d[\s\-.()\[\]]{0,3}){7,}/', $message)) {
    http_response_code(422);
    echo json_encode(['error' => 'Phone numbers are not allowed in messages']);
    exit;
}

$image_url = null;

if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $detectedMime = mime_content_type($_FILES['attachment']['tmp_name']);

    if (!in_array($detectedMime, $allowedMimes, true)) {
        http_response_code(415);
        echo json_encode(['error' => 'Unsupported image format. Please use JPEG, PNG, GIF, or WebP.']);
        exit;
    }

	$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/attachments/';
	if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
	$fileName   = str_replace('.', '_', uniqid('msg_', true)) . '_' . str_replace(' ', '_', basename($_FILES['attachment']['name']));
	$targetFile = $target_dir . $fileName;

	$tmpPath = $_FILES['attachment']['tmp_name'];
	if (is_uploaded_file($tmpPath) && move_uploaded_file($tmpPath, $targetFile)) {
		$image_url = '/assets/images/attachments/' . $fileName;
	}
}

try {
    global $pdo;

    // If we have a match_id but no receiver_id, derive receiver from the match
    if ($match_id && !$receiver_id) {
        $stmt = $pdo->prepare("SELECT user1_id, user2_id FROM matches WHERE match_id = ?");
        $stmt->execute([$match_id]);
        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$match) {
            http_response_code(404);
            echo json_encode(['error' => 'Match not found']);
            exit;
        }

        if ($match['user1_id'] == $sender_id) {
            $receiver_id = $match['user2_id'];
        } elseif ($match['user2_id'] == $sender_id) {
            $receiver_id = $match['user1_id'];
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Not your match']);
            exit;
        }
    }

    if (!$receiver_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing receiver']);
        exit;
    }

    // Insert directly so we can store match_id on the row.
    // functions.php sendMessage() doesn't support match_id, so we bypass it here.
    $ins = $pdo->prepare("
        INSERT INTO messages (sender_id, receiver_id, message, match_id, sent_at, seen, image_url)
        VALUES (?, ?, ?, ?, NOW(), 0, ?)
    ");
    $ins->execute([$sender_id, $receiver_id, $message, $match_id, $image_url]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
