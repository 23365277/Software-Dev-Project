<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

if (isset($_COOKIE['remember_me'])) {
    deleteRememberToken($_COOKIE['remember_me']);
    setcookie('remember_me', '', time() - 1, '/', '', true, true);
}

if (isset($_COOKIE['user_name'])) {
    setcookie('user_name', '', time() - 1, '/');
}

$_SESSION = [];
session_destroy();

header("Location: /index.php");
exit;
?>
