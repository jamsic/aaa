<?php

session_start();
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if (! isset($_SESSION['username']) &&
    ! isset($_SESSION['forename']) &&
    ! isset($_SESSION['surname'])) {
    echo "Для отображения страницы необходима авторизация <a href='index.php'>Щелкните здесь</a>";
   }
else {
  if (!isset($_POST['subject'])) header('Refresh: 1;  http://localhost/hometask/addtask.php');
  $sbj = $_POST['subject'];
  $tsk = $_POST['task'];
  $ddt = $_POST['date'];
  $usrids = $_POST['students'];
  $hometask_id = add_hometask_id($connection, $sbj, $tsk, $ddt);
  add_hometasks_users($connection, $hometask_id, $usrids);
  echo "Домашнее задание '$tsk' по предмету '$sbj' было успешно добавлено следующим студентам:<br>";
  foreach ($usrids as $usrid) {
    $query = "SELECT forename, surname FROM users where id=$usrid";
    $result = $connection->query($query);
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        echo $row[0] . $row[1] . "<br>";
    }
  }
  echo "<a href='alltasks.php'>Вернуться</a>";
  $connection->close();
}

function get_all_tasks($connection) {
  $query = "SELECT * FROM hometasks";
  $result = $connection->query($query);
  return $result;
}

function add_hometask_id($connection, $sbj, $tsk, $ddt)
{
  $query = "INSERT INTO hometasks(subject, task, deadline) VALUES('$sbj', '$tsk', '$ddt')";
  $result = $connection->query($query);
  if (!$result) die($connection->error);
  $id = $connection -> insert_id;
  return $id;
}

function add_hometasks_users($connection, $hmtsk_id, $usrids) {
  foreach ($usrids as $usrid) {
    $query = "INSERT INTO hometask_to_user(user_id, hometask_id, mark) VALUES('$usrid', '$hmtsk_id', '-1')";
    $result = $connection->query($query);
  }
}

function get_student_names($connection, $usrids) {
  //$names = {};
  foreach ($usrids as $usrid) {
    $query = "SELECT forename, surname FROM users where id='$usrid'";
    $result = $connection->query($query);
  }
//names
}
?>
