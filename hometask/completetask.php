<html>
<head>
</head>
<body>
<?php
session_start();
setlocale(LC_ALL, 'ru_RU.UTF-8');
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if (! isset($_SESSION['username']) &&
    ! isset($_SESSION['forename']) &&
    ! isset($_SESSION['surname'])) {
    echo "Для отображения страницы необходима авторизация <a href='index.php'>Щелкните здесь</a>";
   }
else {
  if (! isset($_POST['id'])) header('Refresh: 1;  http://localhost/hometask/tasks.php');
  echo $_SESSION['forename'] . " " . $_SESSION['surname'] . "<br>";
  $task = get_task($connection, $_POST['id'], $_SESSION['id']);
  $row = $task->fetch_array(MYSQLI_NUM);
  $hmtsk = $_POST['id'];
  echo $hmtsk;
  echo "Сдать задание<br>";
  echo "Предмет: " . "$row[2]<br>";
  echo "Задание: " . "$row[1]<br>";
  ?>

  Введите решение
  <form action="taskcompleted.php" method="POST">
  <textarea required name="submitted"></textarea>
  <?php echo "<button name='hmtskid' value=" . $hmtsk . ">Сдать</button>"; ?>
  </form>
  <a href="tasks.php">Вернуться к списку заданий</a>
  <?php
}

$connection->close();

function get_task($connection, $hmtskid, $usrid) {
  $query = "SELECT * FROM hometasks h JOIN hometask_to_user hu ON h.id=hu.hometask_id WHERE hu.user_id='$usrid' and hu.hometask_id=$hmtskid";
  $result = $connection->query($query);
  return $result;
}
?>
</body>
</html>
