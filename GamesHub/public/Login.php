
<?php
    
if (isset($_GET["redirigido"])) {
    echo "<div class='fade-in-out-verde show'><p>Se le ha enviado un correo con su contraseña</p></div>";
    echo "<meta http-equiv='refresh' content='1.8;url=login.php'>";
}

if(isset($_GET["carro"])){
    echo "<div class='fade-in-out-rojo show'><p>Necesitas iniciar sesion para acceder al carrito</p></div>";
}

function comprobar_usuario($email, $passwd)
{
    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";

    try {
        $db = new PDO($cadena_conexion, $usuario, $contraseña);

        //Guardamos la consulta en una variable la cual pregunta por el usuario y la clave obtenidas en el formulario
        $consulta = "SELECT ID, Rol, Name, puntos FROM usuarios WHERE Correo = '$email' AND passwd = '$passwd'";
        //Ejecutamos la consulta
        $resul = $db->query($consulta);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    //Si devuelve lineas significa que el usuario existe
    if ($resul->rowCount() == 0) {
        return false;
    } else {

        session_start();
        $_SESSION["Correo"] = $email;
        while ($row = $resul->fetch()) {
            $_SESSION["ID"] = $row["ID"];
            $_SESSION["Rol"] = $row["Rol"];
            $_SESSION["Name"] = $row["Name"];
            $_SESSION["Puntos"] = $row["puntos"];
            $_SESSION["Carrito"] = [];
            $_SESSION["verde"]=false;
        }
        
        return true;
    }
}

    if (isset($_POST["logear"])) {
    //Compruebo si el usuario existe y si es asi le redirijo a la pagina principal
        if (comprobar_usuario($_POST['correo'], $_POST['passwd'])) {
            header("Location: main.php"); 
        } else {
            echo "<div class='fade-in-out-rojo'><p>Las credenciales no coinciden</p></div>";
            $err = TRUE;     
        }
    }

    if (isset($_POST["registrar"])) {
        //Compruebo si el usuario existe y si es asi le redirijo a la pagina principal
            if (insertar_usuario($_POST['nombre'], $_POST['mail'], $_POST['contraseña'])) {
                comprobar_usuario($_POST['mail'], $_POST['contraseña']);
                header("Location: main.php"); 
            } else {
                echo "<div class='fade-in-out-rojo'><p>Algo a salido mal intentelo mas tarde</p></div>";
                $err = TRUE;     
            }
        }

    function insertar_usuario($Nombre, $correo, $passwd){

        $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
        $usuario = "root";
        $contraseña = "";

        try{

        $db = new PDO($cadena_conexion, $usuario, $contraseña);
        
        $db->beginTransaction();

        $usuarios = $db->prepare("INSERT into usuarios(Name, correo, Rol, passwd, puntos) VALUES (:Name, :correo, 1, :passwd, 400)");
            
            
        $usuarios->execute(array(":Name" => $Nombre, ":correo" => $correo, ":passwd" => $passwd));
        $db->commit() ;  

        return true;

        }catch(PDOException $e){

            $db->rollBack();
            echo "<div class='fade-in-out-rojo'><p>Eroor al crear usuario</p></div>";
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
    <link rel="stylesheet" href="../styles/login-style.css">
    <title>Document</title>
</head>
<body data-bs-theme="dark">

    <button onclick="cambiarModo()" class="btn rounded-fill"><i class="fa-solid fa-circle-half-stroke"></i></button>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <h1>Sing Up</h1>
                <div class="social-icons">
                    <a href="main.php" class="icon"><img src="../img/hogar.png" alt=""></a>
                </div>
                <span> Completa el formulario para crear tu cuenta </span>
                <input type="text" placeholder="Name" name="nombre" required>
                <input type="email" placeholder="Email" name="mail" required>
                <input type="password" placeholder="Password" name="contraseña" required>
                <button name="registrar" type="submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="main.php" class="icon"><img src="../img/hogar.png" alt=""></a>
                </div>
                <span> Completa el formulario para iniciar sesión </span>
                <input type="email" placeholder="Email" name="correo" required>
                <input type="password" placeholder="Password" name="passwd" required>
                <a href="olvidona.php">¿Has olvidado tu contraseña?</a>
                <button name="logear" type="POST">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>
                        Ingrese sus datos personales para utilizar todas las funciones de la Web
                    </p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>
                        Regístrese con sus datos personales para utilizar todas las funciones de la Web y gane 400 GP (GamesHub Points).
                    </p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>