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

    <?php
        echo'
    <div class="row justify-content-center">

        <div class="auth-box col-3">
            <form>
                <h2 class="signup-Title">Interest Form</h2>
                <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
                <button type="submit" class="btn-signup">Sign-up</button>
            </form>
        </div>
        ';
        ?>
    </div><?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
