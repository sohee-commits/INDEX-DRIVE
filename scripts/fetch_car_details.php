<?php
require_once './_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $mark = $_POST['mark'];
  $model = $_POST['model'];

  $stmt = $conn->prepare("SELECT * FROM cars WHERE mark = ? AND model = ?");
  $stmt->bind_param("ss", $mark, $model);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $carDetails = $result->fetch_assoc();
    echo json_encode($carDetails);
  } else {
    echo json_encode([]);
  }

  $stmt->close();
  $conn->close();
}