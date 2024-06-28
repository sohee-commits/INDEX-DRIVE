<?php
require_once '../_config.php';

if (isset($_GET['car_id'])) {
  $carId = $_GET['car_id'];

  $sql = "SELECT name FROM branches WHERE _car_id_1 = ? OR _car_id_2 = ? OR _car_id_3 = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  $stmt->bind_param('iii', $carId, $carId, $carId);
  $stmt->execute();
  $result = $stmt->get_result();

  $branches = array();
  while ($row = $result->fetch_assoc()) {
    $branches[] = $row['name'];
  }

  header('Content-Type: application/json');
  echo json_encode($branches);
  exit;
} else {
  echo json_encode(["error" => "Invalid request"]);
  exit;
}