<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        exit;
    }
    $userId = (int) $_SESSION['user_id'];
    $page   = isset($_GET['page']) ? (int)$_GET['page'] : 0;

    $filters = [
        'gender'     => $_GET['gender']     ?? null,
        'min_age'    => $_GET['min_age']    ?? null,
        'max_age'    => $_GET['max_age']    ?? null,
        'country'    => $_GET['country']    ?? null,
        'looking_for'=> $_GET['looking_for']?? null,
        'trip_dest'  => $_GET['trip_dest']  ?? null,
    ];

    $profiles = getAllUnseen($userId, 20, $page * 20, $filters);

    if (!empty($profiles)) {
        $userIds = array_column($profiles, 'user_id');
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        $photoStmt = $pdo->prepare("SELECT user_id, image_url FROM photos WHERE user_id IN ($placeholders) ORDER BY uploaded_at DESC");
        $photoStmt->execute($userIds);
        $photos = $photoStmt->fetchAll(PDO::FETCH_ASSOC);

        $photosByUser = [];
        foreach ($photos as $photo) {
            $photosByUser[$photo['user_id']][] = $photo['image_url'];
        }

        foreach ($profiles as &$profile) {
            $profile['gallery_images'] = array_slice($photosByUser[$profile['user_id']] ?? [], 0, 6);
        }
    }

    echo json_encode($profiles);
?>
