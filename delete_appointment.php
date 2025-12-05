<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM appointments WHERE id=$id");

header("Location: admin_dashboard.php");
?>
