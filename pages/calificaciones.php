<?php

function format_date($date_str)
{
    $fecha = explode("-", substr($date_str, 0, 10));
    $fecha = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
    $hora = substr($date_str,10, 19);
    return $fecha . " " . $hora;
}

array_key_exists('id', $_GET) or die('Error en los parametros');

$asesor_id = $_GET['id'];
session_start();
require_once "../db/conexion.php";

if (!$_SESSION['logged']) {
    header('location: ./login.php');
    exit;
}

$user = $_SESSION['user'];
if ($user != 'admin') {
    header('location: ./index.php');
    exit;
}
$name = $_SESSION['fullname'];
$query_asesor = "SELECT u.fullname, AVG(c.calificacion) as promedio, COUNT(c.id) as cantidad_calificaciones
FROM usuarios u
LEFT JOIN calificaciones c on u.id = c.id_usuario
WHERE u.id = " . $_GET['id'] . "
GROUP BY u.fullname";
$consulta = mysqli_query($conexion, $query_asesor) or die(mysqli_error($conexion));
$registro = mysqli_fetch_array($consulta);
if (!$registro) {
    header('location: ./index.php');
    exit;
}
$asesor = $registro['fullname'];
$promedio = round($registro['promedio'], 2);
$cantidad_calificaciones = $registro['cantidad_calificaciones'];
$tbody = "";
$query_calificaciones = "SELECT * FROM calificaciones c WHERE c.id_usuario = " . $_GET['id'];
$consulta = mysqli_query($conexion, $query_calificaciones) or die(mysqli_error($conexion));
while ($registro = mysqli_fetch_array($consulta)) {

    $id = $registro['id'];
    $calificacion = $registro['calificacion'];
    $comentario = $registro['comentario'];
    $date = $registro['date'];
    $tbody .= "<tr>";
    $tbody .= "<td>" . $id . "</td>";
    $tbody .= "<td>" . $calificacion . "</td>";
    $tbody .= "<td>" . format_date($date). "</td>";
    $tbody .= "<td>" . $comentario . "</td>";
    $tbody .= " <td>
    <a title='Eliminar' href='#' onclick='confirm_delete($(this))' data-id='$id' data-asesor_id='$asesor_id'><i class='erase fas fa-trash'></i></a>
</td>";
    $tbody .= "</tr>";
}

?>


<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <!-- Required meta tags -->

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="../assets/css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/png" href="../assets/images/iconlogo.png" />

    <title>WebChat Usuarios</title>

</head>
<style>
    .navbar-dark {
        background-color: #214482;
        background-image: linear-gradient(180deg, #214582 10%, #224abe 100%);
        background-size: cover;
    }

    body {
        padding: 50px 0px 0px 0px;
        z-index: 0;
    }

    .row-content {
        margin: 0px auto;
        padding: 50px 0px;
        border-bottom: 1px ridge;
        min-height: 400px;
    }

    .color-red {
        color: red;
    }

    .color-green {
        color: green;
    }
</style>

<body>
    <nav class="navbar navbar-dark navbar-expand-sm fixed-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Navbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="../assets/images/Aliados.png" height="45" width="140"></a>
            <div class="collapse navbar-collapse" id="Navbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active"><a class="nav-link" href="#"><?php echo $name; ?></a></li>
                </ul>
                <span class="navbar-text">
                    <!-- <a data-toggle="modal" data-target="#loginModal"> -->
                    <a href="./logout.php" class="openModal">
                        <i class="fa fa-sign-in"></i> Desconectar y Cerrar Sesión
                    </a>
                </span>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row pt-5">
            <div class="col-md- offset-md-2">
                <h5>Calificaciones del Asesor@
                    <?php echo $asesor; ?>
                    <br>
                    # Calificaciones:
                    <?php echo $cantidad_calificaciones; ?>

                    <br>
                    Promedio:
                    <?php echo $promedio; ?>
                </h5>
            </div>
            <div class="col-md-2 offset-md-2">
                <h5><a href="./index.php"> <i class="fas fa-step-backward"></i> Volver</a></h5>
            </div>
        </div>
        <div class="row row-content">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>
                                Calificación
                            </th>
                            <th>
                                Fecha
                            </th>
                            <th>
                                Comentario
                            </th>
                            <th>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tbody; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script>
        function confirm_delete(button) {

            var id = button.attr('data-id');
            var asesor_id = button.attr('data-asesor_id');
            Swal.fire({
                title: '¿Seguro desea borrar la calificación con ID ' + id + '?',
                text: "Una vez borrada del servidor no se podrá recuperar",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Borrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = "./borrarCalificacion.php?id=" + id + "&asesor_id=" + asesor_id;
                }
            })
        }
    </script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
