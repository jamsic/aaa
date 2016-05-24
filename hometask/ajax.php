<?php
include "login.php";
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if (isset($_POST['action'])) {
  $query = "SELECT AVG(hu.mark), h.subject FROM hometasks h JOIN hometask_to_user hu ON h.id=hu.hometask_id WHERE hu.user_id=" .$_POST['action'] . " AND hu.mark > '0' GROUP BY h.subject";
  $result = $connection->query($query);
  $res = "";
  while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $res = $res . $row[0] . " " . $row[1] . "<br>";
  }
  echo $res;
}
else echo 5;

function select() {
    echo "The select function is called.";
    exit;
}

function insert() {
    echo "The insert function is called.";
    exit;
}
?>
