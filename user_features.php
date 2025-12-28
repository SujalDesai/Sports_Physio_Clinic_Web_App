<?php


function cancelAppointment($conn, $id, $user) {
    $stmt = $conn->prepare("UPDATE appointments SET status='Cancelled' WHERE id=? AND username=?");
    $stmt->bind_param("is", $id, $user);
    return $stmt->execute();
}

function rescheduleAppointment($conn, $id, $user, $newDate, $newTime) {
    $stmt = $conn->prepare("UPDATE appointments SET appointment_date=?, appointment_time=?, status='Rescheduled' WHERE id=? AND username=?");
    $stmt->bind_param("ssis", $newDate, $newTime, $id, $user);
    return $stmt->execute();
}


function getAppointmentsByDate($conn, $user) {
    $stmt = $conn->prepare("SELECT appointment_date, appointment_time, service, status 
                            FROM appointments WHERE username=? ORDER BY appointment_date, appointment_time");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    return $stmt->get_result();
}

function updateUserProfile($conn, $user, $email, $password = null, $profilePic = null) {
    if ($password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET email=?, password=?, profile_pic=? WHERE username=?");
        $stmt->bind_param("ssss", $email, $hashed, $profilePic, $user);
    } else {
        $stmt = $conn->prepare("UPDATE users SET email=?, profile_pic=? WHERE username=?");
        $stmt->bind_param("sss", $email, $profilePic, $user);
    }
    return $stmt->execute();
}


function getServiceInfo($service) {
    $info = [
        "Sports Injury Consultation" => "Expert advice on sports injuries with tailored recovery plans.",
        "Rehabilitation Session" => "Personalized recovery sessions to regain strength and mobility.",
        "Performance Training" => "Boost your athletic performance with tailored programs."
    ];
    return $info[$service] ?? "Service information not available.";
}

function getAppointmentHistory($conn, $user) {
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE username=? ORDER BY appointment_date DESC");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    return $stmt->get_result();
}

function getProgress($conn, $user) {
    $completed = $conn->query("SELECT COUNT(*) AS c FROM appointments WHERE username='$user' AND status='Completed'")->fetch_assoc()['c'];
    $total = $conn->query("SELECT COUNT(*) AS c FROM appointments WHERE username='$user'")->fetch_assoc()['c'];
    return $total > 0 ? round(($completed / $total) * 100) : 0;
}


?>

