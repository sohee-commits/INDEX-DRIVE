<header>
  <!-- динамический выбор логотипа в зависимости от текущей страницы -->
  <a href="./index.php">
    <img src="./assets/logo/<?php echo
      basename($_SERVER['PHP_SELF']) == 'index.php'
      ? 'IXD-white.svg'
      : 'IXD-black.svg'; ?>" alt="логотип компании ИНДЕКС ДРАЙВ - IXD">
  </a>
  <!-- навигация -->
  <nav>
    <ul>
      <li><a href="./booking.php">Бронирование</a></li>
      <li><a href="./branches.php">Филиалы</a></li>
      <li><a href="./index.php#trust">Акции</a></li>
    </ul>
  </nav>
  <menu>
    <?php
    if (isset($_SESSION["user_id"])) {
      echo '
      <button class="btn-secondary">
        <a href="./profile.php">Профиль</a>
      </button>';
    } else {
      echo '
      <button class="btn-link">
        <a href="./login.php">Вход</a>
      </button>
      <button class="btn-secondary">
        <a href="./register.php">Регистрация</a>
      </button>
      ';
    }
    ?>
  </menu>
</header>