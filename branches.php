<?php
session_start();
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

        // Запрос для получения данных о филиалах
        $maps_sql = "SELECT name, _car_id_1, _car_id_2, _car_id_3 FROM branches";
        $maps_result = $conn->query($maps_sql);

        $cars = [];

        // Запрос для получения данных о всех автомобилях
        $cars_sql = "SELECT _car_id, mark, model, class, price, year, rating FROM cars";
        $cars_result = $conn->query($cars_sql);

        // Заполнение массива $cars данными об автомобилях для последующего использования
        while ($car_row = $cars_result->fetch_assoc()) {
          $cars[$car_row['_car_id']] = $car_row;
        }

        $nameTranslations = array(
          "г. Пермь, Комсомольский просп., д. 79" => "79-Komsomolsky-Prospekt-Perm",
          "г. Пермь, ул. Монастырская, д. 11" => "11-Monastyrskaya St-Perm",
          "г. Пермь, ул. Петропавловская, д. 25 а" => "25a-Petropavlovskaya-St-Perm",
          "г. Пермь, ул. Ленина, д. 53" => "53-Lenina-St-Perm.",
          "г. Пермь, ул. Пушкина, д. 107 а" => "107a-Pushkina-St-Perm",
        );

        // Цикл по результатам запроса филиалов для вывода информации о каждом филиале
        while ($maps_row = $maps_result->fetch_assoc()) {
          echo "<section class='map open-cars'>";
          echo "<address><p>"
            . $maps_row['name']
            . "</p>"
            . "<img loading='lazy' src='./assets/branches/"
            // . $maps_row['name']
            . $nameTranslations[$maps_row['name']]
            . ".png' alt='филиал на карте'></address>";
          echo "<img loading='lazy' src='assets/icons/arrow-down.png' alt='посмотреть доступные в филиале машины'>";

          // Начало секции вывода автомобилей в филиале
          echo "<section class='cars hidden'>";
          foreach (['_car_id_1', '_car_id_2', '_car_id_3'] as $car_id_field) {
            $car_id = $maps_row[$car_id_field];
            if (isset($cars[$car_id])) {
              $car = $cars[$car_id];
              echo "<section class='car'>";
              echo "<img loading='lazy' src='./assets/index/cars/" . $car['mark'] . " " . $car['model'] . ".png' alt='превью'>";
              echo "<div class='car-text-body'>";
              echo "<h4>" . $car['mark'] . " " . $car['model'] . "</h4>";
              echo "<div class='qualities'><img loading='lazy' src='./assets/icons/premium.png' alt='класс: премиум'>";
              echo "<div class='year'>";
              echo "<img loading='lazy' src='./assets/icons/year.png' alt='иконка календаря символизирующая год'>";
              echo "<p class='faded-text'>" . $car['year'] . "</p>";
              echo "</div>";
              echo "<div class='rating'>";
              echo "<img loading='lazy' src='./assets/icons/rating.png' alt='иконка пустой звезды символизирующая рейтинг'>";
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
          // Закрытие секции вывода автомобилей в филиале
          echo "</section>";
          // Закрытие секции филиала на карте
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