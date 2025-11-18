<?php
// Admin authentication check helper file
// Include this file at the top of admin pages to ensure only logged-in admins can access them

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

