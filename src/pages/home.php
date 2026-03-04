<?php
// Start session first, before any output
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

// Safe check for $login_successful
if (isset($login_successful) && $login_successful) {
    $_SESSION['logged_in'] = true;
    $_SESSION['email'] = $user_email; // from DB
    header("Location: dashboard.php"); // redirect after login
    exit();
}

// Page metadata
$pageTitle = "Roamance - Home";
$pageCSS = "/assets/css/home.css";

// Include header and messaging components
include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/messaging.php";

?>
<!-- Main Page Content -->
<div class="container col-12 row">
    <div class="card col-lg-4 col-md-6 col-sm-12">
        <h2 class="matches center-text">Matches and Likes</h2>
    </div>
    <div class="container col-lg-8 col-md-6 col-sm-12">
        <div class="card">
            <h2 class="center-text">Messages</h2>
        </div>
        <div class="container row">
            <div class="card col-lg-5 col-md-5 col-sm-12 mt-4">
                <h2 class="center-text">Discovery Feed</h2>
            </div>
            <div class="card col-lg-5 col-md-5 col-sm-12 mt-4">
                <h2 class="center-text">Post A Trip</h2>
            </div>
        </div>
    </div>
</div>

<?php
// Include login welcome bubble at the bottom
include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/login_welcome.php';
?>