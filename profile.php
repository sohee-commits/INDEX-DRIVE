<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php require_once './el-head.php' ?>

  <body>
    <?php require_once './el-header.php' ?>

    <main>
      профиль
    </main>

    <?php require_once './el-footer.php' ?>
  </body>

</html>