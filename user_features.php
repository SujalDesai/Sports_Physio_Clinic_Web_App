<?php
// ==========================
// USER FEATURES
// ==========================

// Cancel appointment
function cancelAppointment($conn, $id, $user) {
    $stmt = $conn->prepare("UPDATE appointments SET status='Cancelled' WHERE id=? AND username=?");
    $stmt->bind_param("is", $id, $user);
    return $stmt->execute();
}

// Reschedule appointment
function rescheduleAppointment($conn, $id, $user, $newDate, $newTime) {
    $stmt = $conn->prepare("UPDATE appointments SET appointment_date=?, appointment_time=?, status='Rescheduled' WHERE id=? AND username=?");
    $stmt->bind_param("ssis", $newDate, $newTime, $id, $user);
    return $stmt->execute();
}

// Appointment calendar view (grouped by date)
function getAppointmentsByDate($conn, $user) {
    $stmt = $conn->prepare("SELECT appointment_date, appointment_time, service, status 
                            FROM appointments WHERE username=? ORDER BY appointment_date, appointment_time");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    return $stmt->get_result();
}

// Profile management (update user details)
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

// Notifications (stub functions for now)
function sendBookingNotification($email, $service, $date, $time) {
    // In production: use PHPMailer or similar
    return true;
}

function sendReminderNotification($email, $service, $date, $time) {
    // In production: trigger via cron job
    return true;
}

// Feedback & ratings
function submitFeedback($conn, $user, $appointmentId, $rating, $comments) {
    $stmt = $conn->prepare("INSERT INTO feedback (username, appointment_id, rating, comments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $user, $appointmentId, $rating, $comments);
    return $stmt->execute();
}

// Service info pages (static content for now)
function getServiceInfo($service) {
    $info = [
        "Sports Injury Consultation" => "Expert advice on sports injuries with tailored recovery plans.",
        "Rehabilitation Session" => "Personalized recovery sessions to regain strength and mobility.",
        "Performance Training" => "Boost your athletic performance with tailored programs."
    ];
    return $info[$service] ?? "Service information not available.";
}

// Appointment history
function getAppointmentHistory($conn, $user) {
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE username=? ORDER BY appointment_date DESC");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    return $stmt->get_result();
}

// Health notes upload (stub)
function uploadHealthNotes($conn, $user, $appointmentId, $filePath) {
    $stmt = $conn->prepare("INSERT INTO health_notes (username, appointment_id, file_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $user, $appointmentId, $filePath);
    return $stmt->execute();
}

// Gamification / progress tracking
function getProgress($conn, $user) {
    $completed = $conn->query("SELECT COUNT(*) AS c FROM appointments WHERE username='$user' AND status='Completed'")->fetch_assoc()['c'];
    $total = $conn->query("SELECT COUNT(*) AS c FROM appointments WHERE username='$user'")->fetch_assoc()['c'];
    return $total > 0 ? round(($completed / $total) * 100) : 0;
}

// Secure messaging (stub)
function sendMessage($conn, $user, $appointmentId, $message) {
    $stmt = $conn->prepare("INSERT INTO messages (username, appointment_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $user, $appointmentId, $message);
    return $stmt->execute();
}
?>
