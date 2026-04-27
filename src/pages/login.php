<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

if (isset($_SESSION['user_id'])) {
    header('Location: /pages/home.php');
    exit();
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

$pageTitle = "Roamance - Dating for Travel Lovers";
$pageCSS = "/assets/css/login.css?v=" . filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/css/login.css');
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

    <div class="auth-box">
        <?php if (isset($_GET['blocked'])): ?>
            <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:5px; font-size:0.9em; margin-bottom:10px;">
                <?php if ($_GET['blocked'] === 'banned'): ?>
                    Your account has been permanently banned.
                <?php elseif ($_GET['blocked'] === 'suspended'): ?>
                    Your account has been suspended.
                    <?php if (!empty($_GET['duration'])): ?>
                        Duration: <?= htmlspecialchars($_GET['duration']) ?>.
                    <?php endif; ?>
                    Please contact support.
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <h3 class="login-heading">Log In</h3>
        <form id="login-form">
            <div id="login-error" style="display:none; background:#fee2e2; color:#b91c1c; padding:10px; border-radius:5px; font-size:0.9em;"></div>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <label style="display:flex; align-items:center; gap:8px; font-size:0.9em; margin:4px 0;">
                <input type="checkbox" name="remember_me" value="1"> Remember me
            </label>
            <button type="submit" id="login-btn" class="btn btn-secondary btn-signup">Log In</button>
        </form>

        <div class="join-divider">
            <span>New to Roamance?</span>
        </div>
        <a href="/pages/create_account.php" class="btn-join-cta">Create an Account</a>

        <script>
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const errorDiv = document.getElementById('login-error');
            const btn      = document.getElementById('login-btn');

            errorDiv.style.display = 'none';
            btn.disabled = true;
            btn.textContent = 'Logging in...';

            const body = new URLSearchParams({
                email:       this.email.value,
                password:    this.password.value,
                remember_me: this.remember_me.checked ? '1' : ''
            });

            try {
                const res  = await fetch('/actions/login_action.php', { method: 'POST', body });
                const data = await res.json();

                if (data.success) {
                    window.location.href = '/pages/home.php';
                } else {
                    errorDiv.textContent    = data.error;
                    errorDiv.style.display  = 'block';
                    btn.disabled            = false;
                    btn.textContent         = 'Log In';
                }
            } catch (err) {
                errorDiv.textContent   = 'A network error occurred. Please try again.';
                errorDiv.style.display = 'block';
                btn.disabled           = false;
                btn.textContent        = 'Log In';
            }
        });
        </script>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php';