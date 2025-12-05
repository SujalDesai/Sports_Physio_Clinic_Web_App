<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit;
}
include 'db_connect.php';
include 'admin_features.php';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        confirmAppointment($conn, $_POST['id']);
        logAdminAction($conn, $_SESSION['admin'], 'Confirmed', $_POST['id']);
    }
    if (isset($_POST['reject'])) {
        rejectAppointment($conn, $_POST['id']);
        logAdminAction($conn, $_SESSION['admin'], 'Rejected', $_POST['id']);
    }
}

// Handle filters
$dateFilter = $_POST['filter_date'] ?? null;
$serviceFilter = $_POST['filter_service'] ?? null;
$userFilter = $_POST['filter_user'] ?? null;

if (isset($_POST['filter'])) {
    $result = searchAppointments($conn, $dateFilter, $serviceFilter, $userFilter);
} else {
    $result = $conn->query("SELECT * FROM appointments ORDER BY appointment_date, appointment_time");
}

$stats = getAnalytics($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

  <!-- Header -->
  <header class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-blue-600">Admin Dashboard</h1>
    <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
  </header>

  <!-- Filter Form -->
  <form method="POST" class="flex flex-wrap gap-4 mb-6">
    <input type="date" name="filter_date" class="border p-2 rounded w-48" placeholder="dd-mm-yyyy">
    <select name="filter_service" class="border p-2 rounded w-64">
      <option value="">All Services</option>
      <option value="Sports Injury Consultation">Sports Injury Consultation</option>
      <option value="Rehabilitation Session">Rehabilitation Session</option>
      <option value="Performance Training">Performance Training</option>
    </select>
    <input type="text" name="filter_user" placeholder="Search by user" class="border p-2 rounded w-64">
    <button type="submit" name="filter" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
  </form>

  <!-- Appointment Table -->
  <table class="min-w-full bg-white rounded shadow">
    <thead>
      <tr class="bg-blue-600 text-white">
        <th class="py-2 px-4">User</th>
        <th class="py-2 px-4">Service</th>
        <th class="py-2 px-4">Date</th>
        <th class="py-2 px-4">Time</th>
        <th class="py-2 px-4">Status</th>
        <th class="py-2 px-4">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) { ?>
        <tr class="hover:bg-gray-100">
          <td class="py-2 px-4"><?php echo $row['username']; ?></td>
          <td class="py-2 px-4"><?php echo $row['service']; ?></td>
          <td class="py-2 px-4"><?php echo $row['appointment_date']; ?></td>
          <td class="py-2 px-4"><?php echo $row['appointment_time']; ?></td>
          <td class="py-2 px-4">
            <?php 
              $status = $row['status'];
              $color = $status === 'Confirmed' ? 'text-green-600 font-bold' : ($status === 'Cancelled' ? 'text-red-600 font-bold' : 'text-yellow-600 font-bold');
              echo "<span class='$color'>$status</span>";
            ?>
          </td>
          <td class="py-2 px-4">
            <form method="POST" class="flex space-x-2">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button name="confirm" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Confirm</button>
              <button name="reject" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Reject</button>
            </form>
          </td>
        </tr>
      <?php }} else { ?>
        <tr><td colspan="6" class="text-center py-4 text-gray-500">No appointments found for selected filters.</td></tr>
      <?php } ?>
    </tbody>
  </table>
<br><br>

<h1 style="text-align:center; font-family:Arial, sans-serif; margin-bottom:20px; color:#2c3e50; font-size: 35px;">
    📊 Analytics Dashboard
</h1>

<div style="display:flex; flex-wrap:wrap; gap:30px; justify-content:center;">

    <div style="background:#fff; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); padding:20px; text-align:center; width:420px;">
        <h3 style="color:#2980b9; font-family:Arial, sans-serif;">Service Popularity</h3>
        <img src="service_popularity.png" alt="Service Popularity Chart" style="width:100%; border-radius:8px;">
    </div>

    <div style="background:#fff; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); padding:20px; text-align:center; width:420px;">
        <h3 style="color:#27ae60; font-family:Arial, sans-serif;">Peak Hours</h3>
        <img src="peak_hours.png" alt="Peak Hours Chart" style="width:100%; border-radius:8px;">
    </div>

    <div style="background:#fff; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); padding:20px; text-align:center; width:420px;">
        <h3 style="color:#8e44ad; font-family:Arial, sans-serif;">Monthly Trends</h3>
        <img src="monthly_trends.png" alt="Monthly Trends Chart" style="width:100%; border-radius:8px;">
    </div>

    <div style="background:#fff; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); padding:20px; text-align:center; width:420px;">
        <h3 style="color:#e67e22; font-family:Arial, sans-serif;">Cancellation Trends</h3>
        <img src="cancellation_trends.png" alt="Cancellation Trends Chart" style="width:100%; border-radius:8px;">
    </div>

</div>



</body>
</html>
