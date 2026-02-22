<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

	$error = '';

	if (isset($_POST['signup'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];

		$user_id = verifyLogin($email, $password);
		if($user_id){
			header('Location: /pages/home.php');
			exit();
		} else {
			$error = "Something went wrong, please try again.";
		}
	}

	$pageTitle = "Roamance - Dating for Travel Lovers";
	$pageCSS = "/assets/css/login.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

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
            <button type="submit" name="signup" class="btn btn-primary btn-signup">Sign Up</button>
            <button type="button" name="login" class="btn btn-secondary btn-login">Log In</button>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

