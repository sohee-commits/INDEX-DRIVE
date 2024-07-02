<?php
session_start(); // Начало сессии для работы с переменными сессии

// Debugging: Проверяем, что сессия начата и метод запроса POST
if (!isset($_SESSION)) {
  echo 'Сессия не начата.';
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Проверяем, была ли нажата кнопка выхода (logout)
  if (isset($_POST['logout'])) {
    // Очищаем все данные сессии
    $_SESSION = array();
    session_destroy();

    // Перенаправляем пользователя на страницу входа (login.php)
    header("Location: ../login.php");
    exit;
  } else {
    echo 'Кнопка выхода не была нажата.';
  }
} else {
  echo 'Метод запроса не POST.';
}
?>
<?php
session_start(); // Начало сессии для работы с переменными сессии

// Debugging: Проверяем, что сессия начата и метод запроса POST
if (!isset($_SESSION)) {
  echo 'Сессия не начата.';
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Проверяем, была ли нажата кнопка выхода (logout)
  if (isset($_POST['logout'])) {
    // Очищаем все данные сессии
    $_SESSION = array();
    session_destroy();

    // Перенаправляем пользователя на страницу входа (login.php)
    header("Location: ../login.php");
    exit;
  } else {
    echo 'Кнопка выхода не была нажата.';
  }
} else {
  echo 'Метод запроса не POST.';
}
?>