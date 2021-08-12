<?php

session_start();
if ($_SESSION['logged']) {
    #conectar a base de datos 
    $level_file = "..";
    require_once "$level_file/db/conexion.php";

    $id = $_SESSION['id'];
    $sql = "UPDATE usuarios SET online=false, clients=0 WHERE id=$id";
    $act = $conexion->query($sql); 
}
session_destroy();

header('location: ./login.php');