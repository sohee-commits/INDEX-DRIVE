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
      <form action="./scripts/register.php" method="POST">
        <h2>Регистрация</h2>
        <!-- тел и др -->
        <div class="conc">
          <div class="group">
            <label for="tel" role="label">Номер телефона</label>
            <input required type="tel" name="tel" id="tel" placeholder="8 000 000 00 00" autocomplete="tel"
              minlength=11" max="11">
          </div>
          <div class="group">
            <label for="birth" role="label">Дата рождения</label>
            <input required type="text" name="birth" id="birth" placeholder="01.01.2000" minlength=10" max="10">
          </div>
        </div>
        <!-- фио -->
        <div class="conc">
          <div class="group">
            <label for="first_name" role="label">Имя</label>
            <input required type="text" name="first_name" id="first_name" placeholder="Иван" autocomplete="given-name"
              minlength=2">
          </div>
          <div class="group">
            <label for="last_name" role="label">Фамилия</label>
            <input required type="text" name="last_name" id="last_name" placeholder="Иванов" autocomplete="family-name">
          </div>
          <div class="group">
            <label for="patronymic" role="label">Отчество</label>
            <input required type="text" name="patronymic" id="patronymic" placeholder="Иванович">
          </div>
        </div>
        <!-- папорт серия номер дв -->
        <div class="conc">
          <div class="group">
            <label for="pass_series" role="label">Серия</label>
            <input required type="number" name="pass_series" id="pass_series" placeholder="0000" minlength="4"
              maxlength="4">
          </div>
          <div class="group">
            <label for="pass_number" role="label">Номер</label>
            <input required type="number" name="pass_number" id="pass_number" placeholder="000000" minlength="6"
              maxlength="6">
          </div>
          <div class="group">
            <label for="pass_date" role="label">Дата выдачи</label>
            <input required type="text" name="pass_date" id="pass_date" placeholder="01.01.2014" minlength="8"
              maxlength="10">
          </div>
        </div>
        <!-- паспорт где выдали и код -->
        <div class="conc">
          <div class="group">
            <label for="pass_authority" role="label">Наименование выдавшего органа</label>
            <input required type="text" name="pass_authority" id="pass_authority"
              placeholder="ГУ МВД по Пермскому краю">
          </div>
          <div class="group">
            <label for="pass_code" role="label">Код подразделения</label>
            <input required type="text" name="pass_code" id="pass_code" placeholder="000-000" minlength="7"
              maxlength="8">
          </div>
        </div>
        <!-- пароль -->
        <div class="group">
          <label for="password" role="label">Пароль</label>
          <input required type="password" name="password" id="password" placeholder="Введите пароль"
            autocomplete="new-password" minlength="8" maxlength="32">
        </div>
        <!-- повтор пароля -->
        <div class="group">
          <label for="password_repeat" role="label">Повтор пароля</label>
          <input required type="password" name="password_repeat" id="password_repeat"
            placeholder="Введите пароль повторно" autocomplete="new-password" minlength="8" maxlength="32">
        </div>
        <button type="submit" name="register" class="btn-primary">Зарегистрироваться</button>
      </form>
      <section class="ads">
        <img src="./assets/register/ads.png" alt="Ваш первый шаг к удобной аренде автомобиля. 8 (800) 111-11-11">
      </section>
    </main>

    <?php require_once './el-footer.php' ?>
  </body>

</html>