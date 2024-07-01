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
        <h1 class="fw-m">Личный кабинет</h1>
        <form action="logout.php" method="post">
          <button class="btn-link red" class="logout">Выйти</button>
        </form>
      </div>
      <section class="info">
        <aside>
          <?php
          require_once './_config.php';

          $user_id = $_SESSION['user_id'];

          $user_sql = "SELECT first_name, last_name, patronymic FROM users WHERE _user_id = ?";
          $stmt_user = $conn->prepare($user_sql);
          $stmt_user->bind_param("i", $user_id);
          $stmt_user->execute();
          $result_user = $stmt_user->get_result();
          $user_info = $result_user->fetch_assoc();

          $booking_count_sql = "SELECT COUNT(*) AS booking_count FROM booking WHERE _user_id = ?";
          $stmt_booking_count = $conn->prepare($booking_count_sql);
          $stmt_booking_count->bind_param("i", $user_id);
          $stmt_booking_count->execute();
          $result_booking_count = $stmt_booking_count->get_result();
          $booking_count = $result_booking_count->fetch_assoc()['booking_count'];

          echo "
          <div class='group'>
              <p role='label'>Имя</p>
              <p>" . $user_info['first_name'] . "</p>
          </div>
          ";
          echo "
          <div class='group'>
              <p role='label'>Фамилия</p>
              <p>" . $user_info['last_name'] . "</p>
          </div>
          ";
          echo "
          <div class='group'>
              <p role='label'>Отчество</p>
              <p>" . $user_info['patronymic'] . "</p>
          </div>
          ";
          echo "
          <div class='group'>
              <p role='label'>Баллы</p>
              <p class='bonus'>Бонусная система неактивна</p>
          </div>
          ";
          echo "
          <div class='group'>
              <p role='label'>Количество бронирований</p>
              <p>" . $booking_count . "</p>
          </div>
          ";

          $stmt_user->close();
          $stmt_booking_count->close();
          ?>
          <hr>
          <a href="./cards.php">Посмотреть банковские карты</a>
          <a href="./history.php" class="red">История аренды</a>
        </aside>
        <section class="history">
          <?php
          require_once './_config.php';

          $user_id = $_SESSION['user_id'];

          $booking_sql = "SELECT _car_id, _branch_id, date_start, date_end FROM booking WHERE _user_id = ?";
          $stmt_booking = $conn->prepare($booking_sql);
          $stmt_booking->bind_param("i", $user_id);
          $stmt_booking->execute();
          $result_booking = $stmt_booking->get_result();

          if ($result_booking->num_rows < 1) {
            echo "<h2>В истории бронирований пока ничего нет.</h2>";
          } else {
            while ($booking_info = $result_booking->fetch_assoc()) {
              $car_id = $booking_info['_car_id'];
              $branch_id = $booking_info['_branch_id'];
              $date_start = $booking_info['date_start'];
              $date_end = $booking_info['date_end'];

              $datetime_start = new DateTime($date_start);
              $datetime_end = new DateTime($date_end);
              $interval = $datetime_start->diff($datetime_end);
              $num_days = $interval->days;

              $car_sql = "SELECT mark, model, description FROM cars WHERE _car_id = ?";
              $stmt_car = $conn->prepare($car_sql);
              $stmt_car->bind_param("i", $car_id);
              $stmt_car->execute();
              $result_car = $stmt_car->get_result();
              $car_details = $result_car->fetch_assoc();

              $branch_sql = "SELECT name FROM branches WHERE _branch_id = ?";
              $stmt_branch = $conn->prepare($branch_sql);
              $stmt_branch->bind_param("i", $branch_id);
              $stmt_branch->execute();
              $result_branch = $stmt_branch->get_result();
              $branch_details = $result_branch->fetch_assoc();

              echo "
              <section class='booking-item'>
                <div class='card-body'>
                  <div class='heading'>
                    <p class='car-name'>"
                . $car_details['mark']
                . " "
                . $car_details['model']
                . "<p class='car-id'>"
                . $car_id
                . "</p>
                  </div>
                  <p>"
                . $car_details['description']
                . "</p>
                  <img src='./assets/index/cars/"
                . $car_details['mark']
                . " "
                . $car_details['model']
                . ".png' alt='превью машины'>
                </div>
                <div class='book-info'>
                  <div class='group'>
                    <p role='label'>Дата</p>
                    <p>"
                . str_replace('-', '.', $datetime_start->format('d-m-Y'))
                . " - "
                . str_replace('-', '.', $datetime_end->format('d-m-Y'))
                . " ("
                . $num_days
                . " д.)"
                . "</p>
                  </div>
                </div>
                <div class='book-info'>
                  <div class='group'>
                    <p role='label'>Филиал</p>
                    <p>"
                . $branch_details['name']
                . "</p>
                  </div>
                </div>
              </section>
              ";
            }
            $stmt_car->close();
            $stmt_branch->close();
          }
          ?>
        </section>
      </section>
    </main>

    <?php require_once './el-footer.php' ?>
  </body>

</html>