<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">
  <?php require_once './el-head.php' ?>

  <body>
    <div class="summ">
      <?php require_once './el-header.php' ?>
      <img src="./assets/index/ИНДЕКС ДРАЙВ.png" alt="ИНДЕКС ДРАЙВ превью-текст">
      <div class="search-con">
        <p class="fw-b">На любой случай — <br>любой автомобиль</p>
        <p class="fw-sb">Аренда эконом и премиум автомобилей в Перми онлайн.</p>
        <form action="./search.php" method="POST">
          <div class="group">
            <label for="mark" role="label">Марка</label>
            <select name="mark" id="mark">
              <?php
              require_once './_config.php ';
              $sql = "SELECT mark FROM cars";
              $result = $conn->query($sql);
              $i = 1;

              while ($row = $result->fetch_assoc()) {
                if ($i == 1) {
                  echo '<option value="'
                    . $row['mark']
                    . '" selected>'
                    . $row['mark']
                    . '</option>';
                } else {
                  echo '<option value="'
                    . $row['mark']
                    . '">'
                    . $row['mark']
                    . '</option>';

                }
                $i++;
              }
              ?>
            </select>
          </div>
          <div class="group">
            <label for="model" role="label">Модель</label>
            <select name="model" id="model">
              <?php
              require_once './_config.php ';
              $sql = "SELECT model FROM cars";
              $result = $conn->query($sql);
              $i = 1;

              while ($row = $result->fetch_assoc()) {
                if ($i == 1) {
                  echo '<option value="'
                    . $row['model']
                    . '" selected>'
                    . $row['model']
                    . '</option>';
                } else {
                  echo '<option value="'
                    . $row['model']
                    . '">'
                    . $row['model']
                    . '</option>';

                }
                $i++;
              }
              ?>
            </select>
          </div>
          <div class="group" id="class-con">
            <p role="label">Класс</p>
            <div class="input-group">
              <label for="econom">
                <input type="radio" name="class" id="econom" value="econom">
                <span class="fw-sb">Эконом</span>
              </label>
              <label for="premium">
                <input type="radio" name="class" id="premium" value="premium">
                <span class="fw-sb">Премиум</span>
              </label>
            </div>
          </div>
          <button class="btn-primary">
            Найти
            <img src="./assets/icons/arrow-right.png" alt="стрелка вправо">
          </button>
        </form>
      </div>
    </div>

    <main>

      <section class="trust">
        <img src="./assets/index/sales.svg"
          alt="доверие: Отмена аренды без штрафов Бесплатно * до 24 часов до старта аренды">
        <img src="./assets/index/payment-transparency.svg"
          alt="акции: Скидки на долгосрочную аренду до 25% * за каждые 3 дня +5%">
      </section>

      <section class="cars">
        <!-- динамический вывод машин из бд -->
        <?php
        require_once './_config.php ';
        $sql = "SELECT mark, model, class, price, year, rating FROM cars";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
          echo "
        <section class='car'>
          <img src='./assets/index/cars/"
            . $row['mark']
            . ' '
            . $row['model']
            . ".png' alt='превью'>
          <div class='car-text-body'>
            <h4>"
            . $row['mark']
            . ' '
            . $row['model']
            . "</h4>
            <div class='qualities'>";
          if ($row['class'] == 'premium') {
            echo "<img src='./assets/icons/premium.png' alt='класс: премиум'>";
          }
          echo "
          <div class='year'>
            <img src='./assets/icons/year.png' alt='иконка календаря символизирующая год'>
            <p class='faded-text'>"
            . $row['year']
            . "</p>
          </div>
          <div class='rating'>
            <img src='./assets/icons/rating.png' alt='иконка пустой звезды символизирующая рейтинг'>
            <p class='faded-text'>"
            . $row['rating']
            . "</p>
          </div>
        </div>
        </div>
        <div class='car-text-footer'>
          <p class='faded-text'>От</p>
          <p>";
          echo number_format($row['price'], 0, '', ' ') . ' ₽';
          echo "
        <span class='faded-text'>/ сутки</span></p>
        </div>
      </section>
      ";
        }
        ?>
      </section>

      <section class="news">
        <div class="heading">
          <h2>Новости</h2>
          <a href="#">
            <img src="./assets/icons/arrow-up.png" alt="стрелка вверх: вернуться наверх">
          </a>
        </div>
        <section class="news-list">
          <section class="new" new-data="index-drive-opened">
            <div class="card-body">
              <p class="fw-b">Сайт</p>
              <hr>
              <h3 class="fw-sb">Открытие «Индекс Драйв»</h3>
              <p>Мы рады объявить об открытии новой и энергичной компании по аренде автомобилей - «Индекс Драйв»!</p>
            </div>
            <button class="btn-secondary">
              Подробнее
            </button>
          </section>
          <section class="new" new-data="index-drive-site-opened">
            <div class="card-body">
              <p class="fw-b">Сайт</p>
              <hr>
              <h3 class="fw-sb">Наш сайт официально открыт</h3>
              <p>Мы с радостью сообщаем об открытии нашего официального сайта! Теперь аренда автомобиля стала удобнее.
              </p>
            </div>
            <button class="btn-secondary">
              Подробнее
            </button>
          </section>
        </section>
      </section>
    </main>

    <?php require_once './el-footer.php' ?>
  </body>

</html>