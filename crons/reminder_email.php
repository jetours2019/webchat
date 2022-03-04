<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'credentials.php';

$mail = new PHPMailer(true);

echo "mail...\n";
try {
    echo "try...\n";

    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $Host;
    $mail->SMTPAuth   = $SMTPAuth;
    $mail->Username   = $Username;
    $mail->Password   = $Password;
    $mail->SMTPSecure = $SMTPSecure;
    $mail->Port       = $Port;

    //Recipients
    $mail->setFrom('desarrollo@aliadostravel.com', 'Aliados Travel SAS');
    $mail->addAddress('cruz.camilo@correounivalle.edu.co', 'Camilo');     //Add a recipient
    $mail->addReplyTo('desarrollo@aliadostravel.com', 'Aliados Travel SAS');

    //Content
    $mail->isHTML(true);  
    $DateAndTime = date('m-d-Y h:i:s a', time());                                //Set email format to HTML
    $mail->Subject = 'Prueba Correo';
    $mail->Body    = "Mensaje enviado a las <b>$DateAndTime</b> from hosting";
    $mail->AltBody = "This is the body in plain text for non-HTML mail clients";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}