<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$level_file = "..";
require_once "$level_file/db/conexion.php";

$query = "SELECT * FROM usuarios WHERE username != 'admin' AND online=false";

$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
while ($registro = mysqli_fetch_array($consulta)) {
    echo $registro['fullname'] . " - " . $registro['email'] . "<br>";
    send_remainder_email($registro['fullname'], $registro['email']);
}

function send_remainder_email($name, $email){
    require 'credentials.php';
    $mail = new PHPMailer(true);
    try {

        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();           
        $mail->CharSet = 'UTF-8';                                 //Send using SMTP
        $mail->Host       = $Host;
        $mail->SMTPAuth   = $SMTPAuth;
        $mail->Username   = $Username;
        $mail->Password   = $Password;
        $mail->SMTPSecure = $SMTPSecure;
        $mail->Port       = $Port;
    
        //Recipients
        $mail->setFrom('desarrollo@aliadostravel.com', 'Notificaciones Automaticas');
        $mail->addAddress($email, $name);     //Add a recipient
        $mail->addReplyTo('desarrollo@aliadostravel.com', 'Notificaciones Automaticas');
    
        //Content
        $mail->isHTML(true);  
        $DateAndTime = date('m-d-Y h:i:s a', time());                                //Set email format to HTML
        $mail->Subject = 'Mensaje Importante | Aliados travel';
        $mail->Body    = 'Estimad@ '.$name.'.<br><p>Nuestro sistema de login identifica tu usuario como <b><i>"Desconectado"</i></b>, para nosotros es  importante contar con tu participación en el chat.<br></p><hr>
                            Recuerda que puedes hacerlo ingresando a <a href="https://www.aliadostravel.com/webchat">www.aliadostravel.com/webchat </a>
                            <hr><br>
                            <i>Si estas fuera de turno hacer caso  omiso a este mensaje</i>.';
        $mail->AltBody = 'Estimad@ '.$name.'. Nuestro sistema de login identifica tu usuario como "Desconectado", para nosotros es  importante contar con tu participación en el chat.
        Recuerda que puedes hacerlo ingresando a www.aliadostravel.com/webchat 
        Si estas fuera de turno hacer caso  omiso a este mensaje.';
    
        $mail->send();
        echo 'Message has been sent'. "<br>";
    } catch (Exception $e) {
        $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}. Exception: {$e}";
        echo $error;
        file_put_contents('errors.php', $error);
    
    }
}