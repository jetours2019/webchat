<?php

session_start();
if (!$_SESSION['logged']) {
    header('location: ../login/login.php');
} else {
    if ($_SESSION['user'] != "admin") {
        header('location: ./index.php');
    }
}

require_once "../db/conexion.php";

$id = $_GET['id'];
$asesor_id = $_GET['asesor_id'];
$sql = "DELETE FROM calificaciones WHERE id=$id";
$act = $conexion->query($sql);
header('location: ./calificaciones.php?id='.$asesor_id);

