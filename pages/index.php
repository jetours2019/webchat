<?php
session_start();
require_once "../db/conexion.php";

if (!$_SESSION['logged']) {
    header('location: ./login.php');
}

$user = $_SESSION['user'];
$name = $_SESSION['fullname'];
$btnConexion = $_SESSION['conected'] ? '<a class="nav-link" href="./desconectar.php"><i class="fas fa-sign-out-alt pr-2"></i></i>Desconectar</a>' : '<a class="nav-link" href="./conectar.php"><i class="fas fa-sign-in-alt pr-2"></i>Conectar</a>';

$query = "SELECT * 
          FROM usuarios 
          WHERE username != 'admin'
          order by online desc";

$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
$tbody = "";
while ($registro = mysqli_fetch_array($consulta)) {

    $id = $registro['id'];
    $fullname = $registro['fullname'];
    $online = $registro['online'] ? "color-green" : "color-red";
    $tbody .= "<tr>";
    $tbody .= "<td>" . $registro['username'] . "</td>";
    $tbody .= "<td>" . $fullname . "</td>";
    $tbody .= "<td>" . "<i class='pl-3 fas fa-circle $online'></i>" . "</td>";
    $tbody .= "<td>" . $registro['tag'] . "</td>";
    $tbody .= "<td>" . $registro['clients'] . "</td>";

    if ($user == "admin") {
        $iconConect = $registro['online'] ? "fa-sign-out-alt" : "fa-sign-in-alt";
        $titleConect = $registro['online'] ? "Desconectar" : "Conectar";
        $urlConect = $registro['online'] ? "./desconectar.php?asesor_id=" . $id : "./conectar.php?asesor_id=" . $id;
        $tbody .= " <td>
                        <a title='$titleConect' href='$urlConect'><i class='erase fas $iconConect'></i></a>
                        <a title='Editar' href='./admin.php?asesor_id=$id')'><i class='erase fas fa-edit'></i></a>
                        <a title='Eliminar' href='#' onclick='confirm_delete($(this))' data-fullname='$fullname' data-id='$id'><i class='erase fas fa-trash'></i></a>
                    </td>";
    }
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
                    <li class="nav-item"><?php echo $btnConexion; ?></li>
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
            <div class="col-md-4 offset-md-4">
                <h3>Usuarios del sistema</h3>
            </div>
            <?php if ($user == "admin") { ?>
                <div class="col-md-2 offset-md-2">
                    <h5><a href="./admin.php"> <i class="fas fa-plus-circle"></i> Crear Usuario</a></h5>
                </div>
            <?php } ?>
        </div>
        <div class="row row-content">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                Nombre usuario
                            </th>
                            <th>
                                Nombre Completo
                            </th>
                            <th>
                                Online
                            </th>
                            <th>
                                TAG
                            </th>
                            <th>
                                Clientes atendiendo
                            </th>
                            <?php if ($user == "admin") { ?>
                                <th>
                                    Acciones
                                </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tbody; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <?php if ($user == "admin") { ?>
        <script>
            function confirm_delete(button) {

                var id = button.attr('data-id');
                var fullname = button.attr('data-fullname');

                Swal.fire({
                    title: '¿Seguro desea borrar el usuario ' + fullname + '?',
                    text: "Una vez borrado del servidor no se podrá recuperar",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Borrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "./borrarUser.php?id=" + id
                    }
                })
            }
        </script>
    <?php } ?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>