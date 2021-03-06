<?php
session_start();
require_once "../db/conexion.php";

if (!$_SESSION['logged']) {
    header('location: ../login/login.php');
} else {
    if ($_SESSION['user'] != "admin") {
        header('location: ./index.php');
    }
}

$user = $_SESSION['user'];
$name = $_SESSION['fullname'];
$btnConexion = $_SESSION['conected'] ? '<a class="nav-link" href="./desconectar.php"><i class="fas fa-sign-out-alt pr-2"></i></i>Desconectar</a>' : '<a class="nav-link" href="./conectar.php"><i class="fas fa-sign-in-alt pr-2"></i>Conectar</a>';

$inputId = $valueUsername =  $valueTag = $valueIdRespond = $valueFullname = "";
$valuePassword = "required";
if (array_key_exists('asesor_id', $_GET) && $_GET['asesor_id'] != '') {

    $id =  $_GET['asesor_id'];
    $inputId = "<input hidden value='$id' name='id'></input>";
    $query = "SELECT * 
          FROM usuarios 
          WHERE id=$id";

    $consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $registro = mysqli_fetch_array($consulta);
    $valuePassword = "";
    $valueUsername = $registro['username'];
    $valueTag = $registro['tag'];
    $valueIdRespond = $registro['id_respond'];
    $valueFullname = $registro['fullname'];
    $valueEmail = $registro['email'];
    $accionTitle = "Edición";
} else {
    $accionTitle = "Creación";
}

$creation = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $creation = true;
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $tag = $_POST['tag'];
    $email = $_POST['email'];
    $id_respond = $_POST['id_respond'];

    if (array_key_exists('id', $_POST)) {
        $id = $_POST['id'];
        $password = (array_key_exists('password', $_POST) && $_POST['password'] != "") ? "password='" . md5($_POST['password']) . "', " : "";
        $query = "UPDATE usuarios SET username='$username', fullname='$fullname', $password tag='$tag', id_respond='$id_respond', email='$email' WHERE id=$id";
        $accion = "editado";
    } else {
        $password = md5($_POST['password']);
        $query = "INSERT INTO `usuarios` (`username`, `password`, `fullname`, `tag`, `id_respond`, `email`) VALUES
                ('$username', '$password', '$fullname', '$tag', '$id_respond', '$email');";
        $accion = "creado";
    }
    $act = $conexion->query($query);

    $mensaje = "<div class='row row-content'> Usuario '$fullname' $accion correctamente.";
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
        <?php if (!$creation) { ?>
            <div class="row pt-5">
                <div class="col-md-4 offset-md-4">
                    <h4>
                        <?php echo $accionTitle; ?> de Usuario
                    </h4>
                </div>
            </div>
            <form class="needs-validation" novalidate method="POST">
                <?php echo $inputId; ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Nombre de usuario</label>
                            <input value="<?php echo $valueUsername; ?>" required type="text" class="form-control" id="username" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input <?php echo $valuePassword; ?> type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fullname">Nombre Completo</label>
                            <input value="<?php echo $valueFullname; ?>" required type="text" class="form-control" id="fullname" name="fullname" placeholder="Nombre Completo">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tag">TAG de Respond</label>
                            <input value="<?php echo $valueTag; ?>" required type="text" class="form-control" id="tag" name="tag" placeholder="TAG">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tag">ID de Respond</label>
                            <input value="<?php echo $valueIdRespond; ?>" required type="text" class="form-control" id="id_respond" name="id_respond" placeholder="ID Respond">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tag">Email</label>
                            <input value="<?php echo $valueEmail; ?>" required type="text" class="form-control" id="email" name="email" placeholder="Email">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 offset-md-4">
                        <button type="submit" class="w-100 btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        <?php } else {
            echo $mensaje;
        } ?>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <?php if ($creation) { ?>
        <script>
            setTimeout(() => {
                location.href = "./index.php";
            }, 1500);
        </script>
    <?php } ?>


    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>


</body>

</html>