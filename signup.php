<?php
include 'db_connect.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')";

if ($conn->query($sql) === TRUE) {
    echo "<h2 style='color:green;text-align:center;'>Sign up successful! <a href='user_login.html'>Login here</a></h2>";
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>
