<?php
session_start();

if (isset($_POST['logout'])) {
  $_SESSION = array();

  session_destroy();

  header("Location: login.php");
  exit;
} else {
  header("Location: error.php");
  exit;
}
?>