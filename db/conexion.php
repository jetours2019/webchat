<?php

$host = "localhost";
$db = "jetours1_webchat";
$user = "jetours1_disponibilidad";
$pass = "(!mEkkfrKuS9";
// $user = "admin";//local
// $pass = "12345678";//local

$conexion = $mysqli = new mysqli($host, $user, $pass, $db);

if (mysqli_connect_errno()) {
	echo 'Conexion Fallida : ', mysqli_connect_error();
	exit();
} 