<?php
function controlar_negativos($dinero)
{
    if ($dinero < 0) {
        $dinero = 0;
    } else {
        $dinero = $dinero;
    }
    return $dinero;
};

function calcular_puntos($dinero)
{
    $puntos = round($dinero * 0.10) * 100;
    return $puntos;
}

function actualizar_puntos()
{
    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";

    $db = new PDO($cadena_conexion, $usuario, $contraseña);
    $consulta = $db->prepare("SELECT puntos FROM usuarios WHERE correo = ? ");
    $consulta->execute(array($_SESSION["Correo"]));
    $consulta = $consulta->fetch();

    $_SESSION["Puntos"] =  $consulta["puntos"];
}

function añadirAlCarrito($idProducto)
{
    // Verificar si la ID del producto ya existe en el array
    if (array_key_exists($idProducto, $_SESSION["Carrito"])) {

        $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
        $usuario = "root";
        $contraseña = "";
        $db = new PDO($cadena_conexion, $usuario, $contraseña);

        try{
            $consulta = $db->prepare("SELECT clave FROM claves WHERE id_producto = ? ");
            $consulta->execute(array($idProducto));
            $consulta->fetchAll();
            if($consulta->rowCount() <= $_SESSION["Carrito"][$idProducto]){
                $_SESSION['alert'] = true;
            }else{
                $_SESSION["Carrito"][$idProducto]++;
            }
        }catch(PDOException $e){

            echo "<p>Error al añadir  Producto</p>";
        }      

    } else {
        // Si no existe, agregamos un nuevo elemento con valor 1
        $_SESSION["Carrito"][$idProducto] = 1;
    }
}
