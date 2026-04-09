<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

	$error = '';

	if (isset($_SESSION['user_id'])){
		header('Location: /pages/home.php');
	}

	// Auto-login from remember me cookie
	if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
		$user = getUserByRememberToken($_COOKIE['remember_me']);
		if ($user) {
			$_SESSION['user_id']    = $user['id'];
			$_SESSION['user_email'] = $user['email'];
			header('Location: /pages/home.php');
			exit();
		} else {
			setcookie('remember_me', '', time() - 1, '/', '', true, true);
		}
	}

	if (isset($_POST['login'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];

		$user_id = verifyLogin($email, $password);
		if($user_id){
			$_SESSION["email"] = $email;
			$profile = getProfileInfoById($user_id);
			$firstName = $profile['first_name'] ?? '';
			if ($firstName) {
				setcookie('user_name', $firstName, time() + (30 * 24 * 60 * 60), '/', '', true, false);
			}
			if (!empty($_POST['remember_me'])) {
				$token = setRememberToken($user_id);
				setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
			}
			header('Location: /pages/home.php');
			exit();
		} else {
			$error = "Something went wrong, please try again.";
		}
	}

	$pageTitle = "Roamance - Dating for Travel Lovers";
	$pageCSS = "/assets/css/login.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';

?>

<!DOCTYPE html>
<html lang="en">

<body>

<div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/scrollimg1.jpg');"></div>
<div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/scrollimg2.jpg');"></div>
<div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/scrollimg3.jpg');"></div>
<div class="bg-overlay"></div>

<div class="container mt-5">
    <div class="info-box">
        <h1>Welcome to Roamance</h1>
        <p>Where wanderlust meets romance. Connect with fellow travel enthusiasts who share your passion for exploring the world.</p>
        <p>Find your perfect travel companion and create unforgettable memories together across the globe.</p>
    </div>

    <div class="auth-box mt-4">
        <h2>Join Roamance</h2>
        <hr>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <label style="display:flex; align-items:center; gap:8px; font-size:0.9em; margin:4px 0;">
                <input type="checkbox" name="remember_me" value="1"> Remember me
            </label>
			<button type="submit" name="login" class="btn btn-secondary btn-signup">Log In</button>
            <!-- <button type="button" name="signup" class="btn btn-primary btn-signup">Sign Up</button> -->
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();

