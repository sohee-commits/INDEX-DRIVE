<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

  <?php require_once './el-head.php'; ?>

  <body>
    <?php require_once './el-header.php'; ?>

    <main>
      <div class="inner-heading">
        <h1 class="fw-m">Филиалы</h1>
        <p>Найдите ближайший филиал и выберите идеальный автомобиль – удобно, быстро, рядом с вами.</p>
      </div>
      <section class="maps">
        <?php
        require_once './_config.php';

        $maps_sql = "SELECT name, _car_id_1, _car_id_2, _car_id_3 FROM branches";
        $maps_result = $conn->query($maps_sql);

        $cars = [];

        $cars_sql = "SELECT _car_id, mark, model, class, price, year, rating FROM cars";
        $cars_result = $conn->query($cars_sql);

        while ($car_row = $cars_result->fetch_assoc()) {
          $cars[$car_row['_car_id']] = $car_row;
        }

        while ($maps_row = $maps_result->fetch_assoc()) {
          echo "<section class='map open-cars'>";
          echo "<address><p>"
            . $maps_row['name']
            . "</p>"
            . "<img src='./assets/branches/"
            . $maps_row['name']
            . ".png' alt='филиал на карте'></address>";
          echo "<img src='assets/icons/arrow-down.png' alt='посмотреть доступные в филиале машины'>";

          // Start cars section
          echo "<section class='cars hidden'>";
          foreach (['_car_id_1', '_car_id_2', '_car_id_3'] as $car_id_field) {
            $car_id = $maps_row[$car_id_field];
            if (isset($cars[$car_id])) {
              $car = $cars[$car_id];
              echo "<section class='car'>";
              echo "<img src='./assets/index/cars/" . $car['mark'] . " " . $car['model'] . ".png' alt='превью'>";
              echo "<div class='car-text-body'>";
              echo "<h4>" . $car['mark'] . " " . $car['model'] . "</h4>";
              echo "<div class='qualities'><img src='./assets/icons/premium.png' alt='класс: премиум'>";
              echo "<div class='year'>";
              echo "<img src='./assets/icons/year.png' alt='иконка календаря символизирующая год'>";
              echo "<p class='faded-text'>" . $car['year'] . "</p>";
              echo "</div>";
              echo "<div class='rating'>";
              echo "<img src='./assets/icons/rating.png' alt='иконка пустой звезды символизирующая рейтинг'>";
              echo "<p class='faded-text'>" . $car['rating'] . "</p>";
              echo "</div>";
              echo "</div>";
              echo "</div>";
              echo "<div class='car-text-footer'>";
              echo "<p class='faded-text'>От</p>";
              echo "<p>" . number_format($car['price'], 0, '', ' ') . " ₽<span class='faded-text'>/ сутки</span></p>";
              echo "</div>";
              echo "</section>";
            }
          }
          // Close cars section
          echo "</section>";
          // Close map section
          echo "</section>";
        }

        $conn->close();
        ?>
      </section>


    </main>

    <?php require_once './el-footer.php'; ?>

    <script src="./scripts/branches.js"></script>

  </body>

</html>