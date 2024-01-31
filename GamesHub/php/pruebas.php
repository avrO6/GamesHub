<?php
function añadirAlCarrito($idProducto)
{
    $_SESSION["Carrito"]= [];

    $arrayProductos = $_SESSION["Carrito"];
    // Verificar si la ID del producto ya existe en el array
    if (array_key_exists($idProducto, $arrayProductos)) {
        $arrayProductos[$idProducto]++;
    } else {
        // Si no existe, agregamos un nuevo elemento con valor 1
        $arrayProductos[$idProducto] = 1;
    }

    $_SESSION["Carrito"] = $arrayProductos;
}

        añadirAlCarrito(1);
        echo(count($_SESSION["Carrito"]));

?>