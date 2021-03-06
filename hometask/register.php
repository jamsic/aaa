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
    <div class="col-xs-6 col-xs-offset-3">
    <h2>Регистрация нового пользователя</h2>
    <a href='index.php'>Вернуться назад</a>
    <form role="form" action="register.php" method="POST">
      <div class="form-group">
        <label for="email">Имя:</label>
        <input type="text" class="form-control" name="forename">
      </div>

      <div class="form-group">
        <label for="email">Фамилия:</label>
        <input type="text" class="form-control" name="surename">
      </div>

      <div class="form-group">
        <label for="email">Логин:</label>
        <input type="text" class="form-control" name="login">
      </div>

      <div class="form-group">
        <label for="email">Пароль:</label>
        <input type="password" class="form-control" name="password">
      </div>
      <button class="btn btn-primary" type="submit">Отправить</button>
    </form>


<?php // authenticate.php
require_once 'login.php';
setlocale(LC_ALL, 'ru_RU.UTF-8');
$connection =
new mysqli($db_hostname, $db_username, $db_password, $db_database);
//unset($_SERVER);
if (isset($_POST['login']) && isset($_POST['password'])) {
  $_SERVER['PHP_AUTH_USER'] = $_POST['login'];
  $_SERVER['PHP_AUTH_PW'] = $_POST['password'];
}
if ($connection->connect_error) die($connection->connect_error);
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && isset($_POST['surename']) && isset($_POST['forename'])) {

//echo "k" . $_SERVER['PHP_AUTH_USER'] . "k";
  $un_temp = sanitizeMySQL($connection,$_SERVER['PHP_AUTH_USER']);
  $pw_temp = sanitizeMySQL($connection,$_SERVER['PHP_AUTH_PW']);
  $query = "SELECT * FROM users WHERE username='$un_temp'";
  $result = $connection->query($query);
  $result->data_seek(0);
  $rows=$result->num_rows;
  if ($rows > 0) {
    echo "пользователь с таким именем уже существует";
    #echo $rows;
  }
  else {
    $forename = $_POST['forename'];
    $surname = $_POST['surename'];
    $username = $un_temp;
    $password = $pw_temp;
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    $token = hash('ripemd128', "$salt1$password$salt2");
    add_user($connection, $forename, $surname, $username, $token);
    echo "Регистрация прошла успешно";
    echo '<a href="index.php">Назад</a>';
  }
}
/*
else {
  header('WWW-Authenticate: Basic realm="Restricted Section"');
  header('HTTP/1.0 401 Unauthorized');
  die ("Пожалуйста, введите имя пользователя и пароль");
}
*/
$connection->close();

function mysql_entities_fix_string($connection, $string) {
  return htmlentities(mysql_fix_string($connection, $string));
}

function mysql_fix_string($connection, $string) {
  if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $connection->real_escape_string($string);
}

function sanitizeString($var) {
  $var = stripslashes($var);
  $var = htmlentities($var);
  $var = strip_tags($var);
  return $var;
}

function sanitizeMySQL($connection, $var) {
  $var = $connection->real_escape_string($var);
  $var = sanitizeString($var);
  return $var;
}

function add_user($connection, $fn, $sn, $un, $pw)
{
$query = "INSERT INTO users(forename, surname, username, password, isadmin) VALUES('$fn', '$sn', '$un', '$pw', 'false')";
$result = $connection->query($query);
if (!$result) die($connection->error);}
?>

    </div>
    </div>
  </body>
</html>
