<?php // setupusers.php
require_once 'login.php';
$connection =
new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($connection->connect_error) die($connection->connect_error);
$query = "CREATE TABLE users (
id int NOT NULL AUTO_INCREMENT,
forename VARCHAR(32) NOT NULL,
surname VARCHAR(32) NOT NULL,
username VARCHAR(32) NOT NULL UNIQUE,
password VARCHAR(32) NOT NULL,
isadmin BOOL NOT NULL,
PRIMARY KEY (id)
)";
$result = $connection->query($query);
if (!$result) die($connection->error);
$salt1 = "qm&h*";
$salt2 = "pg!@";
$forename = 'Bill';
$surname = 'Smith';
$username = 'bsmith';
$password = 'mysecret';
$token = hash('ripemd128', "$salt1$password$salt2");
add_user($connection, $forename, $surname, $username, $token);
$forename = 'Pauline';
$surname = 'Jones';
$username = 'pjones';
$password = 'acrobat';
$token = hash('ripemd128', "$salt1$password$salt2");
add_user($connection, $forename, $surname, $username, $token);
function add_user($connection, $fn, $sn, $un, $pw)
{
$query = "INSERT INTO users(forename, surname, username, password, isadmin) VALUES('$fn', '$sn', '$un', '$pw', 'false')";
$result = $connection->query($query);
if (!$result) die($connection->error);}
?>
