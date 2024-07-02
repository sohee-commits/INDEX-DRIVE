<?php
session_start();

require_once './_config.php';

$sql = "SELECT mark, model, class, price, year, rating FROM cars";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
  // Инициализируем массив для хранения условий фильтрации
  $conditions = [];

  // Фильтруем по условиям, указанным в форме
  // Экранируем и защищаем от SQL инъекций
  if (!empty($_POST['mark']) && $_POST['mark'] !== 'null') {
    // real_escape_string - экранирование и очистка данных, полученных из формы, перед тем как использовать их в SQL запросе
    $mark = $conn->real_escape_string($_POST['mark']);
    $conditions[] = "mark = '$mark'";
  }
  if (!empty($_POST['model']) && $_POST['model'] !== 'null') {
    $model = $conn->real_escape_string($_POST['model']);
    $conditions[] = "model = '$model'";
  }
  if (!empty($_POST['class']) && ($_POST['class'] === 'econom' || $_POST['class'] === 'premium')) {
    $class = $conn->real_escape_string($_POST['class']);
    $conditions[] = "class = '$class'";
  }

  // Если есть условия, добавляем WHERE к SQL запросу
  if (!empty($conditions)) {
    // implode - объединение элементов массива в строку
    $sql .= " WHERE " . implode(" AND ", $conditions);
  }
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

  <?php require_once './el-head.php'; ?>

  <body>
    <div class="summ">

      <?php require_once './el-header.php'; ?>

      <img src="./assets/index/ИНДЕКС ДРАЙВ.png" alt="ИНДЕКС ДРАЙВ превью-текст">
      <div class="search-con">
        <p class="fw-b">На любой случай — <br>любой автомобиль</p>
        <p class="fw-sb">Аренда эконом и премиум автомобилей в Перми онлайн.</p>
        <form action="./index.php" method="POST">
          <div class="group">
            <label for="mark" role="label">Марка</label>
            <select name="mark" id="mark">
              <option value="null" selected>Выбрать</option>
              <?php
              // Получаем марки автомобилей из базы данных и заполняем выпадающий список
              $sql_marks = "SELECT DISTINCT mark FROM cars";
              $result_marks = $conn->query($sql_marks);
              while ($row_marks = $result_marks->fetch_assoc()) {
                echo '<option value="' . $row_marks['mark'] . '">' . $row_marks['mark'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="group">
            <label for="model" role="label">Модель</label>
            <select name="model" id="model">
              <option value="null" selected>Выбрать</option>
              <?php
              // Получаем модели автомобилей из базы данных и заполняем выпадающий список
              $sql_models = "SELECT DISTINCT model FROM cars";
              $result_models = $conn->query($sql_models);
              while ($row_models = $result_models->fetch_assoc()) {
                echo '<option value="' . $row_models['model'] . '">' . $row_models['model'] . '</option>';
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
          <button class="btn-primary" name="search">
            Найти
            <img src="./assets/icons/arrow-right.png" alt="стрелка вправо">
          </button>
        </form>
      </div>
    </div>

    <main>

      <?php
      // Проверяем, была ли отправлена форма и есть ли результаты
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
        echo '<section class="cars">';
        // Выводим отфильтрованные автомобили
        while ($row = $result->fetch_assoc()) {
          echo "
          <section class='car'>
              <img src='./assets/index/cars/" . $row['mark'] . ' ' . $row['model'] . ".png' alt='превью'>
              <div class='car-text-body'>
                  <h4>" . $row['mark'] . ' ' . $row['model'] . "</h4>
                  <div class='qualities'>";
          // Добавляем значок для премиум класса, если автомобиль премиум
          if ($row['class'] == 'premium') {
            echo "<img src='./assets/icons/premium.png' alt='класс: премиум'>";
          }
          echo "
                    <div class='year'>
                        <img src='./assets/icons/year.png' alt='иконка календаря символизирующая год'>
                        <p class='faded-text'>" . $row['year'] . "</p>
                    </div>
                    <div class='rating'>
                        <img src='./assets/icons/rating.png' alt='иконка пустой звезды символизирующая рейтинг'>
                        <p class='faded-text'>" . $row['rating'] . "</p>
                    </div>
                </div>
            </div>
            <div class='car-text-footer'>
                <p class='faded-text'>От</p>
                <p>" . number_format($row['price'], 0, '', ' ') . ' ₽' . "<span class='faded-text'>/ сутки</span></p>
            </div>
        </section>";
        }
        echo '</section>';
      } else {
        // Выводим стандартные секции, если форма поиска не отправлена
        echo '
        <section class="trust">
          <img src="./assets/index/sales.svg"
              alt="доверие: Отмена аренды без штрафов Бесплатно * до 24 часов до старта аренды">
          <img src="./assets/index/payment-transparency.svg"
              alt="акции: Скидки на долгосрочную аренду до 25% * за каждые 3 дня +5%">
        </section>';

        echo '<section class="cars">';
        // Выводим все автомобили
        while ($row = $result->fetch_assoc()) {
          echo "
          <section class='car'>
              <img src='./assets/index/cars/" . $row['mark'] . ' ' . $row['model'] . ".png' alt='превью'>
              <div class='car-text-body'>
                  <h4>" . $row['mark'] . ' ' . $row['model'] . "</h4>
                  <div class='qualities'>";
          // Добавляем значок для премиум класса, если автомобиль премиум
          if ($row['class'] == 'premium') {
            echo "<img src='./assets/icons/premium.png' alt='класс: премиум'>";
          }
          echo "
                    <div class='year'>
                        <img src='./assets/icons/year.png' alt='иконка календаря символизирующая год'>
                        <p class='faded-text'>" . $row['year'] . "</p>
                    </div>
                    <div class='rating'>
                        <img src='./assets/icons/rating.png' alt='иконка пустой звезды символизирующая рейтинг'>
                        <p class='faded-text'>" . $row['rating'] . "</p>
                    </div>
                </div>
            </div>
            <div class='car-text-footer'>
                <p class='faded-text'>От</p>
                <p>" . number_format($row['price'], 0, '', ' ') . ' ₽' . "<span class='faded-text'>/ сутки</span></p>
            </div>
        </section>";
        }
        echo '</section>';
      }
      ?>

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

    <?php require_once './el-footer.php'; // Подключаем футер страницы ?>

  </body>

</html>