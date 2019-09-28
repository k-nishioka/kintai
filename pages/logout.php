<?php

session_start();
$_SESSION = array();
session_destroy();
header("Location: /attendance_management/pages/login.php")

?>
