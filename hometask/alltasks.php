<!doctype html>
<html>
  <head>
  </head>
  <body>
<?php
session_start();
setlocale(LC_ALL, 'ru_RU.UTF-8');
require_once 'login.php';
$connection =
new mysqli($db_hostname, $db_username, $db_password, $db_database);
if (! isset($_SESSION['username']) &&
    ! isset($_SESSION['forename']) &&
    ! isset($_SESSION['surname'])) {
    echo "Для отображения страницы необходима авторизация <a href='index.php'>Щелкните здесь</a>";
   }
else {
  echo "Список всех заданий";
  $tasks = get_all_tasks($connection);
  echo "<form method='POST' action='addtask.php'><button>Добавить задание</button></form>";
  if ($tasks->num_rows == 0) {
    echo "Список заданий пуст";
  }
  else {
   ?>

<table>
   <caption>Все задания</caption>
   <tr>
    <th>Студенты</th>
    <th>Задание</th>
    <th>Предмет</th>
    <th>Срок сдачи</th>
    <th>Сдано</th>
    <th>Оценка</th>
    <th>Действия</th>
   </tr>
  <?php
    while ($row = $tasks->fetch_array(MYSQLI_NUM)) {
      $students = get_students($connection, $row[0]);
      while ($studentrow = $students->fetch_array(MYSQLI_NUM)) {
        $passed = ($studentrow[3] > -1)? 'Да' : 'Нет';
        $mark = ($studentrow[3] > 0)? $studentrow[3] : 'Нет';
        $action = 'Нет действия';
        if ($studentrow[3] == 0) $action = "<form method='POST' action='givemark.php'><button name='id' value='$row[0]_$studentrow[0]'>Проверить</button></form>";
        if ($studentrow[3] > 0) $action = "<form method='POST' action='givemark.php'><button name='id' value='$row[0]_$studentrow[0]'>Изменить</button></form>";
        echo "<tr><td>" . $studentrow[1] . " " . $studentrow[2] . "</td>
                <td>$row[1]</td>
                <td>$row[2]</td>
                <td>$row[3]</td>
                <td>" . $passed . "</td>
                <td>" . $mark . "</td>
                <td>" . $action . "</td></tr>";
        }
      }
  ?>
  </table>
  <a href='index.php'>Вернуться</a>
  <?php
  }
}
$connection->close();

function get_all_tasks($connection) {
  $query = "SELECT * FROM hometasks";
  $result = $connection->query($query);
  return $result;
}

function get_students($connection, $hmtsid) {
  $query = "SELECT u.id, forename, surname, mark FROM users u JOIN hometask_to_user uh ON u.id=uh.user_id where uh.hometask_id=$hmtsid";
  $result = $connection->query($query);
  return $result;
}
?>
  </body>
  </html>
