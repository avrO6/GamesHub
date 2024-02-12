<?php  
    session_start();
    require "../php/funciones.php";

    /* la compra no salio bien */
    if(isset($_GET["error"])){
        echo "<div class='fade-in-out show'><p>Error al intentar hacer la transacción</p></div>";
    };

        /* en caso de intentar entrar a la pagina sin darle al boton de comprar te mandará a
    la página principal */
    if(!isset($_POST["checkout"])){
        header("location:main.php");
    }

    /* impedir que se haga un pago si no has seleccionado productos */
    if(isset($_SESSION["total"])){
        if($_SESSION["total"]==0){
            header("location:carrito.php");
        }
    }


    /* si seleccionas checkout en el carrito, se guarda el dinero de todos los productos seleccionados
    y se guarda el descuento que se haría */
    if(isset($_POST["checkout"])){
        $total = $_SESSION["total"];
        $descuento = $_SESSION["descuento"];
        /* en caso de elegir aplicar el descuento actualizo la variable de sesion que almacena los puntos del ususario
        calculo el total que seria el precio de todos los prodictos menos el descuento */
        if(isset($_POST["check"])!=null ){
            $_SESSION["Puntos"]= controlar_negativos($_SESSION["Puntos"]-round($total*100));
            $total = $total-$descuento;
            $_SESSION["total"] = $total;
        }

    }
    /* controlo de que el resultado no salga negativo */
    $total = controlar_negativos($_SESSION["total"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../styles/checkout-styles.css">
    <title>Checkout</title>
</head>

<body data-bs-theme="dark">

            
    <main>

        <div class="form">
            <div class="volver">
                <a href="carrito.php"><-- Volver a tu Carrito</a>
            </div>

            <form action="../php/pago.php" method="post">

                <div class="input-group">
                    <span class="input-group-text">First and last name</span>
                    <input type="text" aria-label="First name" class="form-control" pattern="[A-Za-z]+" name="nombre" required>
                    <input type="text" aria-label="Last name" class="form-control" pattern="[A-Za-z\s]+" name="apellido" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Nº Targeta</span>
                    <input type="text" class="form-control" name="tarjeta" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="1234-5678-9012-3456">
                    <span class="input-group-text" id="inputGroup-sizing-default">CVV</span>
                    <input type="text" class="form-control" name="cvv" aria-label="Sizing example input" pattern="[0-9]{3}" aria-describedby="inputGroup-sizing-default" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="email" class="form-control" placeholder="E-mail" name="email" aria-label="Username" aria-describedby="basic-addon1 " value="<?php echo $_SESSION['Correo'] ;  ?>">
                </div>
                <div class="input-group mb-3 dinero">
                    <input type="text" class="form-control"  aria-label="Username" aria-describedby="basic-addon1" value="<?php echo"$total"?>" readonly>
                    <span class="input-group-text" id="basic-addon1">€</span>
                </div>

                <button class='btn btn-primary' type="submit">Pagar</button>

            </form>

        </div>

    </main>
    <script>
         /* script que controla el input donde metes la tarjeta  */
        document.addEventListener('DOMContentLoaded', function () {
            const tarjetaInput = document.querySelector('input[name="tarjeta"]');
            
            tarjetaInput.addEventListener('input', function() {
                let valor = this.value.replace(/\D/g, ''); // Eliminar caracteres que no son dígitos
                let formateado = '';

                for (let i = 0; i < valor.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formateado += '-';
                    }
                    formateado += valor[i];
                }

                this.value = formateado.substring(0, 19);
            });
        });
    </script> 
</body>

</html>