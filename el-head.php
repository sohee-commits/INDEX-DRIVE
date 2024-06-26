<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- икнока вкладки -->
  <link rel="shortcut icon" href="./assets/logo/IXD-black.svg" type="image/x-icon">
  <!-- динамическое формирование названия вкладки -->
  <title>ИНДЕКС ДРАЙВ |
    <?php
    $currentFileName = basename($_SERVER['PHP_SELF']);
    $expectedFileNames = [
      'index.php' => 'Главная',
      'login.php' => 'Вход',
      'register.php' => 'Регистрация',
      'profile.php' => 'Профиль',
      'booking.php' => 'Бронирование автомобиля',
      'history.php' => 'История автомобиля',
      'branches.php' => 'Филиалы',
      'cards.php' => 'Банковские карты',
    ];
    // если страница в массиве не найдена выведет пустую строку
    echo $expectedFileNames[$currentFileName] ?? '';
    ?> | Аренда эконом и премиум автомобилей в Перми онлайн
  </title>
  <!-- динамическое подключение соответствующего файла стилей -->
  <link rel="stylesheet" href="./css/<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>.css">
  <!-- шрифты -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- основной -->
  <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
</head>