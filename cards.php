<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

require_once './_config.php';

// Обработка запроса на удаление карты
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
  $cardId = $_POST['card_id'];
  $userId = $_SESSION['user_id'];

  // Подготовка и выполнение SQL запроса для удаления карты
  $sql = "DELETE FROM cards WHERE _card_id = ? AND _user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $cardId, $userId);

  if ($stmt->execute()) {
    header("Location: cards.php");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}

// Обработка запроса на добавление новой карты
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
  $cardNumber = $_POST['number'];
  $cardCV = $_POST['cv'];
  $cardName = $_POST['name'];
  $cardDate = $_POST['date'];
  $userId = $_SESSION['user_id'];

  // Определение типа карты по первым четырем цифрам номера карты
  $firstDigit = substr($cardNumber, 0, 4);
  $cardType = '';
  switch ($firstDigit) {
    case '1111':
      $cardType = 'Visa';
      break;
    case '2222':
      $cardType = 'MasterCard';
      break;
    case '3333':
      $cardType = 'Мир';
      break;
    default:
      $cardType = 'Unknown';
      break;
  }

  // Подготовка и выполнение SQL запроса для добавления новой карты
  $sql = "INSERT INTO cards (number, cv, card_type, _user_id, name, date) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssiss", $cardNumber, $cardCV, $cardType, $userId, $cardName, $cardDate);

  if ($stmt->execute()) {
    header("Location: cards.php");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php require_once './el-head.php'; ?>

  <body>
    <?php require_once './el-header.php'; ?>

    <main>
      <div class="inner-heading">
        <h1 class="fw-m">Банковские карты</h1>
        <button class="btn-primary" id="add-card">Добавить</button>
      </div>
      <section class="history">
        <?php
        require_once './_config.php';

        $user_id = $_SESSION['user_id'];

        // Получение списка карт пользователя
        $sql = "SELECT _card_id, number, cv, card_type FROM cards WHERE _user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Вывод сообщения, если пользователь еще не добавил ни одной карты
        if ($result->num_rows < 1) {
          echo "
          <h2>У вас нет зарегистрированных карт.</h2>
          ";
        } else {
          // Вывод списка карт пользователя
          while ($cards = $result->fetch_assoc()) {
            echo "
            <section class='item'>
              <form action='cards.php' method='POST'>
                <input required type='hidden' name='card_id' value='" . htmlspecialchars($cards['_card_id']) . "'>
                <div class='heading'>
                  <p>" . htmlspecialchars($cards['card_type']) . "</p> 
                  <p class='card-number' data-full-number='" . htmlspecialchars($cards['number']) . "'>"
              . str_repeat('*', 4) // Первые четыре цифры скрываются символами '*'
              . " "
              . str_repeat('*', 4) // Следующие четыре цифры скрываются символами '*'
              . " "
              . str_repeat('*', 4) // Следующие четыре цифры скрываются символами '*'
              . " "
              . substr($cards['number'], -4) // Отображение последних четырех цифр номера карты
              . "
                  </p>
                  <p id='card-cv'>/ " . htmlspecialchars($cards['cv']) . "</p>
                </div>
                <button class='btn-link red' type='submit' name='delete'>Удалить</button>
              </form>
              <small>Активна</small>
            </section>
            ";
          }
        }

        $stmt->close(); // Закрываем подготовленное выражение 
        $conn->close(); // Закрываем соединение с базой данных
        ?>
      </section>
      <img src="./assets/cards/poster.svg" alt="Индекс Драйв: На любой случай любой автомобиль">
    </main>

    <?php require_once './el-footer.php'; ?>

    <dialog>
      <form action="cards.php" method="POST">
        <div class="group">
          <label for="number" role="label">Номер карты</label>
          <input required type="text" id="number" name="number" placeholder="0000 0000 0000 0000" minlength="16"
            maxlength="20">
        </div>
        <div class="group">
          <label for="name" role="label">
            Имя и фамилия
            <span class="red">латиницей</span>
          </label>
          <input required type="text" id="name" name="name" placeholder="Ivan Ivanov" autocomplete="none" minlength="3"
            maxlength="32">
        </div>
        <div class="group">
          <label for="date" role="label">Срок годности</label>
          <input required type="text" id="date" name="date" placeholder="01 / 01" minlength="5" maxlength="7">
        </div>
        <div class="group">
          <label for="cv" role="label">CVV/CVC</label>
          <input required type="text" id="cv" name="cv" placeholder="123" minlength="3" maxlength="3">
        </div>
        <button type="submit" name="add" class="btn-primary">Добавить</button>
      </form>
    </dialog>

    <script src="./scripts/cards.js"></script>
  </body>

</html>