<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php"); // Перенаправляем пользователя на страницу входа, если не авторизован
  exit();
}

require_once '../_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $carId = $_POST['car_id']; // Получаем ID автомобиля из формы
  $branchId = $_POST['branch']; // Получаем ID филиала из формы
  $dateStart = $_POST['date_start']; // Получаем дату начала аренды из формы
  $dateEnd = $_POST['date_end']; // Получаем дату окончания аренды из формы
  $userId = $_SESSION['user_id']; // Получаем ID пользователя из сессии

  // Рассчитываем количество дней аренды
  $datetimeStart = new DateTime($dateStart);
  $datetimeEnd = new DateTime($dateEnd);
  $interval = $datetimeStart->diff($datetimeEnd);
  $numDays = $interval->days;

  // Рассчитываем скидку, если аренда превышает 3 дня
  $discount = 0;
  if ($numDays > 3) {
    $additionalDays = $numDays - 3;
    $discount = min($additionalDays / 3 * 5, 25); // Максимальная скидка - 25%
  }

  // Получаем цену автомобиля
  $carPrice = 0;
  $carSql = "SELECT price FROM cars WHERE _car_id = ?";
  $stmtCar = $conn->prepare($carSql);
  $stmtCar->bind_param("i", $carId);
  $stmtCar->execute();
  $resultCar = $stmtCar->get_result();
  if ($rowCar = $resultCar->fetch_assoc()) {
    $carPrice = $rowCar['price'];
  }

  // Рассчитываем общую стоимость аренды с учетом скидки
  $totalPrice = $carPrice * $numDays * (1 - ($discount / 100));

  // Вставляем данные бронирования в таблицу booking
  $sql = "INSERT INTO booking (_car_id, _branch_id, date_start, date_end, _user_id, price) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('iissid', $carId, $branchId, $dateStart, $dateEnd, $userId, $totalPrice);

  if ($stmt->execute()) {
    header("Location: ../history.php"); // Перенаправляем пользователя на страницу истории бронирований
    exit;
  } else {
    echo "Error: " . $stmt->error; // Выводим сообщение об ошибке, если вставка не удалась
  }

  $stmtCar->close();
  $stmt->close();
}
?>