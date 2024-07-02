<?php

require_once '../_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

  // Получение и очистка данных
  $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_STRING);
  $birth = filter_input(INPUT_POST, 'birth', FILTER_SANITIZE_STRING);
  $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
  $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
  $patronymic = filter_input(INPUT_POST, 'patronymic', FILTER_SANITIZE_STRING);
  $pass_series = filter_input(INPUT_POST, 'pass_series', FILTER_SANITIZE_STRING);
  $pass_number = filter_input(INPUT_POST, 'pass_number', FILTER_SANITIZE_STRING);
  $pass_date = filter_input(INPUT_POST, 'pass_date', FILTER_SANITIZE_STRING);
  $pass_authority = filter_input(INPUT_POST, 'pass_authority', FILTER_SANITIZE_STRING);
  $pass_code = filter_input(INPUT_POST, 'pass_code', FILTER_SANITIZE_STRING);
  $password = htmlspecialchars($_POST['password']);
  $password_repeat = htmlspecialchars($_POST['password_repeat']);

  // Проверка совпадения паролей
  if ($password !== $password_repeat) {
    die("Пароли не совпадают.");
  }

  // Проверка, существует ли пользователь с данным номером телефона
  $stmt = $conn->prepare("SELECT _user_id FROM users WHERE tel = ?");
  $stmt->bind_param("s", $tel);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    die("Пользователь с таким номером телефона уже существует.");
  }

  // Закрытие запроса проверки
  $stmt->close();

  // Хеширование пароля
  $password_hashed = password_hash($password, PASSWORD_DEFAULT);

  // Подготовка SQL-запроса с заполнителями
  $stmt = $conn->prepare("INSERT INTO users (tel, birth, first_name, last_name, patronymic, pass_series, pass_number, pass_date, pass_authority, pass_code, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  // Привязка параметров к подготовленному запросу
  $stmt->bind_param("sssssssssss", $tel, $birth, $first_name, $last_name, $patronymic, $pass_series, $pass_number, $pass_date, $pass_authority, $pass_code, $password_hashed);

  // Выполнение запроса и обработка успеха или ошибки
  if ($stmt->execute()) {
    // Начало сессии
    session_start();
    // Сохранение сгенерированного идентификатора пользователя в сессии
    $_SESSION['user_id'] = $stmt->insert_id;
    // Перенаправление на страницу профиля
    header('Location: ../profile.php');
    exit(); // Завершение выполнения скрипта после перенаправления
  } else {
    echo $stmt->error;
  }

  // Закрытие запроса
  $stmt->close();
}

// Закрытие соединения
$conn->close();