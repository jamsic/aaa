<?php
session_start();
require_once 'login.php';
$connection =
new mysqli($db_hostname, $db_username, $db_password, $db_database);
if (! isset($_SESSION['username']) &&
    ! isset($_SESSION['forename']) &&
    ! isset($_SESSION['surname'])) {
    echo "Для отображения страницы необходима авторизация <a href='index.php'>Щелкните здесь</a>";
   }
else {
  echo "Список домашних заданий пользователя " . $_SESSION['forename'] . " " . $_SESSION['surname'];
}
$connection->close();
?>
