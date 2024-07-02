<?php
// Включение вывода всех ошибок для отладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Установка заголовка Content-Type для JSON
header('Content-Type: application/json');

// Подключение файла конфигурации базы данных
require_once '../_config.php';

// Проверка наличия необходимых параметров в GET-запросе
if (isset($_GET['car_id']) && isset($_GET['branch_id']) && isset($_GET['date_start']) && isset($_GET['date_end'])) {

  // Получение параметров из GET-запроса
  $carId = $_GET['car_id'];
  $branchId = $_GET['branch_id'];
  $dateStart = $_GET['date_start'];
  $dateEnd = $_GET['date_end'];

  // SQL-запрос для подсчёта количества бронирований для указанного автомобиля на указанные даты
  $sql = "SELECT COUNT(*) AS count_bookings
          FROM booking
          WHERE _car_id = ? 
            AND _branch_id = ? 
            AND ((date_start <= ? AND date_end >= ?)
                 OR (date_start <= ? AND date_end >= ?)
                 OR (date_start >= ? AND date_end <= ?))";

  // Подготовка SQL-запроса
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    // Вывод ошибки, если подготовка запроса не удалась
    echo json_encode(['error' => 'Prepare failed: ' . htmlspecialchars($conn->error)]);
    exit;
  }

  // Привязка параметров к подготовленному запросу
  $stmt->bind_param("iissssss", $carId, $branchId, $dateStart, $dateStart, $dateEnd, $dateEnd, $dateStart, $dateEnd);

  // Выполнение запроса
  if ($stmt->execute() === false) {
    // Вывод ошибки, если выполнение запроса не удалось
    echo json_encode(['error' => 'Execute failed: ' . htmlspecialchars($stmt->error)]);
    exit;
  }

  // Получение результата выполнения запроса
  $result = $stmt->get_result();
  if (!$result) {
    // Вывод ошибки, если получение результата не удалось
    echo json_encode(['error' => 'Get result failed: ' . htmlspecialchars($stmt->error)]);
    exit;
  }

  // Получение количества бронирований
  $countBookings = $result->fetch_assoc()['count_bookings'];

  // Проверка количества бронирований и формирование JSON-ответа
  if ($countBookings > 0) {
    echo json_encode(["error" => "На выбранные даты автомобиль недоступен"]);
  } else {
    echo json_encode(["success" => "Автомобиль доступен для выбранных дат"]);
  }
  exit;
} else {
  // Вывод ошибки, если параметры не были переданы в запросе
  echo json_encode(["error" => "Invalid request"]);
  exit;
}
?>