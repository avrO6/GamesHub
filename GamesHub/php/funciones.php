<?php

/* En caso de que el dato que se inserte sea negativo la funcion lo cambia a 0 */
function controlar_negativos($dinero)
{
    if ($dinero < 0) {
        $dinero = 0;
    } else {
        $dinero = $dinero;
    }
    return $dinero;
};

/* funcion que utilizamos para calcular los puntos que damos al usuario despues de hacer una compra */
function calcular_puntos($dinero)
{
    $puntos = round($dinero * 0.10) * 100;
    return $puntos;
}

/* Esta funcion hace una consulta a la base de datos Para actualizar la variable de puntos  */
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

    /* funcion para añadir al carro pasandole a la funcion el id del producto */
function añadirAlCarrito($idProducto)
{

    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";
    $db = new PDO($cadena_conexion, $usuario, $contraseña);
    // Verificar si la ID del producto ya existe en el array
    if (array_key_exists($idProducto, $_SESSION["Carrito"])) {

        //Preguntamos a la base de datos la cantidad de productos que tenemos para comprobar si se puede añadir
        try{
            $consulta = $db->prepare("SELECT clave FROM claves WHERE id_producto = ? ");
            $consulta->execute(array($idProducto));
            $consulta->fetchAll();
            if($consulta->rowCount() <= $_SESSION["Carrito"][$idProducto]){
                echo "<div class='fade-in-out show'><p>No hay stock del producto elegido</p></div>";
                echo "<meta http-equiv='refresh' content='1.8;url=main.php'>";
               
            }else{
                
                $_SESSION["Carrito"][$idProducto]++;
                echo "<meta http-equiv='refresh' content='0;url=main.php'>";
                
            }

        }catch(PDOException $e){
            
            echo "<p>Error al añadir  Producto</p>";
        }      

    } else {
        // Si no existe, agregamos un nuevo elemento con valor 1
        $consulta = $db->prepare("SELECT clave FROM claves WHERE id_producto = ? ");
        $consulta->execute(array($idProducto));
        $consulta->fetchAll();

        if($consulta->rowCount()==0){

            echo "<div class='fade-in-out show'><p>No hay stock del producto elegido</p></div>";
           
        }else{
            
            echo "<meta http-equiv='refresh' content='0;url=main.php'>";
            $_SESSION["Carrito"][$idProducto] = 1;
            
        }
        
    }

}


/* Funcion de Hector solo mirar */
function insertarpedido($carrito, $codRes){
    $res =  "algo"/* leerconfig(dirname(FILE)."/configuracion.xml", dirname(__FILE)."/configuracion.xsd") */;
    $bd = new PDO($res[0], $res[1], $res[2]);
    $bd->beginTransaction();
    $hora = date("Y-m-d H:i:s", time());
    // insertar el pedido
    $sql = "insert into pedidos(fecha, enviado, restaurante) 
            values('$hora',0, $codRes)";
    $resul = $bd->query($sql);
    if (!$resul) {
        return FALSE;
    }
    // coger el id del nuevo pedido para las filas detalle
    $pedido = $bd->lastInsertId();
    // insertar las filas en pedidoproductos
    foreach($carrito as $codProd=>$unidades){
        $sql = "insert into pedidosproductos(Pedido, Producto, Unidades) 
                     values( $pedido, $codProd, $unidades)";
         $resul = $bd->query($sql); 
        if (!$resul) {
            $bd->rollback();
            return FALSE;
        }
    }
    $bd->commit();
    return $pedido;
}
