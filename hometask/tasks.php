<html>
  <head>
    <meta charset="utf-8">
    <title>Система автомаматической проверки домашнего задания</title>
    <link href="bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="mystyles.css" rel="stylesheet">
    <link rel="icon" type="image/ico" href="book.ico">
  </head>
  <body>

  <div class="row row-content">
    <div class="col-xs-8 col-xs-offset-2">
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
  echo "Список домашних заданий пользователя " . $_SESSION['forename'] . " " . $_SESSION['surname'];
  $tasks = get_all_tasks($connection, $_SESSION['id']);
  if ($tasks->num_rows == 0) {
    echo "<br>Список заданий пуст";
  }
  else {
   ?>
   <table class="table">
   <caption>Мои задания</caption>
   <tr>
    <th>Предмет</th>
    <th>Задание</th>
    <th>Срок сдачи</th>
    <th>Сдано</th>
    <th>Оценка</th>
    <th>Действия</th>
   </tr>
   <?php
    while ($row = $tasks->fetch_array(MYSQLI_NUM)) {
        $passed = $row[6] > -1 ? 'Да' : 'Нет';
        $mark = ($row[6] > 0)? $row[6] : 'Нет';
        $action = 'Нет действия';
        if ($row[6] == -1) $action = "<form method='POST' action='completetask.php'><button value='$row[0]' name='id'>Сдать</button></form>";
        echo "<tr><td>" . $row[2] . "</td>
                <td>$row[1]</td>
                <td>$row[3]</td>
                <td>" . $passed . "</td>
                <td>" . $mark . "</td>
                <td>" . $action . "</td></tr>";
    }
  }
}
echo "<a href='index.php'>Назад</a>";
$connection->close();

?>
   </table>
<?php
echo '<button class="button btn btn-primary" name=' . $_SESSION['id'] . '>Посмотреть средние оценки по предметам</button>';
?>

  <div id="avg"></div>

<?php
function get_all_tasks($connection, $id) {
  $query = "SELECT * FROM hometasks h JOIN hometask_to_user hu ON h.id=hu.hometask_id WHERE hu.user_id='$id'";
  $result = $connection->query($query);
  return $result;
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
$(document).ready(function(){
    $('.button').click(function(){
        alert('d');
        id = $(this).attr("name");
        alert(id);
        var ajaxurl = 'ajax.php',
        //alert(clickBtnValue);
        data =  {'action': id};
        alert('o');
        $.post(ajaxurl, data, function (response) {
            // Response div goes here.
            alert("action performed successfully " + response);
            //location.reload(true);
            document.getElementById('avg').innerHTML = response;
        });
    });

});
    </script>
  </div>
  </div>
  </body>
