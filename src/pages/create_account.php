<?php
	$pageTitle = "Roamance - Create Account";
	$pageCSS = "/assets/css/create_account.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
    <div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/scrollimg1.jpg');"></div>
    <div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/scrollimg2.jpg');"></div>
    <div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/scrollimg3.jpg');"></div>
    <div class="bg-overlay"></div>

    <div class="container mt-5">

        <div class="auth-box">
            <form>
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="secondName" placeholder="Second Name" required>
                <input type="text" name="dateOfBirth" placeholder="Date of Birth" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="password" placeholder="Password" required>
                <textarea type="text" name="bio" placeholder="Bio" required></textarea>
                <input type="file" name="profilePicture" placeholder="Profile Picture" accept="image/*" required>
                <button type="submit" class="btn-signup">Sign-up</button>
            </form>
        </div>

    </div>

<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
?>