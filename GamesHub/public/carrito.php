<?php 
    session_start();
    require "../php/funciones.php";

    /* llamo a la funcion productos para que siempre que se entre a esta pagina este
    la variable $_SESSION["puntos"] actualizada */
    actualizar_puntos();

    /* no te permite entrar al carrito si no has iniciado sessión */
    if(!isset($_SESSION["Carrito"])){
        header("location: Login.php?carro=true");
    }
    
    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";
    $db = new PDO($cadena_conexion, $usuario, $contraseña);

     /* guado la los datos de los productos añadidos al carrito */
        $arr1 = $_SESSION["Carrito"];

        /* en caso de usar el boton de eliminar*/
    if(isset($_POST["eliminar"])){

        //se elimina del array del carrito la posicion seleccionada
        unset($_SESSION["Carrito"][$_POST['eliminar']]);//$_POST["contiene el id del producto seleccionado"]
        header("location:carrito.php");
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
            $precio_total =0;
             /* arr1 contiene en el apartado de la clave el id del producto y el valor es la cantidad */
                foreach ($arr1 as $clave => $valor) {
                    /* busco el producto en funcion de la clave  */
                    $consulta = $db->prepare( "SELECT ID, Precio, Categoria, Nombre FROM productos WHERE id = ?");
                    $consulta->execute(array($clave));

                    $producto = $consulta->fetch();
                    /* guardo el valor total del  juego y sus copias */
                    $precio_prod = $valor*($producto["Precio"]);
                        /* muestro el producto  */
                        echo "<form action=" .htmlspecialchars($_SERVER["PHP_SELF"]) . " method='post'><div class='productos'>
                        <div class='producto'>
                            <img src='../img/".$producto["Nombre"].".png' alt='Producto.'>
                            <span><b>".$producto["Nombre"]."</b></span>
                        </div>
                        <div class='sumary'>
                            <span>'".$valor."'</span>
                            <span>". $precio_prod."€</span>
                            <button value='".$clave."' name='eliminar' type='submit'><img src='../img/borrar.png' alt=''></button>
                        </div>
                        </div></form>";
                 
                        /* summo el precio de todos los productos */
                    $precio_total = $precio_total + $precio_prod;
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

                <form action="./checkout.php" method="post">

                    <?php
                    /* paso los puntos a lo que seria en dinero  */
                        $puntos = round($_SESSION['Puntos'] / 100);
                        echo "                 
                            <h3>Resumen</h3>

                            <div class='resum'>
                                <span>Total</span>
                                <span name='total'>".$precio_total." €</span>
                            </div>
        
                            <div class='resum'>
                                <span>PG</span>
                                <span>".$_SESSION['Puntos']."</span>
                            </div>
        
                            <div class='resum'>
                                <span>Descuento</span>
                                <span name='descuento'>". $puntos ." €</span>
                            </div>

                            <div class='resum'>
                                <input type='checkbox' name='check' value='si'> Aplicar descuento
                            </div>
        
                            <div class='resum'>
                                <span>Total + descuento</span> 
                                <span id='descuento'> ".controlar_negativos($precio_total - $puntos)/* calculo el descuento */." €</span>
                            </div>
        
                            <button name='checkout' type='submit' class='btn btn-primary'>Checkout</button>";

                            $_SESSION["total"] = $precio_total;
                            $_SESSION["descuento"] = controlar_negativos($puntos);
                    ?>

<!--                     <h3>Resumen</h3>

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

                    <button type="submit" class='btn btn-primary'>Checkout</button> -->

                </form>
                
            </div>

        </aside>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="../script/total-dinamico.js"></script>                
                
</body>

</html>