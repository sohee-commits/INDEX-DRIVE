<?php
// Подключение файла конфигурации базы данных
require_once '../_config.php';

// Проверка наличия параметра car_id в GET-запросе
if (isset($_GET['car_id'])) {
  // Получение значения параметра car_id из GET-запроса
  $carId = $_GET['car_id'];

  // SQL-запрос для выборки филиалов, где указанный автомобиль присутствует
  $sql = "SELECT _branch_id, name FROM branches WHERE _car_id_1 = ? OR _car_id_2 = ? OR _car_id_3 = ?";

  // Подготовка SQL-запроса
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    // Вывод ошибки, если подготовка запроса не удалась
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  // Привязка параметров к подготовленному запросу
  $stmt->bind_param('iii', $carId, $carId, $carId);

  // Выполнение запроса
  $stmt->execute();

  // Получение результата выполнения запроса
  $result = $stmt->get_result();

  // Создание массива для хранения данных о филиалах
  $branches = array();

  // Обработка каждой строки результата и добавление в массив branches
  while ($row = $result->fetch_assoc()) {
    $branches[] = $row;
  }

  // Установка заголовка Content-Type для возврата данных в формате JSON
  header('Content-Type: application/json');

  // Вывод данных в формате JSON
  echo json_encode($branches);

  // Завершение выполнения скрипта
  exit;
} else {
  // Вывод сообщения об ошибке, если параметр car_id не был передан в GET-запросе
  echo json_encode(["error" => "Invalid request"]);

  // Завершение выполнения скрипта
  exit;
}
?>