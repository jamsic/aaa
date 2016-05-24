<html>
<head>
</head>
<body>
<?php
//print_r($_POST);
setlocale(LC_ALL, 'ru_RU.UTF-8');
session_start();
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if (! isset($_SESSION['username']) &&
    ! isset($_SESSION['forename']) &&
    ! isset($_SESSION['surname'])) {
    echo "Для отображения страницы необходима авторизация <a href='index.php'>Щелкните здесь</a>";
   }
else {
  submit_task($connection, $_SESSION['id'], $_POST['hmtskid'], $_POST['submitted']);
  echo "Задание успешно отправлено!<br>";
  echo "<a href='tasks.php'>Вернуться к списку заданий</a>";
}

$connection->close();

function submit_task($connection, $usrid, $hmtskid, $submitted) {
  $query = "UPDATE hometask_to_user SET mark='0', submitted='$submitted' WHERE mark='-1' AND user_id='$usrid' AND hometask_id='$hmtskid'";
  $result = $connection->query($query);
  if (!$result) die($connection->error);
}
?>
</body>
</html>
