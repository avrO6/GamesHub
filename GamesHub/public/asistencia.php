<?php   
    session_start();
 /*  si has pulsado el boton de enviar mensaje 
 se crea una variable de session que almacena los datos proporcionados por el ususario,
 en el caso de la clave mail guardo un correo fijo por que en este caso ese es el correo de lapagina.
 Despues lo redirigo a la pagina mailer.php que es donde se creará y se enviará el correo */
    if(isset($_POST["atencion_cliente"])){
        $_SESSION["mail"] =[
            "mail_pagina" => $_POST["email"],
            "nombre" => $_POST["nombre"],
            "cuerpo" => "usuario :".$_POST["email"] ."\n\n". $_POST["cuerpo"],
            "mail" =>  "sergio.seves9999@gmail.com",
            "respuesta"=> true
        ];

        header("location:../php/mailer.php?asistencia=true ");
    }

?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../styles/olvidona-styles.css">
    <title>Asistencia al cliente</title>
</head>

<body data-bs-theme="dark">
    <main>

        <div class="form">
            <div class="volver">
                <a href="main.php"><-- Volver al Main</a>
            </div>
            <form action="" method="post">

                <div class="input-group mb-3 dinero">
                    <span class="input-group-text" id="basic-addon1">Nombre</span>
                    <input type="text" class="form-control" name="nombre" require>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="email" class="form-control" name="email" require>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Escriba aquí su problema</span>
                    <br>
                    <textarea class="form-control" name="cuerpo" require></textarea>  
                </div>

                <button class='btn btn-primary' type="submit" name="atencion_cliente">Enviar mensaje</button>

            </form>

        </div>

    </main>
</body>

</html>