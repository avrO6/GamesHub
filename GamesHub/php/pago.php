    <?php  
        require "./mailer.php";

        $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
        $usuario = "root";
        $contraseña = "";
        
            $db = new PDO($cadena_conexion, $usuario, $contraseña);

        $arr1 = $_SESSION["Carrito"];
        $cuerpo_correo = [];

        foreach ($arr1 as $clave => $valor) {
            
            $consulta = $db->prepare( "SELECT clave FROM claves WHERE id_producto = ? LIMIT $valor");
            $consulta->execute(array($clave));   
            $producto = $consulta->fetchAll();
            $nombre = $db->prepare( "SELECT Nombre FROM productos WHERE ID = ? LIMIT 1");
            $nombre->execute(array($clave));
            $nombre = $nombre->fetch();
            

            $cuerpo_correo = array_merge($cuerpo_correo,["\n su clave para el juego: ".$nombre["Nombre"]." es: \n".$producto["clave"]."."]) ;
        }

        $cuerpo_correo_str = implode("", $cuerpo_correo);

        Enviar_correo($_SESSION["Correo"],$_SESSION["Name"],$cuerpo_correo_str);
    ?>