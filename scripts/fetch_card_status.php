<?php
require_once './_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $cardNumber = $_POST['cardNumber'];

  $stmt = $conn->prepare("SELECT status FROM cards WHERE number = ?");
  $stmt->bind_param("s", $cardNumber);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $cardDetails = $result->fetch_assoc();
    echo $cardDetails['status'];
  } else {
    echo 'Status not found';
  }

  $stmt->close();
  $conn->close();
}