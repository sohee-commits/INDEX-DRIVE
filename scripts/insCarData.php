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

  $datetimeStart = new DateTime($dateStart);
  $datetimeEnd = new DateTime($dateEnd);
  $interval = $datetimeStart->diff($datetimeEnd);
  $numDays = $interval->days;

  $discount = 0;
  if ($numDays > 3) {
    $additionalDays = $numDays - 3;
    $discount = min($additionalDays / 3 * 5, 25);
  }

  $carPrice = 0;
  $carSql = "SELECT price FROM cars WHERE _car_id = ?";
  $stmtCar = $conn->prepare($carSql);
  $stmtCar->bind_param("i", $carId);
  $stmtCar->execute();
  $resultCar = $stmtCar->get_result();
  if ($rowCar = $resultCar->fetch_assoc()) {
    $carPrice = $rowCar['price'];
  }

  $totalPrice = $carPrice * $numDays * (1 - ($discount / 100));


  // Insert into booking table
  $sql = "INSERT INTO booking (_car_id, _branch_id, date_start, date_end, _user_id, price) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('iissid', $carId, $branchId, $dateStart, $dateEnd, $userId, $totalPrice);

  if ($stmt->execute()) {
    echo "Booking successfully added.";
    header("Location: ../history.php");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmtCar->close();
  $stmt->close();
}
?>