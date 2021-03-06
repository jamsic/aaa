<html>
  <head>
    <meta charset="utf-8">
    <title>Система автоматической проверки домашнего задания</title>
    <link href="bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="mystyles.css" rel="stylesheet">
    <link rel="icon" type="image/ico" href="book.ico">
  </head>
  <body>
  <header class="jumbotron">
    <div class="container">
      <div class="row row-header">
        <div class="col-xs-12 col-sm-9">
          <h2>Система автоматической проверки домашнего задания</h2>
        </div>
      </div>
    </div>
  </header>

  <div class="row row-content">
    <div class="col-xs-8 col-xs-offset-2">
    <?php
      session_start();
      setlocale(LC_ALL, 'ru_RU.UTF-8');
      if (! isset($_SESSION['username']) &&
          ! isset($_SESSION['forename']) &&
          ! isset($_SESSION['surname'])) {
    echo '<form action="authenticate.php" method="POST">
      <button class="btn btn-primary" name="signin">Войти</button>
    </form>
    <form action="register.php" method="POST">
      <button class="btn btn-primary" name="register">Зарегистрироваться</button>
    </form>';
      }
      else {
        echo "Добро пожаловать, " . $_SESSION['forename'] ."<br>";
        if ($_SESSION['isadmin'] == 0) {
          echo "<a href='tasks.php'>К списку заданий</a>";
        }
        if ($_SESSION['isadmin'] == 1) {
          echo "<a href='alltasks.php'>К списку заданий</a>";
        }
        echo '<form action="unauthenticate.php" method="POST">
      <button class="btn btn-primary" name="signout">Выйти</button>
    </form>';
      }

function destroy_session_and_data()
{
session_start();
$_SESSION = array();
setcookie(session_name(), '', time() - 2592000, '/');
session_destroy();
}
?>
      </div>
    </div>
  </body>
</html>
