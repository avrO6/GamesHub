<?php 
    session_start();

    if(!isset($_SESSION["Carrito"])){
        header("location: Login.php?carro=true");
    }
    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";
    $db = new PDO($cadena_conexion, $usuario, $contraseña);

    
        $arr1 = $_SESSION["Carrito"];

    if(isset($_POST["eliminar"])){
        unset($_SESSION["Carrito"][$_POST['eliminar']]);
         header("location:./carrito.php");  
    }
       


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../styles/carrito-styles.css">
    <title>Carrito</title>
</head>

<body data-bs-theme="dark">

    <main class="page">

        <section class="shopping-cart dark">

            <div class="volver">
                <a href="main.php"><-- Seguir comprando</a>
            </div>


            <!-- Dinamico aqui -->

            <?php 
                foreach ($arr1 as $clave => $valor) {
                    // Aquí puedes usar $clave como tu ID en una consulta
                    
                
                    $consulta = $db->prepare( "SELECT ID, Precio, Categoria, Nombre FROM productos WHERE id = ?");
                    $consulta->execute(array($clave));
                    // Tu lógica de consulta aquí, por ejemplo:
                    // $consulta = "SELECT * FROM tu_tabla WHERE id = $clave";
                    // Ejecutar la consulta...

                    $producto = $consulta->fetch();

                        echo "<form action=" .htmlspecialchars($_SERVER["PHP_SELF"]) . " method='post'><div class='productos'>
                        <div class='producto'>
                            <img src='../img/".$producto["Nombre"].".png' alt='Producto.'>
                            <span><b>".$producto["Nombre"]."</b></span>
                        </div>
                        <div class='sumary'>
                            <span>'".$valor."'</span>
                            <span>".$valor*($producto["Precio"])."€</span>
                            <button value='".$clave."' name='eliminar' type='submit'><img src='../img/borrar.png' alt=''></button>
                        </div>
                        </div></form>";
                }
            ?>

<!--             <div class="productos">

                <div class="producto">
                    <img src="../img/gta VI.png" alt="Producto.">
                    <span><b>GTA VI</b></span>
                </div>

                <div class="sumary">
                    <span>"2"</span>
                    <span>$$$</span>
                    <button type="submit"><img src="../img/borrar.png" alt=""></button>
                </div>


            </div> -->



        </section>

        <aside>

            <div class="checking">

                <form action="" method="post">

                    <h3>Resumen</h3>

                    <div class="resum">
                        <span>Subtotal</span>
                        <span>$$$</span>
                    </div>

                    <div class="resum">
                        <span>PG</span>
                        <span>XXXX</span>
                    </div>

                    <div class="resum">
                        <span>Descuento</span>
                        <span>$$$</span>
                    </div>

                    <div class="resum">
                        <span>Total</span>
                        <span>$$$</span>
                    </div>

                    <form action="">
                        <div class="resum">
                            <input type="checkbox" name="descuento"> Aplicar descuento
                        </div>
                    </form>

                    <button type="submit" class='btn btn-primary'>Checkout</button>

                </form>
                
            </div>

        </aside>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>


