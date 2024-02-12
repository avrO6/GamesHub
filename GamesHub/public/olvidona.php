<?php
    session_start();


    /* se entra en el if si se a pulsado el boton de recordar contraseña */
if (isset($_POST["cambiar_contraseña"])) {

    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña_db = ""; 
    $errmode = [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT];//Permite que una consulta te devuelva false en caso de que salga mal
    try {
        $db = new PDO($cadena_conexion, $usuario, $contraseña_db,$errmode);

        /* buscamos la contraseña de la persona que tenga el correo y nombre especidicado en el formulario */
        $olvidona = $db->prepare("SELECT Correo,passwd,Name FROM usuarios WHERE  Name =? AND Correo = ? ");
        $olvidona->execute(array($_POST["nombre"],$_POST["email"]));
        $contraseña = $olvidona->fetch();
        if($contraseña==false){
            echo "<div class='fade-in-out-rojo show'><p>Nombre o correo no coinciden</p></div>";
        }
        else{  
            /* creo una variable que contiente los datos del cuerpo del correo y el nombre y correo del usuario 
            despues lo redirijo a la pagina donde se usaran esos datos para enviar el mensaje*/
                $_SESSION["mail"] =[
                    "nombre" => $_POST["nombre"],
                    "cuerpo" => $_POST["nombre"].",\nSu contraseña  es : \n".$contraseña["passwd"]."\n\n\nUnete a Nuestro discord para estar informado de nuestras actualizaciones --->😎 https://discord.gg/MMYmZZwx7k 👌",
                    "mail" => $_POST["email"]
                ];  
                 
            header("location:../php/mailer.php?olvidona=true");
        }
    
    } catch (PDOException $e) {
        /* si la consulta no sale bien significa que no había un usuario con ese correo  */
        echo $e->getMessage();
        echo "<div class='fade-in-out-rojo show'><p>Error,no exixte un usuario con ese correo</p></div>";
        
    }
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
    <title>Checkout</title>
</head>

<body data-bs-theme="dark">
    <main>

        <div class="form">
            <div class="volver">
                <a href="login.php"><-- Volver al Login</a>
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

                <button class='btn btn-primary' type="submit" name="cambiar_contraseña">Recordar contraseña</button>

            </form>

        </div>

    </main>
</body>

</html>