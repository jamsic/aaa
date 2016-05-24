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
    ?>
    Добавить задание
    <form action="taskadded.php" method="POST">
              <div class="form-group">
                <label for="subject" class="col-sm-3 control-label" align="right">Предмет</label>
                <div class="col-sm-9">
                  <div class="has-feedback col-sm-10" style="padding: 0">
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Предмет" required>
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="task" class="col-sm-3 control-label"  align="right">Задание</label>
                <div class="col-sm-9">
                  <div class="has-feedback col-sm-10" style="padding: 0">
                    <textarea class="form-control" id="task" name="task" required></textarea>
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="date" class="col-sm-3 control-label"  align="right">Срок сдачи</label>
                <div class="col-sm-9">
                  <div class="has-feedback col-sm-10" style="padding: 0">
                    <input type="date" class="form-control" id="date" name="date" required>
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                  </div>
                </div>
              </div>

   <p><b>Кому назначить задание?</b></p>
   <p><input type="checkbox" onclick="toggle(this);">Всем<Br>
   <?php
   $users = get_all_students($connection);
   while ($row = $users->fetch_array(MYSQLI_NUM)) {
      //for ($j = 0; $j < count($row); ++$j) {
        echo "<input type='checkbox' name='students[]' value='$row[0]'>$row[1] $row[2]<Br>";
      //}
    }
   ?>
      <button type="submit">s</button>
    </form>
  </body>
  <script>
    function toggle(source) {
      checkboxes = document.getElementsByName('students[]');
      for(var i=0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
      }
    }
  </script>
</html>

<?php
$connection->close();

function get_all_students($connection) {
  $query = "SELECT id, surname, forename FROM users WHERE isadmin='false'";
  $result = $connection->query($query);
  return $result;
}
?>
