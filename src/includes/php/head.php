<?php
ini_set('display_errors', '0');
error_reporting(0);

if(session_status() == PHP_SESSION_NONE){
	session_set_cookie_params([
		'lifetime' => 0,
		'path'     => '/',
		'secure'   => true,
		'httponly' => true,
		'samesite' => 'Strict'
	]);
	session_start();
}

$publicPages = ['login', 'create_account', 'about', 'contact'];
$currentPage = basename($_SERVER['PHP_SELF'] ?? '', '.php');
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['remember_me']) && !in_array($currentPage, $publicPages)) {
    header('Location: /pages/login.php');
    exit();
}

if (isset($_SESSION['user_id'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

    $userStmt = $pdo->prepare("SELECT role, account_status FROM users WHERE id = ?");
    $userStmt->execute([$_SESSION['user_id']]);
    $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user_role'] = $userRow['role'] ?? 'USER';

    $accountStatus = $userRow['account_status'] ?? 'ACTIVE';
    if ($accountStatus === 'BANNED' || $accountStatus === 'SUSPENDED') {
        $redirectQuery = 'blocked=' . strtolower($accountStatus);

        if ($accountStatus === 'SUSPENDED') {
            $suspStmt = $pdo->prepare("SELECT duration FROM suspended_users WHERE target_id = ? AND status = 'SUSPENDED' LIMIT 1");
            $suspStmt->execute([$_SESSION['user_id']]);
            $duration = $suspStmt->fetchColumn();
            if ($duration) {
                $redirectQuery .= '&duration=' . urlencode(formatSuspensionDuration($duration));
            }
        }

        session_unset();
        session_destroy();
        setcookie('remember_me', '', time() - 1, '/', '', true, true);
        $isLoginPage = strpos($_SERVER['PHP_SELF'] ?? '', 'login') !== false;
        if (!$isLoginPage) {
            header('Location: /pages/login.php?' . $redirectQuery);
            exit();
        }
    }
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
    $user = getUserByRememberToken($_COOKIE['remember_me']);
    if ($user) {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $profile = getProfileInfoById($user['id']);
        $firstName = $profile['first_name'] ?? '';
        if ($firstName) {
            setcookie('user_name', $firstName, time() + (30 * 24 * 60 * 60), '/', '', true, false);
        }
    } else {
        setcookie('remember_me', '', time() - 1, '/', '', true, true);
        if (!in_array($currentPage, $publicPages)) {
            header('Location: /pages/login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- src/includes/head.php -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
	?>

    <title><?php echo isset($pageTitle) ? $pageTitle : "Roamance"; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/assets/images/ui/favicon_dark.ico" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/x-icon" href="/assets/images/ui/favicon_light.ico" media="(prefers-color-scheme: light)">

    <link rel="stylesheet" href="/assets/css/header.css?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/css/header.css') ?>">
    <link rel="stylesheet" href="/assets/css/footer.css?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/css/footer.css') ?>">



	<!-- For Page Specific CSS-->
	<?php if (isset($pageCSS)): ?>
        <?php if (is_array($pageCSS) ): ?>
            <?php foreach ($pageCSS as $css): ?>
                <link rel="stylesheet" href="<?php echo $css; ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <link rel="stylesheet" href="<?php echo $pageCSS; ?>">
        <?php endif; ?>
    <?php endif; ?>
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/header.php"; ?>
