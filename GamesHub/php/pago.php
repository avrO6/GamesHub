<?php
    require "./mailer.php";
    require "./funciones.php";

    $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
    $usuario = "root";
    $contraseña = "";
    $errmode = [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT];

    $db = new PDO($cadena_conexion, $usuario, $contraseña,$errmode);

    $arr1 = $_SESSION["Carrito"];
    $cuerpo_correo = ["Gracias por su compra ".$_POST["nombre"]." ".$_POST["apellido"]."."];

    try{
        $db->beginTransaction();
        $err = false;
        /* INSERTO LA COMPRA DEL USUARIO A LA BASE DE DATOS Y ME GUARDO SU ID */
        $compra = $db->prepare("INSERT INTO compras(User,fecha) Values (:User,:fecha) ");
        $result = $compra->execute(array( ":User" => $_SESSION["ID"],":fecha" => date("Y-m-d")));
        //la funcion lastInsertId recoge del ultimo insert el idañadido  generado por una columna autoincremental
        $id_compra = $db->lastInsertId(); 

        if(!$result){
            $err = true;
        }

        /* POR CADA PORDUCTO DEL CARRITO SACO UNA CLAVE RELACIONADA CON EL PRODUCTO ,INSERTO EL PRODUCTO EN LA TABLA DE JUEGOS COMPRADOS
        TAMBIEN VOY GENERANDO EL MENSAJE QUE VOY A MANDAR POR CORREO Y POR ULTIMO ELIMINO LA CLAVE SELECCIONADA */
        foreach ($arr1 as $clave => $valor) {

            $nombre = $db->prepare("SELECT Nombre  FROM productos WHERE ID = ? LIMIT 1");
            $result = $nombre->execute(array($clave));
            $nombre = $nombre->fetch();
            if(!$result){
                $err = true;
            }

            $consulta = $db->prepare("SELECT clave FROM claves WHERE id_producto = ? LIMIT $valor");
            $result = $consulta->execute(array($clave));
            $producto = $consulta->fetchAll();

            if(!$result){
                $err = true;
            }

            $compra_juego = $db->prepare("INSERT INTO compras_productos(N_compra,N_producto, Unidades ) Values (:N_compra,:N_producto,:Unidades) ");
            $result= $compra_juego->execute(array( ":N_compra" => $id_compra , ":N_producto" => $clave , ":Unidades"=>$valor));

            if(!$result){
                $err = true;
            }

            /* EN CASO DE QUE UN PRODUCTO SE PIDA MAS DE UNA VEZ */
            foreach ($producto as $fila) {
                $cuerpo_correo = array_merge($cuerpo_correo, ["\n Su clave para el juego " . $nombre["Nombre"] . " es: \n" . $fila["clave"]   . "."]);
/*                 $eliminar = $db->prepare("DELETE FROM claves WHERE clave = ? LIMIT 1"); 
                $result=$eliminar->execute(array($fila["clave"])); 
 */
                if(!$result){
                    $err = true;
                }
            }
        }


        $cuerpo_correo_str = implode(" \n", $cuerpo_correo);

        /* Actualizar puntos del ususario */
        $puntos = $_SESSION["Puntos"] +calcular_puntos($_SESSION["total"]);
        $dinero = $db->prepare("UPDATE usuarios SET puntos=? where Name=?");
        $result = $dinero->execute(array($puntos, $_SESSION["Name"]));
        
        if(!$result){
            $err = true;
        }

        if($err){
            $db->rollBack();
            header("location:../public/checkout.php?error=true");
        }

        $db->commit();
        Enviar_correo($_POST["email"], $_SESSION["Name"], $cuerpo_correo_str); 
        actualizar_puntos();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $db->rollBack();
        header("location:../public/checkout.php?error=true");
    }

?>
