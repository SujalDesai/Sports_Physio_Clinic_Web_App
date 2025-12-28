<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: user_login.html");
    exit;
}
include 'db_connect.php';
include 'user_features.php';

$user = $_SESSION['user'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['appointment_id'])) {
        $appointmentId = intval($_POST['appointment_id']);
        $action = $_POST['action'];

        if ($action === 'Cancel') {
            $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE id = ?");
            $stmt->bind_param("i", $appointmentId);
            $stmt->execute();
        } elseif ($action === 'Reschedule') {
            $newDate = $_POST['new_date'] ?? null;
            $newTime = $_POST['new_time'] ?? null;

            if ($newDate && $newTime) {
                $stmt = $conn->prepare("UPDATE appointments SET appointment_date = ?, appointment_time = ?, status = 'Confirmed' WHERE id = ?");
                $stmt->bind_param("ssi", $newDate, $newTime, $appointmentId);
                $stmt->execute();
            }
        }

        header("Location: user_dashboard.php");
        exit;
    }
}


$result = $conn->query("SELECT * FROM appointments WHERE username='$user'");


$total = $result->num_rows;
$pending = $conn->query("SELECT COUNT(*) AS c FROM appointments WHERE username='$user' AND status='Pending'")->fetch_assoc()['c'];
$confirmed = $conn->query("SELECT COUNT(*) AS c FROM appointments WHERE username='$user' AND status='Confirmed'")->fetch_assoc()['c'];
$cancelled = $conn->query("SELECT COUNT(*) AS c FROM appointments WHERE username='$user' AND status='Cancelled'")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 to-purple-100 min-h-screen flex flex-col items-center p-8">


    
  <header class="w-full max-w-5xl flex justify-between items-center mb-8">
    <h1 class="text-4xl font-extrabold text-blue-700">👋 Welcome, <?php echo htmlspecialchars($user); ?></h1>
    <a href="logout.php" class="bg-red-500 text-white px-5 py-2 rounded-lg shadow hover:bg-red-600">Logout</a>
  </header>

  <div class="grid md:grid-cols-4 gap-6 w-full max-w-5xl mb-10">
    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
      <h2 class="text-lg font-semibold text-gray-600">Total</h2>
      <p class="text-3xl font-bold text-blue-600"><?php echo $total; ?></p>
    </div>
    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
      <h2 class="text-lg font-semibold text-gray-600">Pending</h2>
      <p class="text-3xl font-bold text-yellow-500"><?php echo $pending; ?></p>
    </div>
    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
      <h2 class="text-lg font-semibold text-gray-600">Confirmed</h2>
      <p class="text-3xl font-bold text-green-600"><?php echo $confirmed; ?></p>
    </div>
    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
      <h2 class="text-lg font-semibold text-gray-600">Cancelled</h2>
      <p class="text-3xl font-bold text-red-600"><?php echo $cancelled; ?></p>
    </div>
  </div>

  <div class="w-full max-w-5xl mb-8 text-center">
    <a href="book.html" class="bg-green-500 text-white px-8 py-3 rounded-lg shadow-lg hover:bg-green-600 transition">
      ➕ Book New Appointment
    </a>
  </div>

  <div class="w-full max-w-5xl">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">📅 Your Appointments</h2>
    <?php if ($result->num_rows > 0) { ?>
      <div class="grid md:grid-cols-2 gap-6">
        <?php while($row = $result->fetch_assoc()) { ?>
          <div class="bg-white shadow-xl rounded-lg p-6 hover:scale-105 transform transition text-center">
            <h3 class="text-xl font-bold text-blue-600 mb-2"><?php echo htmlspecialchars($row['service']); ?></h3>
            <p class="mb-1"><strong>Date:</strong> <?php echo $row['appointment_date']; ?></p>
            <p class="mb-1"><strong>Time:</strong> <?php echo $row['appointment_time']; ?></p>
            <p class="mb-3"><strong>Status:</strong> 
              <?php 
                $status = $row['status'];
                $color = $status === 'Confirmed' ? 'text-green-600 font-bold' : ($status === 'Cancelled' ? 'text-red-600 font-bold' : 'text-yellow-600 font-bold');
                echo "<span class='$color'>$status</span>";
              ?>
            </p>
            <div class="flex flex-col items-center space-y-4">
             
              <form method="post" class="flex flex-col items-center space-y-2">
                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="action" value="Reschedule">

                <label class="text-sm text-gray-600">New Date:</label>
                <input type="date" name="new_date" class="border rounded px-2 py-1" required>

                <label class="text-sm text-gray-600">New Time:</label>
                <input type="time" name="new_time" class="border rounded px-2 py-1" required>

                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                  Reschedule
                </button>
              </form>

              <form method="post" style="display:inline;">
                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="action" value="Cancel">
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                  Cancel
                </button>
              </form>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <p class="text-gray-600 text-center text-lg">No appointments yet. Book your first one above!</p>
    <?php } ?>
  </div>

</body>
</html>

