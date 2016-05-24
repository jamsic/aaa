<?php

echo "Вы успешно разлогинировались и будете перенаправлены на главную страницу";
destroy_session_and_data();
header('Refresh: 1;  http://localhost/hometask/');

function destroy_session_and_data()
{
session_start();
$_SESSION = array();
setcookie(session_name(), '', time() - 2592000, '/');
session_destroy();
}
?>
