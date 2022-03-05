<?php

require_once "../db/conexion.php";

$query = "SELECT * FROM usuarios WHERE username != 'admin' AND online=false";
$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
$users = array();
while ($registro = mysqli_fetch_array($consulta)) {
    $users[] = array(
        'fullname' => $registro['fullname'],
        'email' => $registro['email'],
    );
}

print_r(json_encode($users));