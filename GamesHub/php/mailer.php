<?php
use PHPMailer\PHPMailer\PHPMailer;

require "./vendor/autoload.php";
session_start();


function Enviar_correo($envio, $nombre, $cuerpo) {
    
    $mail = new PHPMailer();
    
    
    $mail->IsSMTP();
    $mail->SMTPDebug = 0; 
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;

    // Credenciales de la cuenta de correo.
    $mail->Username = "sergio.seves9999@gmail.com";
    $mail->Password = "xjgz xdcj tzpw ndth";

    // Configura el remitente del correo.
    $mail->setFrom("sergio.seves9999@gmail.com", "yo");

    // Configura el asunto y el cuerpo del correo.
    $mail->Subject = "GamesHub";
    $mail->Body = $cuerpo;
    $mail->isHTML(false);

    // Agrega el destinatario y su nombre.
    $address = $envio;
    $mail->addAddress($address, "$nombre");

    // Intenta enviar el correo y muestra un mensaje de éxito o error.
    $result = $mail->send();
    if (!$result) {
        header("location:../public/checkout.php?error=true");
        /* echo "<br><br><br>Error: " . $mail->ErrorInfo . "<br><br><br>"; */
    } else {
        $_SESSION["Carrito"] =[];

        if(isset($_SESSION["Rol"])){
            $_SESSION["verde"]=true;
        }
        
        if(isset($_SESSION["cuerpo"])){
            header("location:../public/login.php?redirigido=true");
        }
        else{
            header("location:../public/main.php");
        }
        
    }
}

Enviar_correo($_SESSION["mail"],$_SESSION["nombre"],$_SESSION["cuerpo"]);
?>