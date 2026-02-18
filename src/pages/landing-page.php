<?php 
	$pageTitle = "Roamance - Dating for Travel Lovers"; 
	$pageCSS = "assets/css/landing-page.css"
?>

<!DOCTYPE html>
<html lang="en">

<?php include $_SERVER['DOCUMENT_ROOT'].'/includes/head.php'; ?>

<body>


<div class="container mt-5">
    <div class="info-box">
        <h1>Welcome to Roamance</h1>
        <p>Where wanderlust meets romance. Connect with fellow travel enthusiasts who share your passion for exploring the world.</p>
        <p>Find your perfect travel companion and create unforgettable memories together across the globe.</p>
    </div>

    <div class="auth-box mt-4">
        <h2>Join Roamance</h2>
        <hr>
        <form>
            <input type="email" placeholder="Email" required>
            <input type="password" placeholder="Password" required>
            <button type="submit" class="btn btn-primary btn-signup">Sign Up</button>
            <button type="button" class="btn btn-secondary btn-login">Log In</button>
        </form>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

