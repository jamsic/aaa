<html>
<head>
</head>
<body>
<?php
print_r($_POST);
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
  if (!isset($_POST['mark']))  header('Refresh: 1;  http://localhost/hometask/givemark.php');
//echo $_SESSION['usr_id'];
//echo $_SESSION['tmtsk_id'];
//echo $_POST['mark'];
  else {
    set_mark($connection, $_SESSION['hmtskid'], $_SESSION['usrid'], $_POST['mark']);
    echo "Оценка " . $_POST['mark'] . " успешно поставлена<br>";
    echo "<a href='alltasks.php'>Назад</a>";
  }
}

$connection->close();

function set_mark($connection, $hmtskid, $usrid, $mark) {
  $query = "UPDATE hometask_to_user SET mark='$mark' WHERE user_id='$usrid' AND hometask_id='$hmtskid'";
  $result = $connection->query($query);
  if (!$result) die($connection->error);
}
?>
</body>
</html>
