<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../_config.php';

if (isset($_GET['car_id']) && isset($_GET['branch_id']) && isset($_GET['date_start']) && isset($_GET['date_end'])) {
  $carId = $_GET['car_id'];
  $branchId = $_GET['branch_id'];
  $dateStart = $_GET['date_start'];
  $dateEnd = $_GET['date_end'];

  $sql = "SELECT COUNT(*) AS count_bookings
          FROM booking
          WHERE _car_id = ? 
            AND _branch_id = ? 
            AND ((date_start <= ? AND date_end >= ?)
                 OR (date_start <= ? AND date_end >= ?)
                 OR (date_start >= ? AND date_end <= ?))";

  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    echo json_encode(['error' => 'Prepare failed: ' . htmlspecialchars($conn->error)]);
    exit;
  }

  $stmt->bind_param("iissssss", $carId, $branchId, $dateStart, $dateStart, $dateEnd, $dateEnd, $dateStart, $dateEnd);

  if ($stmt->execute() === false) {
    echo json_encode(['error' => 'Execute failed: ' . htmlspecialchars($stmt->error)]);
    exit;
  }

  $result = $stmt->get_result();
  if (!$result) {
    echo json_encode(['error' => 'Get result failed: ' . htmlspecialchars($stmt->error)]);
    exit;
  }

  $countBookings = $result->fetch_assoc()['count_bookings'];

  if ($countBookings > 0) {
    echo json_encode(["error" => "На выбранные даты автомобиль недоступен"]);
  } else {
    echo json_encode(["success" => "Car is available for the selected dates"]);
  }
  exit;
} else {
  echo json_encode(["error" => "Invalid request"]);
  exit;
}
?>