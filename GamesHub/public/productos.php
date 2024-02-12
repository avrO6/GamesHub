<?php
session_start();
require "../php/funciones.php";


/* compruebo que se halla dado al boton de añadir al carrito para añadir los productos */
if (isset($_POST["añadir_carrito"])){

    if( isset($_SESSION["Rol"])) {
        try {
            /* llamamos a la funcion de añadir carrito y le pasamo el parametro que contiene el id del 
            producto seleccionado */
            añadirAlCarrito($_POST["añadir_carrito"]);
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }else{
        /* si no has iniciado sesion te redirigirá al login con un mensaje */
        echo "<div class='fade-in-out show'><p>Inicie sesion para añadir al carro</p></div>";
    }
} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../styles/main-styles.css">
</head>

<body data-bs-theme="dark">

    <header>

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">

                <div class="logo-img">
                    <img src="../img/logo.png" alt="Cargando...">
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo02">

                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active nav-size" aria-current="page" href="main.php">Explorar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-size" href="productos.php?redirigido=1">Sistemas Operativos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-size" href="productos.php?redirigido=2">Juegos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-size" href="productos.php?redirigido=3">Licencias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-size" href="productos.php?redirigido=4">Targetas regalo</a>
                        </li>
                    </ul>

                    <form class="d-flex" role="search" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="carrito">
                            <div class="carrito-icono">
                                <a href="./carrito.php">
                                    <img src="../img/carrito-de-compras.png" alt="">
                                </a>
                            </div>
                            <div class="numero-articulos">
                                <?php
                                // Mostrar la cantidad de productos en el carrito
                                if (isset($_SESSION["Carrito"])) {
                                    $cantidadProductos = count($_SESSION["Carrito"]);
                                    if ($cantidadProductos > 0) {
                                        echo "<span class='badge'>$cantidadProductos</span>";
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <input name="texto_bus" class="form-control me-2" type="text" placeholder="Buscar por nombre" aria-label="Search">
                        <button name="buscador" class="btn btn-outline-light" type="submit">Buscar</button>
                    </form>

                </div>

            </div>
        </nav>

    </header>

    <main>

        <aside class="bg-body-tertiary">

            <div class="aside-content">
                <span>
                    <b><a class="nav-link" href="https://discord.gg/MMYmZZwx7k">Comunidad</a></b>
                </span>
                <span>
                    <b><a class="nav-link" href="#">Atencion al Cliente</a></b>
                </span>

                <?php
                     /* muestro el enlace a la zona admin en caso de que el usuario logeado tenga rol 0 */
                if (isset($_SESSION["Rol"]) && $_SESSION["Rol"] == 0) {
                    echo "
                        <span>
                            <b><a class='nav-link' href='./zonaAdmin.php'>Administración</a></b>
                        </span>";
                }

                ?>

            </div>

            <div class="avatar-img">

                <img src="../img/usuario.png" alt="AVATAR">

                <div class="content-avatar">

                    <?php
                        /* muestro los puntos del usuario y el enlace para desloguearse en caso de que tengas la sesion
                        iniciada y si no te muestra solo el enlace para iniciar sesion */
                    if (isset($_SESSION["Rol"])) {
                        echo ('<button class="btn btn-outline-light">' . $_SESSION["Puntos"] . ' GP</button>');
                        echo ('<a class="nav-link" href="../php/logout.php">Log out</a>');
                    } else {

                        echo ('<a class="nav-link" href="login.php">Log in</a>');
                    }
                    ?>

                </div>

            </div>
        </aside>

        <section>
            <?php

            $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
            $usuario = "root";
            $contraseña = "";
            $db = new PDO($cadena_conexion, $usuario, $contraseña);

            /* Este código se ejecuta en caso de usar el buscador */
            if (isset($_POST["buscador"])) {
                /* Se construye un patrón de búsqueda para el nombre del producto en el que los porcentajes en sql  indican 
                cualquier caracter*/
               $nombre = "%" . $_POST["texto_bus"] . "%"; 
           
               // Buscamos productos que coincidan con el nombre
               $consulta = $db->prepare("SELECT ID, Precio, Categoria, Descripcion, Nombre FROM productos WHERE Nombre LIKE ?");
               $consulta->execute(array($nombre));
           
               // Mostramos los productos encontrados
               foreach ($consulta as $filas) {
                   // Formulario para añadir productos al carrito
                   echo "<form action='main.php' method='POST'> <div class='card' style='width: 18rem;'>
                               <img src='../img/" . $filas["Nombre"] . ".png' class='card-img-top' alt='...'>
                               <div class='card-body'>
                               <h5 class='card-title'>" . $filas["Nombre"] . "&nbsp&nbsp&nbsp" . $filas["Precio"] . "€" . "</h5>
                               <p class='card-text'>" . $filas["Descripcion"] . "</p>
                               <button value=" . $filas["ID"] . " name='añadir_carrito' type='submit' class='btn btn-primary'>Añadir al carrito</button>
                               </div>
                               </div></form>  ";
               }
            }else {
                /* muestro los productos en funcion del apartado que hallan seleccionado en la pagina principal */

                if (isset($_GET["redirigido"])) {

                    // Obtenemos todos los productos que cumplen las condiciones
                    $consulta = $db->prepare("SELECT ID,Precio,Categoria,Descripcion,Nombre FROM productos Where Categoria =? ");
                    $consulta->execute(array($_GET["redirigido"]));

                    // Mostramos todos los productos
                    foreach ($consulta as $filas) {
                        /* formulario del producto */
                        echo "<form method='POST'> <div class='card' style='width: 18rem;'>
                                <img src='../img/" . $filas["Nombre"] . ".png' class='card-img-top' alt='...'>
                                <div class='card-body'>
                                <h5 class='card-title'>" . $filas["Nombre"] . "&nbsp&nbsp&nbsp" . $filas["Precio"] . "€" . "</h5>
                                <p class='card-text'>" . $filas["Descripcion"] . "</p>
                                <button value=" . $filas["ID"] . " name='añadir_carrito' type='submit' class='btn btn-primary'>Añadir al carrito</button>
                                </div>
                                </div>
                            </form>  ";
                    }
                } else {
                    header("location:./main.php");
                }
            }
            ?>
            <!--             <div class="card" style="width: 18rem;">
                <img src="../img/logo.png" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title">Card title</h5>
                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div> -->

        </section>

    </main>
    <?php

    ?>

    <script src=""></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>