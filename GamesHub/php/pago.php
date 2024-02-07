<?php
    require "./mailer.php";

    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";

    $db = new PDO($cadena_conexion, $usuario, $contraseña);

    $arr1 = $_SESSION["Carrito"];
    $cuerpo_correo = [];

    function calcular_puntos($dinero)
    {
        $puntos = round(($dinero * 0.10) * 100);
        return $puntos;
    }

    function actualizar_puntos(){
        $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
        $usuario = "root";
        $contraseña = "";
    
        $db = new PDO($cadena_conexion, $usuario, $contraseña);
        $consulta = $db->prepare("SELECT puntos FROM usuarios WHERE correo = ? ");
        $consulta->execute(array($_SESSION["Correo"]));
        $consulta = $consulta->fetch();        
        
        $_SESSION["Puntos"] = $_SESSION['Puntos'] + $consulta["puntos"];
    }


    foreach ($arr1 as $clave => $valor) {

        $consulta = $db->prepare("SELECT clave FROM claves WHERE id_producto = ? LIMIT $valor");
        $consulta->execute(array($clave));
        $producto = $consulta->fetchAll();
        $nombre = $db->prepare("SELECT Nombre FROM productos WHERE ID = ? LIMIT 1");
        $nombre->execute(array($clave));
        $nombre = $nombre->fetch();

        foreach ($producto as $fila) {
            $cuerpo_correo = array_merge($cuerpo_correo, ["\n su clave para el juego: " . $nombre["Nombre"] . " es: \n" . $fila["clave"]   . "."]);
        }
    }

    $cuerpo_correo_str = implode(" \n", $cuerpo_correo);



    $puntos = calcular_puntos($_SESSION["total"]);

    $dinero = $db->prepare("UPDATE usuarios SET puntos=? where Name=?");
    $dinero->execute(array($puntos, $_SESSION["Name"]));


    Enviar_correo($_SESSION["Correo"], $_SESSION["Name"], $cuerpo_correo_str);
    actualizar_puntos();
?>
