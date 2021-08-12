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
$creation = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $creation = true;
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $password = md5($_POST['password']);
    $tag = $_POST['tag'];

    $query = "INSERT INTO `usuarios` (`username`, `password`, `fullname`, `tag`) VALUES
    ('$username', '$password', '$fullname', '$tag');";
    $act = $conexion->query($query);

    $mensaje = "<div class='row row-content'> Usuario '$fullname' creado correctamente.";
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
        <?php if (!$creation) { ?>
            <div class="row pt-5">
                <div class="col-md-4 offset-md-4">
                    <h4>
                        Creación de Usuario
                    </h4>
                </div>
            </div>
            <form class="needs-validation" novalidate method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Nombre de usuario</label>
                            <input required type="text" class="form-control" id="username" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input required type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fullname">Nombre Completo</label>
                            <input required type="text" class="form-control" id="fullname" name="fullname" placeholder="Nombre Completo">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tag">TAG de Respond</label>
                            <input required type="text" class="form-control" id="tag" name="tag" placeholder="TAG">
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