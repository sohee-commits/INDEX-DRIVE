<?php
require_once '../_config.php';

if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
  // Получаем данные из формы и защищаем их от HTML и JavaScript инъекций
  $tel = htmlspecialchars($_POST['tel']);
  $password = htmlspecialchars($_POST['password']);

  // Подготовка запроса к базе данных для получения ID пользователя и его пароля
  $stmt = $conn->prepare("SELECT _user_id, password FROM users WHERE tel = ?");
  $stmt->bind_param("s", $tel);

  // Выполнение запроса и извлечение результата
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  // Проверка наличия пользователя в базе данных
  if ($user) {
    // Проверка соответствия введенного пароля хэшированному паролю в базе данных
    if (password_verify($password, $user['password'])) {
      // Начало сессии для авторизации пользователя
      session_start();
      $_SESSION["user_id"] = $user["_user_id"];
      header("Location: ../profile.php"); // Перенаправление на профиль пользователя в случае успешной авторизации
      die();
    } else {
      // Вывод страницы с сообщением о неверном пароле
      echo '
      <!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link rel="shortcut icon" href="./assets/logo/IXD-black.svg" type="image/x-icon">
          <title>ИНДЕКС ДРАЙВ | Ошибка входа | Аренда эконом и премиум автомобилей в Перми онлайн
          </title>
          <link rel="stylesheet" href="../css/main.css">
          <link rel="preconnect" href="https://fonts.googleapis.com">
          <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
          <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet">
        </head>

        <body>
          <header>
            <a href="./index.php">
              <img src="../assets/logo/IXD-black.svg" alt="логотип компании ИНДЕКС ДРАЙВ - IXD">
            </a>
            <nav>
              <ul>
                <li><a href="../booking.php">Бронирование</a></li>
                <li><a href="../branches.php">Филиалы</a></li>
                <li><a href="../index.php#trust">Акции</a></li>
              </ul>
            </nav>
            <menu>
              <button class="btn-link"><a href="../login.php">Вход</a></button>
              <button class="btn-secondary"><a href="../register.php">Регистрация</a></button>
            </menu>
          </header>

          <main>
            <h1>Неверный пароль или логин.</h1>
            <button class="btn-primary">
              <a href="../login.php">
                Попробовать войти снова
              </a>
            </button>
            <button class="btn-secondary">
              <a href="../register.php">Зарегистрироваться</a>
            </button>
          </main>

          <?php require_once "../el-footer.php" ?>
        </body>

      </html>
      ';
    }
  } else {
    // Вывод страницы с сообщением о том, что пользователя с таким номером телефона нет
    echo '
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="./assets/logo/IXD-black.svg" type="image/x-icon">
        <title>ИНДЕКС ДРАЙВ | Ошибка входа | Аренда эконом и премиум автомобилей в Перми онлайн
        </title>
        <link rel="stylesheet" href="../css/main.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
      </head>

      <body>
        <header>
          <a href="./index.php">
            <img src="../assets/logo/IXD-black.svg" alt="логотип компании ИНДЕКС ДРАЙВ - IXD">
          </a>
          <nav>
            <ul>
              <li><a href="../booking.php">Бронирование</a></li>
              <li><a href="../branches.php">Филиалы</a></li>
              <li><a href="../index.php#trust">Акции</a></li>
            </ul>
          </nav>
          <menu>
            <button class="btn-link"><a href="../login.php">Вход</a></button>
            <button class="btn-secondary"><a href="../register.php">Регистрация</a></button>
          </menu>
        </header>

        <main>
          <h1>Такого пользователя не существует!</h1>
          <button class="btn-primary">
            <a href="../register.php">
              Зарегистрироваться
            </a>
          </button>
          <button class="btn-secondary">
            <a href="../login.php">Попробовать войти снова</a>
          </button>
        </main>

        <?php require_once "../el-footer.php" ?>
      </body>

    </html>
    ';
  }

  // Внедрение стилей непосредственно в случае вывода ошибки
  echo '
  <style>
    main {
      margin: 0 auto;
    }

    main > * {
      margin: var(--sz-s);
    }

    .btn-primary a {
      color: var(--white); 
    }
  </style>
  ';

  $stmt->close();
}

// Закрытие соединения с базой данных
$conn->close();
?>