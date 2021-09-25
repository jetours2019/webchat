<?php

session_start();
#conectar a base de datos 
$level_file = "..";
require_once "$level_file/db/conexion.php";
date_default_timezone_set("America/Bogota");

$id = $_SESSION['id'];
$sql = "UPDATE usuarios SET online=false";

$hora = date('H');
if ($hora >= 18) {
    $act = $conexion->query($sql);
}
