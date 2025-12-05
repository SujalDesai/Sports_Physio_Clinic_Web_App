<?php
session_start();
include 'db_connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

$result = $conn->query("SELECT * FROM users WHERE username='$username'");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['user'] = $username;
        header("Location: user_dashboard.php");
        exit;
    } else {
        echo "<h2 style='color:red;text-align:center;'>Invalid password</h2>";
    }
} else {
    echo "<h2 style='color:red;text-align:center;'>User not found</h2>";
}
$conn->close();
?>
