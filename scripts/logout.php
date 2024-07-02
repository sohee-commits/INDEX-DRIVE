<?php
session_start();

// Debugging: Check if the session is started and the request method is POST
if (!isset($_SESSION)) {
  echo 'Session not started.';
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['logout'])) {
    $_SESSION = array();
    session_destroy();

    // Redirect to login page
    header("Location: ../login.php");
    exit;
  } else {
    echo 'Logout button not clicked.';
  }
} else {
  echo 'Request method is not POST.';
}
?>