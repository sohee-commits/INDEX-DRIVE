<?php
$servername = 'localhost';
// $dbname = 'p904840y_8';
// $username = 'p904840y_8';
$username = 'root';
$dbname = 'index-drive';
// $password = 'S1S&c8pT';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
