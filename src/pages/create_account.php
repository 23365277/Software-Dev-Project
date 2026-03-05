<?php
	$pageTitle = "Roamance - Create Account";
	$pageCSS = "/assets/css/create_account.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
?>

<div class="container-liquid d-flex-column min-vh-75">
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? ''; 
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        registerNewUser($email, $password, $first_name, $last_name, $date_of_birth);
    }
?>
    <div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/scrollimg1.jpg');"></div>
    <div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/scrollimg2.jpg');"></div>
    <div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/scrollimg3.jpg');"></div>
    <div class="bg-overlay"></div>

    <div class="row justify-content-center" >

        <div class="col-3">
            <form class="auth-form">
                <div id="step1">
                    <h2 class="signup-Title">Create Account</h2>
                    <input type="text" name="email" placeholder="Email" required>
                    <input type="text" name="password" placeholder="Password" required>
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
                    <button type="button" onclick=nextStep() class="btn-signup">Next Step</button>
                </div>
                <div id="step2" style="display: none;">
                    <h2 class="signup-Title">Interest Form</h2>
                    <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
                    <button type="submit" class="btn-signup">Sign-up</button>
                </div>
            </form>
        </div>

    </div>

    
</div>

<script src="/includes/js/create_account.js"></script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>

<!-- method="POST" action="" -->