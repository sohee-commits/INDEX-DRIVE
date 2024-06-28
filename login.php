<?php
session_start();
if (isset($_SESSION["user_id"])) {
  header("Location: profile.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php require_once './el-head.php' ?>

  <body>
    <?php require_once './el-header.php' ?>

    <main>
      <section class="ads">
        <img src="./assets/login/ads.png" alt="Ваш первый шаг к удобной аренде автомобиля. 8 (800) 111-11-11">
      </section>
      <form action="./scripts/login.php" method="POST">
        <h2>Вход</h2>
        <!-- тел пароль -->
        <div class="conc">
          <div class="group">
            <label for="tel" role="label">Номер телефона</label>
            <input required type="tel" name="tel" id="tel" placeholder="8 000 000 00 00" autocomplete="tel"
              minlength=11" max="11">
          </div>
          <div class="group">
            <label for="password" role="label">Пароль</label>
            <input required type="password" name="password" id="password" placeholder="Введите пароль"
              autocomplete="current-password" minlength="8" maxlength="32">
          </div>
        </div>

        <button type="submit" name="login" class="btn-primary">Войти</button>
      </form>
    </main>

    <?php require_once './el-footer.php' ?>
  </body>

</html>