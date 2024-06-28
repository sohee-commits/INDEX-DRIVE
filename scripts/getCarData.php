<?php
require_once '../_config.php';

if (isset($_GET['mark']) && !isset($_GET['model'])) {
  // Handle getting models based on mark
  $selectedMark = $_GET['mark'];

  $sql = "SELECT DISTINCT model FROM cars WHERE mark = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  $stmt->bind_param('s', $selectedMark);
  $stmt->execute();
  $result = $stmt->get_result();

  $models = array();
  while ($row = $result->fetch_assoc()) {
    $models[] = $row['model'];
  }

  header('Content-Type: application/json');
  echo json_encode($models);
  exit;
} elseif (isset($_GET['mark']) && isset($_GET['model'])) {
  $selectedMark = $_GET['mark'];
  $selectedModel = $_GET['model'];

  $sql = "SELECT _car_id, number, price FROM cars WHERE mark = ? AND model = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  $stmt->bind_param('ss', $selectedMark, $selectedModel);
  $stmt->execute();
  $result = $stmt->get_result();

  $carDetails = $result->fetch_assoc();

  $sql = "SELECT name FROM branches WHERE _car_id_1 = ? OR _car_id_2 = ? OR _car_id_3 = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  $stmt->bind_param('iii', $carDetails['_car_id'], $carDetails['_car_id'], $carDetails['_car_id']);
  $stmt->execute();
  $result = $stmt->get_result();

  $branches = array();
  while ($row = $result->fetch_assoc()) {
    $branches[] = $row['name'];
  }

  $carDetails['branches'] = $branches;

  header('Content-Type: application/json');
  echo json_encode($carDetails);
  exit;
} else {
  echo json_encode(["error" => "Invalid request"]);
  exit;
}