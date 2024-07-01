<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

require_once '../_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $carId = $_POST['car_id'];
  $branchId = $_POST['branch'];
  $dateStart = $_POST['date_start'];
  $dateEnd = $_POST['date_end'];
  $userId = $_SESSION['user_id'];

  $sql = "INSERT INTO booking (_car_id, _branch_id, date_start, date_end, _user_id) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  $stmt->bind_param('iissi', $carId, $branchId, $dateStart, $dateEnd, $userId);

  if ($stmt->execute()) {
    echo "Booking successfully added.";
    header("Location: ../profile.php");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }
}
?>