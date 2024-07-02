<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php require_once './el-head.php' ?>

  <body>
    <?php require_once './el-header.php' ?>

    <main>
      <div class="inner-heading">
        <h1 class="fw-m">История аренды</h1>
        <p>
          Все ваши бронирования и текущие аренды в одном месте - полный контроль, удобство и прозрачность на каждом
          этапе аренды.</p>
      </div>
      <section class="history">
        <?php
        require_once './_config.php';

        $user_id = $_SESSION['user_id'];

        // Получаем информацию о бронированиях пользователя
        $booking_sql = "SELECT date_start, date_end, price, _booking_id, _car_id, _branch_id FROM booking WHERE _user_id = ?";
        $stmt_booking = $conn->prepare($booking_sql);
        $stmt_booking->bind_param("i", $user_id);
        $stmt_booking->execute();
        $result_booking = $stmt_booking->get_result();

        // Если нет бронирований, выводим сообщение об этом
        if ($result_booking->num_rows < 1) {
          echo "<h2>Здесь пока ничего нет</h2>";
        } else {
          // Выводим информацию о каждом бронировании
          while ($booking_info = $result_booking->fetch_assoc()) {
            $date_start = $booking_info['date_start'];
            $date_end = $booking_info['date_end'];
            $_booking_id = $booking_info['_booking_id'];
            $car_id = $booking_info['_car_id'];
            $branch_id = $booking_info['_branch_id'];

            // Вычисляем продолжительность аренды
            $datetime_start = new DateTime($date_start);
            $datetime_end = new DateTime($date_end);
            $interval = $datetime_start->diff($datetime_end);
            $num_days = $interval->days;

            // Получаем детали автомобиля по его идентификатору
            $car_sql = "SELECT mark, model, price, class FROM cars WHERE _car_id = ?";
            $stmt_car = $conn->prepare($car_sql);
            $stmt_car->bind_param("i", $car_id);
            $stmt_car->execute();
            $result_car = $stmt_car->get_result();
            $car_details = $result_car->fetch_assoc();

            // Получаем информацию о филиале по его идентификатору
            $branch_sql = "SELECT name FROM branches WHERE _branch_id = ?";
            $stmt_branch = $conn->prepare($branch_sql);
            $stmt_branch->bind_param("i", $branch_id);
            $stmt_branch->execute();
            $result_branch = $stmt_branch->get_result();
            $branch_details = $result_branch->fetch_assoc();

            // Выводим информацию о бронировании и автомобиле
            echo "
            <section class='item'>
              <div class='card-body'>
                <div class='heading'>
                  <p class='car-name'>" . $car_details['mark'] . " " . $car_details['model'] . "</p>
                  <a href='booking.php' type='submit' class='red'>Арендовать снова</a>
                </div>
                <img src='./assets/index/cars/" . $car_details['mark'] . " " . $car_details['model'] . ".png' alt='превью машины'>
              </div>
              <div class='group'>
                <p role='label'>Класс автомобиля</p>
                <p>";
            echo $car_details['class'] == 'premium' ? 'Премиум' : 'Эконом';
            echo "
                </p>
              </div>
              <div class='group'>
                <p role='label'>Стоимость аренды</p>
                <p>" . number_format($car_details['price'], 0, '', ' ') . " ₽ / день</p>
              </div>
              <div class='group'>
                <p role='label'>Номер бронирования</p>
                <p>" . $booking_info['_booking_id'] . "</p>
              </div>
              <div class='group'>
                <p role='label'>Дата</p>
                <p>" . $datetime_start->format('d.m.Y') . " - " . $datetime_end->format('d.m.Y') . " (" . $num_days . " дн.)</p>
              </div>
              <div class='group'>
                <p role='label'>Филиал</p>
                <p>" . $branch_details['name'] . "</p>
              </div>
              <hr />
              <div class='group'>
                <p role='label'>Итоговая стоимость аренды</p>
                <p>" . number_format($booking_info['price'], 0, '', ' ') . " ₽</p>
              </div>
            </section>
          ";
          }
        }

        // Закрываем подготовленные запросы
        $stmt_car->close();
        $stmt_branch->close();
        $stmt_booking->close();
        ?>
      </section>
    </main>

    <?php require_once './el-footer.php' ?>
  </body>

</html>