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
    <p>введите данные</p>
    <form action="authenticate.php" method="POST">
      <input name="login"></input>
      <input name="password"></input>
      <button class="btn btn-primary" type="submit">Отправить</button>
    </form>
    </div>
    </div>
  </body>
</html>

<?php // authenticate.php
setlocale(LC_ALL, 'ru_RU.UTF-8');
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
//unset($_SERVER);
if (isset($_POST['login']) && isset($_POST['password'])) {
  $_SERVER['PHP_AUTH_USER'] = $_POST['login'];
  $_SERVER['PHP_AUTH_PW'] = $_POST['password'];
}
if ($connection->connect_error) die($connection->connect_error);
if (isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW']))
{

//echo "k" . $_SERVER['PHP_AUTH_USER'] . "k";
$un_temp = mysql_entities_fix_string($connection,$_SERVER['PHP_AUTH_USER']);
$pw_temp = mysql_entities_fix_string($connection,$_SERVER['PHP_AUTH_PW']);
$query = "SELECT * FROM users WHERE username='$un_temp'";
$result = $connection->query($query);
if (!$result) die($connection->error);
elseif ($result->num_rows)
{
$row = $result->fetch_array(MYSQLI_NUM);
$result->close();
$salt1 = "qm&h*";
$salt2 = "pg!@";
$token = hash('ripemd128', "$salt1$pw_temp$salt2");
if ($token == $row[4]) {
  session_start();
  $_SESSION['username'] = $un_temp;
  $_SESSION['id'] = $row[0];
  $_SESSION['forename'] = $row[1];
  $_SESSION['surname'] = $row[2];
  $_SESSION['isadmin'] = $row[5];
  echo "$row[1] $row[2] : Добро пожаловать, $row[1], под именем '$row[3]'";
  echo $_SESSION['username'];
  echo $_SESSION['forename'];
  echo $_SESSION['surname'];
  echo $_SESSION['isadmin'];
  echo '<a href="index.php">На главную</a>';
}
else echo "Неверная комбинация имя пользователя–пароль";
}
else echo "Неверная комбинация имя пользователя–пароль";
}
/*
else
{
header('WWW-Authenticate: Basic realm="Restricted Section"');
header('HTTP/1.0 401 Unauthorized');
die ("Пожалуйста, введите имя пользователя и пароль");
}
*/
$connection->close();
function mysql_entities_fix_string($connection, $string)
{
return htmlentities(mysql_fix_string($connection, $string));
}
function mysql_fix_string($connection, $string)
{
if (get_magic_quotes_gpc()) $string = stripslashes($string);
return $connection->real_escape_string($string);
}
?>
