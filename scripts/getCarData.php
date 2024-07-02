<?php
require_once '../_config.php';

if (isset($_GET['mark']) && !isset($_GET['model'])) {
  // Обработка запроса на получение моделей по марке
  $selectedMark = $_GET['mark'];

  // SQL запрос для выбора уникальных моделей автомобилей по заданной марке
  $sql = "SELECT DISTINCT model FROM cars WHERE mark = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  // Привязка параметра марки к подготовленному запросу
  $stmt->bind_param('s', $selectedMark);
  $stmt->execute();
  $result = $stmt->get_result();

  // Формирование списка моделей
  $models = array();
  while ($row = $result->fetch_assoc()) {
    $models[] = $row['model'];
  }

  // Возврат списка моделей в формате JSON
  header('Content-Type: application/json');
  echo json_encode($models);
  exit;
} elseif (isset($_GET['mark']) && isset($_GET['model'])) {
  // Обработка запроса на получение подробностей об автомобиле по марке и модели
  $selectedMark = $_GET['mark'];
  $selectedModel = $_GET['model'];

  // SQL запрос для выбора подробностей об автомобиле по марке и модели
  $sql = "SELECT _car_id, number, price FROM cars WHERE mark = ? AND model = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  // Привязка параметров марки и модели к подготовленному запросу
  $stmt->bind_param('ss', $selectedMark, $selectedModel);
  $stmt->execute();
  $result = $stmt->get_result();

  // Получение подробностей об автомобиле
  $carDetails = $result->fetch_assoc();

  // SQL запрос для выбора списка филиалов, где доступен данный автомобиль
  $sql = "SELECT name FROM branches WHERE _car_id_1 = ? OR _car_id_2 = ? OR _car_id_3 = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  // Привязка идентификатора автомобиля к подготовленному запросу
  $stmt->bind_param('iii', $carDetails['_car_id'], $carDetails['_car_id'], $carDetails['_car_id']);
  $stmt->execute();
  $result = $stmt->get_result();

  // Формирование списка филиалов, где доступен автомобиль
  $branches = array();
  while ($row = $result->fetch_assoc()) {
    $branches[] = $row['name'];
  }

  // Добавление списка филиалов к подробностям об автомобиле
  $carDetails['branches'] = $branches;

  // Возврат подробностей об автомобиле и списка филиалов в формате JSON
  header('Content-Type: application/json');
  echo json_encode($carDetails);
  exit;
} else {
  // В случае неверного запроса возвращаем сообщение об ошибке
  echo json_encode(["error" => "Invalid request"]);
  exit;
}
?>