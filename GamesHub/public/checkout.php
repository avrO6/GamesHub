<?php  
    session_start();
    if(isset($_SESSION["total"])){
        if($_SESSION["total"]==0){
            header("location:carrito.php");
        }
    }
    if(isset($_POST["checkout"])){+
        $total = $_SESSION["total"];
        $descuento = $_SESSION["descuento"];
        if(isset($_POST["check"])){

            $total = $total-$descuento;
            $_SESSION["total"] = $total;
        }
    }
    $total = $_SESSION["total"];
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

            <form action="" method="post">

                <div class="input-group">
                    <span class="input-group-text">First and last name</span>
                    <input type="text" aria-label="First name" class="form-control" pattern="[A-Za-z]+" name="nombre" required>
                    <input type="text" aria-label="Last name" class="form-control" pattern="[A-Za-z]+" name="apellido" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Nº Targeta</span>
                    <input type="text" class="form-control" name="n-targeta" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" placeholder="1234-5678-9012-3456">
                    <span class="input-group-text" id="inputGroup-sizing-default">CVV</span>
                    <input type="text" class="form-control" name="cvv" aria-label="Sizing example input" pattern="[0-9]{3}" aria-describedby="inputGroup-sizing-default" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control" placeholder="E-mail" name="email" aria-label="Username" aria-describedby="basic-addon1 " value="<?php echo $_SESSION['Correo'] ;  ?>">
                </div>
                <div class="input-group mb-3 dinero">
                    <input type="text" class="form-control" name="email" aria-label="Username" aria-describedby="basic-addon1" value="<?php echo"$total"?>" readonly>
                    <span class="input-group-text" id="basic-addon1">€</span>
                </div>

                <button class='btn btn-primary' type="submit">Pagar</button>

            </form>

        </div>

    </main>
</body>

</html>