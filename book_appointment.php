<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: user_login.html");
    exit;
}

$user = $_SESSION['user'];
$service = $_POST['service'];
$date = $_POST['appointment_date'];
$time = $_POST['appointment_time'];

$check = $conn->query("SELECT * FROM appointments WHERE appointment_date='$date' AND appointment_time='$time'");
if ($check->num_rows > 0) {
    echo "<h2 style='color:red;text-align:center;'>Slot already booked! Please choose another.</h2>";
    exit;
}

$sql = "INSERT INTO appointments (username, service, appointment_date, appointment_time, status)
        VALUES ('$user', '$service', '$date', '$time', 'Pending')";

if ($conn->query($sql) === TRUE) {
    header("Location: user_dashboard.php");
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>
