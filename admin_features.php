<?php


function confirmAppointment($conn, $id) {
    $stmt = $conn->prepare("UPDATE appointments SET status='Confirmed' WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function rejectAppointment($conn, $id) {
    $stmt = $conn->prepare("UPDATE appointments SET status='Cancelled' WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


function searchAppointments($conn, $date = null, $service = null, $user = null) {
    $query = "SELECT * FROM appointments WHERE 1=1";
    $params = [];
    $types = "";

    if ($date) { $query .= " AND appointment_date=?"; $params[] = $date; $types .= "s"; }
    if ($service) { $query .= " AND service=?"; $params[] = $service; $types .= "s"; }
    if ($user) { $query .= " AND username=?"; $params[] = $user; $types .= "s"; }

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result();
}

function getAnalytics($conn) {
    $analytics = [];
    $analytics['popular_service'] = $conn->query("SELECT service, COUNT(*) AS c FROM appointments GROUP BY service ORDER BY c DESC LIMIT 1")->fetch_assoc();
    $analytics['peak_times'] = $conn->query("SELECT appointment_time, COUNT(*) AS c FROM appointments GROUP BY appointment_time ORDER BY c DESC LIMIT 3")->fetch_all(MYSQLI_ASSOC);
    $analytics['monthly_trends'] = $conn->query("SELECT MONTH(appointment_date) AS month, COUNT(*) AS c FROM appointments GROUP BY month")->fetch_all(MYSQLI_ASSOC);
    return $analytics;
}


function getAllUsers($conn) {
    return $conn->query("SELECT id, username, email, created_at FROM users");
}

function deactivateUser($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function resetUserPassword($conn, $id, $newPassword) {
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $hashed, $id);
    return $stmt->execute();
}


function exportAppointmentsCSV($conn) {
    $result = $conn->query("SELECT * FROM appointments");
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=appointments.csv');
    $csv = fopen("php://output", "w");
    fputcsv($csv, ["ID", "Username", "Service", "Date", "Time", "Status", "Created At"]);
    while ($row = $result->fetch_assoc()) {
        fputcsv($csv, $row);
    }
    fclose($csv);
    exit;
}

function getAdminUsers($conn) {
    return $conn->query("SELECT username, role FROM users WHERE role='admin'");
}

function logAdminAction($conn, $admin, $action, $appointmentId) {
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $appointmentId);
    return $stmt->execute();
}



function sendAdminNotification($email, $summary) {

    return true;
}
?>

