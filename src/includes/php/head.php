<?php
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
        if (!isset($_COOKIE['user_name'])) {
            $profile = getProfileInfoById($user['id']);
            $firstName = $profile['first_name'] ?? '';
            if ($firstName) {
                setcookie('user_name', $firstName, time() + (30 * 24 * 60 * 60), '/', '', true, false);
            }
        }
    } else {
        setcookie('remember_me', '', time() - 1, '/', '', true, true);
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

    <link rel="icon" type="image/x-icon" href="/assets/images/favicon_dark.ico" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon_light.ico" media="(prefers-color-scheme: light)">

    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">



	<!-- For Page Specific CSS-->
	<?php if (isset($pageCSS)): ?>
    <link rel="stylesheet" href="<?php echo $pageCSS; ?>">

    <?php endif; ?>
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/header.php"; ?>
