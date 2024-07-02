<?php
// для хоста
// $servername = 'localhost';
// $username = 'p904840y_8';
// $dbname = 'p904840y_8';
// $password = 'S1S&c8pT';

// для локалки
$servername = 'localhost';
$username = 'root';
$dbname = 'index-drive';
$password = '';

// новоое подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// вывод об ошибке
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
