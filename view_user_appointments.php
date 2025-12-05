<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../user_login.html");
    exit;
}

$user = $_SESSION['user'];
$result = $conn->query("SELECT * FROM appointments WHERE username='$user' ORDER BY appointment_date, appointment_time");

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
echo json_encode($appointments);
$conn->close();
?>
