<?php
// Only show message if user just logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $user_email = htmlspecialchars($_SESSION['email']);
    echo '
    <div id="loginMessage" class="message-bubble">
        Welcome back, ' . $user_email . '!
    </div>
    ';
    
    // Remove the session flag so it only shows once
    unset($_SESSION['logged_in']);
}
?>