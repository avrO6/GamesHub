<?php

function comprobar_usuario($email)
{
    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";

    try {
        $db = new PDO($cadena_conexion, $usuario, $contraseña);

        //Guardamos la consulta en una variable la cual pregunta por el usuario y la clave obtenidas en el formulario
        $consulta = "SELECT Rol FROM usuarios WHERE Correo = '$email'";
        //Ejecutamos la consulta
        $resul = $db->query($consulta);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    //Si devuelve lineas significa que el usuario existe
    if ($resul->rowCount() == 0) {
        return false;
    } else {

        return true;
    }
}

if (isset($_POST["cambiar_contraseña"])) {
    //Compruebo si el usuario existe y si es asi le redirijo a la pagina principal
    if (comprobar_usuario($_POST['email'])) {
        modificar_usuario($_POST['email'], $_POST['passwd']);
    } else {
        echo "Las credenciales no coinciden";
        $err = TRUE;
    }
}
function modificar_usuario($correo, $passwd)
{

    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";

    try {

        $db = new PDO($cadena_conexion, $usuario, $contraseña);

        $db->beginTransaction();

        $usuarios = $db->prepare("UPDATE usuarios
        SET passwd = :contraseña
        WHERE Correo = :email");

        $usuarios->execute(array( ":email" => $correo, ":contraseña" => $passwd));
        $db->commit();

        echo "Se ha modificado la contraseña";
        return true;
    } catch (PDOException $e) {

        $db->rollBack();
        echo "<p>Error al modificar el usuario</p>";
        return false;
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

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control" placeholder="E-mail" name="email" aria-label="Username" aria-describedby="basic-addon1 " value="" require>
                </div>
                <div class="input-group mb-3 dinero">
                    <span class="input-group-text" id="basic-addon1">Nueva contraseña</span>
                    <input type="password" class="form-control" name="passwd" aria-label="Username" aria-describedby="basic-addon1" value="" require>
                </div>

                <button class='btn btn-primary' type="submit" name="cambiar_contraseña">Cambiar contraseña</button>

            </form>

        </div>

    </main>
</body>

</html>