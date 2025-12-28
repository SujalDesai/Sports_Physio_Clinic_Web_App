<?php
session_start();
include 'db_connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

$adminUser = "admin";
$adminPass = "admin@123";

if ($username === $adminUser && $password === $adminPass) {
    $_SESSION['admin'] = $username;
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "<h2 style='color:red;text-align:center;'>Invalid admin credentials</h2>";
}
?>

