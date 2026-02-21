<?php
	$pageTitle = "Roamance - Create Account";
	$pageCSS = "/assets/css/create_account.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $profile_picture = $_POST['profile_picture'] ?? '';
        registerNewUser($username, $email, $password, $first_name, $last_name, $date_of_birth, $bio, $profile_picture);
    }
?>
    <div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/scrollimg1.jpg');"></div>
    <div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/scrollimg2.jpg');"></div>
    <div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/scrollimg3.jpg');"></div>
    <div class="bg-overlay"></div>

    <div class="container mt-5">

        <div class="auth-box">
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="email" placeholder="Email" required>
                <input type="text" name="password" placeholder="Password" required>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
                <textarea type="text" name="bio" placeholder="Bio" required></textarea>
                <input type="text" name="profile_picture" placeholder="Profile Picture" required>
                <button type="submit" class="btn-signup">Sign-up</button>
            </form>
        </div>

    </div>
<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
?>