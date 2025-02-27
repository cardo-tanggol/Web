<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'user') {
        header("Location: user_dashboard.php");
        exit;
    }
}

// If not logged in, redirect to login page
header("Location: login.php");
exit;
?>