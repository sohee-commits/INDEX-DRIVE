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
      <?php
      require_once './_config.php';

      $user_id = $_SESSION['user_id'];

      $sql = "SELECT _car_id, _branch_id, date_start, date_end FROM booking WHERE _user_id = ?";
      $stmt = $conn->prepare($sql);
      if ($stmt === false) {
        echo "пустая история";
      } else {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
          echo "пустая история";
        } else {
          while ($row = $result->fetch_assoc()) {
            echo $row['_car_id'] . ' _car_id <br />';
            echo $row['_branch_id'] . ' _branch_id <br />';
            echo $row['date_start'] . ' date_start <br />';
            echo $row['date_end'] . ' date_end <br />';
          }
        }
      }
      ?>

    </main>

    <?php require_once './el-footer.php' ?>
  </body>

</html>