<?php

session_start();
if ($_SESSION['logged']) {
    #conectar a base de datos 
    $level_file = "..";
    require_once "$level_file/db/conexion.php";
    if (array_key_exists('asesor_id', $_GET) && $_GET['asesor_id'] != '' && $_SESSION["user"] == 'admin') {
        $id =  $_GET['asesor_id'];
    } else {
        $id =  $_SESSION['id'];
        $_SESSION['conected'] = false;
    }
    $sql = "UPDATE usuarios SET online=false WHERE id=$id";
    $act = $conexion->query($sql); 
}

header('location: ./index.php');