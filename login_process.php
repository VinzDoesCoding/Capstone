<?php
session_start();

// Sample static credentials (use database in production)
$valid_user = 'crew';
$valid_pass = '1234';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === $valid_user && $password === $valid_pass) {
    $_SESSION['is_admin'] = true;
    $_SESSION['username'] = $username; // ✅ Store the username
    header('Location: admin_dashboard.php');
    exit;
} else {
    echo "<script>alert('Invalid credentials');window.location.href='login.php';</script>";
}
?>