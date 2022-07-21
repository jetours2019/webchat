<?php

session_start();

if (!array_key_exists('ces', $_POST) || 
    !array_key_exists('da', $_POST) || 
    !array_key_exists('id_asesor', $_POST) ||
    !array_key_exists('comentario', $_POST) ||
    !array_key_exists('calificacion', $_POST)
    ) {
    header("location:javascript://history.go(-1)");
    $_SESSION['error'] = true;
    exit;
}

require_once "../db/conexion.php";
$calificacion = $_POST['calificacion'];
$id_asesor = $_POST['id_asesor'];
$comentario = $_POST['comentario'];
$date = $_POST['da'];

$query_asesor = "SELECT * FROM calificaciones WHERE id_usuario='" . $id_asesor . "' AND date='" . $date . "'";
$consulta = mysqli_query($conexion, $query_asesor) or die(mysqli_error($conexion));
$registro = mysqli_fetch_array($consulta);

if(!$registro){
    $query_insert = "INSERT INTO calificaciones (id_usuario, calificacion, comentario, date) VALUES ('" . $id_asesor . "', " . $calificacion . ", '" . $comentario . "', '" . $date . "')";
    $consulta = mysqli_query($conexion, $query_insert) or die(mysqli_error($conexion));
}

$_SESSION['saved'] = true;
header("location: index.php?ces=".$_POST['ces']."&da=".$_POST['da']);
