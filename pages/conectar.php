<?php

session_start();
if ($_SESSION['logged']) {
    #conectar a base de datos 
    $level_file = "..";
    require_once "$level_file/db/conexion.php";

    $id = $_SESSION['id'];
    $sql = "UPDATE usuarios SET online=true WHERE id=$id";
    $act = $conexion->query($sql);
    $_SESSION['conected'] = true; 
}

header('location: ./index.php');