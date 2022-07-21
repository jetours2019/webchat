<?php


array_key_exists('ces', $_GET) or die('Error en los parametros');
array_key_exists('da', $_GET) or die('Error en los parametros');

$id_respond = $_GET['ces'];
$da = $_GET['da'];
require_once "../db/conexion.php";

$query_asesor = "SELECT * FROM usuarios WHERE id_respond='" . $id_respond . "'";
$consulta = mysqli_query($conexion, $query_asesor) or die(mysqli_error($conexion));
$registro = mysqli_fetch_array($consulta);
$saved = 0;
$error = 0;
if (!$registro) {
    $asesor = false;
} else {
    $asesor = $registro['fullname'];
    $id_asesor = $registro['id'];
    session_start();
    if(array_key_exists('saved', $_SESSION)){
        $saved = 1;
        unset($_SESSION['saved']);
    }elseif(array_key_exists('error', $_SESSION)){
        $error = 1;
        unset($_SESSION['error']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificación WebChat</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/iconlogo.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500;900&display=swap" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet" type="text/css">

</head>

<body>

    <form action="guardar.php" method="POST">

        <img src="https://aliadostravel.com/wp-content/uploads/2022/03/Logo.png">
        <?php
        if ($asesor) {
        ?>
            <h2>Califica tu experiencia con el asesor@ <b><?php echo $asesor; ?> </b> </h2>

            <p class="clasificacion">
                <input id="pre-1" type="radio" name="calificacion" value="5">
                <label for="pre-1">★</label>
                <input id="pre-2" type="radio" name="calificacion" value="4">
                <label for="pre-2">★</label>
                <input id="pre-3" type="radio" name="calificacion" value="3">
                <label for="pre-3">★</label>
                <input id="pre-4" type="radio" name="calificacion" value="2">
                <label for="pre-4">★</label>
                <input id="pre-5" type="radio" name="calificacion" value="1">
                <label for="pre-5">★</label>
            </p>
            <p class="comentarios">
                <label>Comentarios Adicionales</label>
                <textarea name="comentario"></textarea>
            </p>
            <input type="hidden" name="ces" value="<?php echo $id_respond; ?>">
            <input type="hidden" name="da" value="<?php echo $da; ?>">
            <input type="hidden" name="id_asesor" value="<?php echo $id_asesor; ?>">

            <p class="comentarios">
                <input type="submit" disabled class="noHover" id="submit" value="ENVIAR CALIFICACIÓN">
            </p>

        <?php
        } else {
        ?>
            <h2>No se encontró el asesor</h2>
        <?php
        }
        ?>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var saved = <?php echo $saved ?>;
    var error = <?php echo $error ?>;
</script>
<script src="../assets/js/calificacion.js"></script>

</html>