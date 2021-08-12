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
$sql = "DELETE FROM usuarios WHERE id=$id";
$act = $conexion->query($sql);
header('location: ./index.php');

