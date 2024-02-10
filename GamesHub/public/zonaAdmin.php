    <?php
        
        session_start();
        /* si no tienes el rol de adim te redirige a la pagina principal */
         if((!isset($_SESSION["Rol"]) || ($_SESSION["Rol"]!=0))){
            header("Location:main.php");
         }
         /* SI se ha recargado con un post la linea recarga la pagina para eliminar esos post y asi evitar errores */
         if ($_SERVER["REQUEST_METHOD"] == "POST" && !(isset($_POST["actualizar"]) || isset($_POST["mostrar"]))) {
            echo "<meta http-equiv='Refresh' content='1.8; URL=zonaAdmin.php'>";
        }
        


        $cadena_conexion = "mysql:dbname=gameshub;host=127.0.0.1";
        $usuario = "root";
        $clave = "";
        $db = new PDO($cadena_conexion, $usuario, $clave);
        
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/zona-admin.css">
    <title>Document</title>
</head>
<body data-bs-theme="dark">
<aside>
        <div class="logo"><img src="../img/logo.png"></div>
<div class="patras">
    <span>
        <b><a class="nav-link" href="../public/main.php"><img src="../img/casa_blanca.png"></a> <span><----pagina principal</span></b>
    </span>

</div>

<h1>Zona De Administración</h1>
</aside>

<main>
<h1>Mostrar tabla</h1>
<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<select name="option">   <!-- esta linea me sirve para mantener la opcion que habia seleccionado despues de recargar-->
    <option name="option" <?php if(isset($_POST["mostrar"])){echo ( $_POST["option"] === 'productos') ? 'selected' : '';} ?> value="productos">Producto</option>
    <option name="option" <?php if(isset($_POST["mostrar"])){echo ( $_POST["option"] === 'Claves') ? 'selected' : '';} ?> value="Claves">Claves</option>
    <option name="option" <?php if(isset($_POST["mostrar"])){echo ( $_POST["option"] === 'categoria') ? 'selected' : '';} ?> value="categoria">Categorias</option>
    <option name="option" <?php if(isset($_POST["mostrar"])){echo ( $_POST["option"] === 'usuarios') ? 'selected' : '';} ?> value="usuarios">usuarios</option>
</select>

    <button name="mostrar" type="submit">Mostrar tabla</button>
    <br>
    <br>
</form>

<?php 


    if(isset($_POST["mostrar"])){

            /* consulta para sacar el nombre de las columnas de la tabla seleccionada en el select */
        $datos_tabla=$db->prepare("SHOW COLUMNS FROM ".$_POST["option"] );
        $datos_tabla->execute(array());
        
    echo"<div class='tabla'>
    <h1>Tabla de la Base de Datos</h1>
    <table>
        <tr>
            "; foreach($datos_tabla as $filas)//se imprimen los nombres de las columnas
            {
                echo "<td>".$filas["Field"]."</td>";
            };"

        </tr>
        ";

            /* se hace una consulta para sacar todos los datos de una tabla para posteriormente mostrarlos */
        $usuarios = $db->prepare("SELECT *  FROM ".$_POST["option"] );
        $usuarios->execute(array());

        /* con el foreach imprimo cada fila de la tabla */
        foreach ($usuarios as $filas) {
            $contador = count($filas);//guardo el numero de columnas de la tabla
            echo "<tr>";

            /* el for se va a repetir en relacion al numero de columnas que halla */
            for($i=0;$i<$contador;$i++){

                if(isset($filas[$i]))
                {
                    echo "<td><div>" . $filas[$i] . "</div></td>";//Se muetran los datos de cada columna
                };    
            } 
             echo "</tr>  ";
        }
    echo "</table></div>";

    }
?>

<br>
<h1>Añadir producto</h1>
<br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="contenedor">
            <div>
            <label>Nombre</label>
            <br>
            <input name="nombre" type="text" required>
            </div>
            <div>
            <label>Descripcion</label>
            <br>
            <textarea name="descripcion" required></textarea>
            </div>
            <div>
            <label>Precio</label>
            <br>
            <input name="precio" type="text" required>
            </div>
            <div>
            <label>Categoria</label>
            <br>
            <input name="categoria" type="text" required pattern="^[0-9]+$">
            </div>
        </div>
        <br>
        <div>
            <button name="añadir_pro" type="submit">Añadir </button>
        </div>

        <?php 
            if(isset($_POST["añadir_pro"])){
                    try{
                        /* inserto los datos del producto en relacion a los datos que me ha pasado el formulario */
                        $usuarios = $db->prepare("INSERT into productos(Precio,Categoria,Descripcion,Nombre) VALUES (:Precio, :Categoria, :Descripcion, :Nombre)");
                        $usuarios->execute(array(":Precio" => $_POST["precio"], ":Categoria" => $_POST["categoria"], ":Descripcion" => $_POST["descripcion"], ":Nombre" => $_POST["nombre"]));
                        
                        //este echo muestra un mensaje positivo si se realiza la accion correctamente
                        echo "<div class='fade-in-out-verde show'><p>Añadido correctamente</p></div>";
            
                    }catch(PDOException $e){

                        //en caso de que salga mal se mostrará un mensaje error 
                        echo "<div class='fade-in-out show'><p>Error al añadir</p></div>";
                    }  
            }
        ?>
        
    </form>
    <br>
    <h1>Añadir Clave</h1>
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="contenedor">
            <div>
            <label>Clave</label>
            <br>
            <input name="Clave" class="clave" type="text" placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXX" required pattern = '^[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}$'>
            </div>
            <div>
            <label>Id Del Producto</label>
            <br>
            <input name="id_producto" type="text" pattern="^[0-9]+$" required>
            </div>
        </div>
        <br>
        <div>
            <button name="añadir_cla" value="clave" type="submit">Añadir </button>
        </div>
        
        <?php 
            if(isset($_POST["añadir_cla"])){
                    try{

                        /* inserto en la base de datos la clave con los datos que me proporciona el post */
                        $usuarios = $db->prepare("INSERT into claves(clave,id_producto) VALUES (:clave, :id_producto)");
                        $usuarios->execute(array(":clave" => $_POST["Clave"], ":id_producto" => $_POST["id_producto"]));


                         //este echo muestra un mensaje positivo si se realiza la accion correctamente
                        echo "<div class='fade-in-out-verde show'><p>Añadido correctamente</p></div>";
            
                    }catch(PDOException $e){

                        //en caso de que salga mal se mostrará un mensaje error 
                        echo "<div class='fade-in-out show'><p>Error al añadir</p></div>";
                    }  
            }
        ?>
        
    </form>

    <br>

    <section>
    <div>
        <h1>Añadir Categoria/Eliminar Categoria</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="contenedor categoria    ">
                <div>
                <label>Nombre de la Categoria</label>
                <br>
                <input name="categoria" type="text" required >
                </div>
            </div>
            <br>
            <div>
                <button name="añadir_ca" type="submit">Añadir </button>
                <button name="eliminar_ca" type="submit">Eliminar </button>
            </div>

            <?php 
                if(isset($_POST["añadir_ca"])){
                        try{

                            /* inserto en la base de datos la categoria con los datos que me proporciona el post */
                            $usuarios = $db->prepare("INSERT into categoria(Nombre) VALUES (:Nombre)");
                            $usuarios->execute(array(":Nombre" => $_POST["categoria"]));

                            //este echo muestra un mensaje positivo si se realiza la accion correctamente
                            echo "<div class='fade-in-out-verde show'><p>Añadido correctamente</p></div>";
                            
                        }catch(PDOException $e){

                            //en caso de que salga mal se mostrará un mensaje error 
                            echo "<div class='fade-in-out show'><p>Error al añadir</p></div>";
                        }  
                }
                if(isset($_POST["eliminar_ca"])){
                    try{
                        $usuarios = $db->prepare("DELETE FROM categoria WHERE Nombre = ? LIMIT 1");
                        $usuarios->execute(array( $_POST["categoria"]));
                        echo "<div class='fade-in-out-verde show'><p>Eliminado correctamente</p></div>";
            
                    }catch(PDOException $e){
                        echo "<div class='fade-in-out show'><p>Error al eliminar</p></div>";
                    }  
            }
            ?>
            
        </form>
    </div>
    <div>
    <h1>Eliminar Producto</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="contenedor categoria">
            <div>
            <label>Id Del Producto</label>
            <br>
            <input name="id" type="text" pattern="^[0-9]+$" required>
            </div>
            <div>
            <label>Nombre Del Producto</label>
            <br>
            <input name="nombre" type="text"  required>
            </div>
        </div>
        <br>
        <div>
            <button name="eliminar_pro" type="submit" required>Eliminar </button>
        </div>

        <?php 

            if(isset($_POST["eliminar_pro"])){
                try{
                    $usuarios = $db->prepare("DELETE FROM productos WHERE Nombre = ? AND ID = ?");
                    $usuarios->execute(array( $_POST["nombre"],$_POST["id"]));
                    echo "<div class='fade-in-out-verde show'><p>Eliminado correctamente</p></div>";
        
                }catch(PDOException $e){

                    echo "<div class='fade-in-out show'><p>Error al eliminar</p></div>";
                }  
        }
        ?>
        
    </form>
    </div>
    <br>
    <br>
    <div>
    <h1>Eliminar Clave</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="contenedor categoria">
            <div>
            <label>Id De la Clave</label>
            <br>
            <input name="clave" type="text" pattern="^[0-9]+$" required>
            </div>
            <div>
            <label>ID del producto</label>
            <br>
            <input name="producto" type="text" required>
            </div>
        </div>
        <br>
        <div>
            <button name="eliminar_cla" type="submit" required>Eliminar </button>
        </div>

        <?php 

            if(isset($_POST["eliminar_cla"])){
                try{
                    $db->beginTransaction();
                    $usuarios = $db->prepare("DELETE FROM clave WHERE ID = ? AND id_producto = ?");
                    $usuarios->execute(array( $_POST["clave"],$_POST["producto"]));
                    $db->commit() ; 
                    unset($_POST["eliminar_cla"]);

                    echo "<div class='fade-in-out-verde show'><p>Eliminado correctamente</p></div>";
        
                }catch(PDOException $e){

                    $db->rollBack();

                    echo "<div class='fade-in-out show'><p>Error al eliminar</p></div>";
                }  
        }
        ?>
        
    </form>
    </div>
    </section>
    <br>
    <br>
    <div>
        <h1>Modificar Tabla</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <select name="option">   <!-- esta linea me sirve para mantener la opcion que habia seleccionado despues de recargar-->
            <option name="option" <?php if(isset($_POST["actualizar"])){echo ( $_POST["option"] === 'productos') ? 'selected' : '';} ?> value="productos">Producto</option>
            <option name="option" <?php if(isset($_POST["actualizar"])){echo ( $_POST["option"] === 'Claves') ? 'selected' : '';} ?> value="Claves">Claves</option>
            <option name="option" <?php if(isset($_POST["actualizar"])){echo ( $_POST["option"] === 'categoria') ? 'selected' : '';} ?> value="categoria">Categorias</option>
            <option name="option" <?php if(isset($_POST["actualizar"])){echo ( $_POST["option"] === 'usuarios') ? 'selected' : '';} ?> value="usuarios">usuarios</option>
        </select>

    <button name="actualizar" type="submit">Seleccione Tabla</button>
    </form>
    <?php 
        if(isset($_POST["actualizar"])){
            /* hago la consulta que me muestra el nombre de las tablas */
            $datos_tabla = $db->prepare("SHOW COLUMNS FROM " . $_POST["option"]);
            $datos_tabla->execute();


            /* creo una etiquieta select en relacion a los datos que me ha pasado la anterior consulta
            para asi mostrar las distintas tablas */
            echo "<br><br>            
            <form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method='post'>
            <select name='opcion'>";  
            /* creo tantos post con etiqueta <option> como nombres de columnas halla obtenido de la consulta
            exceptuando el ID por que no quiero que se muestre */
            foreach ($datos_tabla as $filas) {
                if ($filas["Field"] !== "ID") { 
                    echo "<option name='opcion' value='" . $filas["Field"] . "'>" . $filas["Field"] . "</option>";
                }
            }
            /* creo el formulario que se utilizará para actualizar los campos */
            echo "</select>
            <br>
            <br>
            <div>
            <label>Dato al que se va a actualziar</label>
            <input type='text' name='cambio'required>  
            </div>
            <br>
            <div>
            <label>ID del dato que se quiere cambiar</label>
            <input type='text' name='id'required>  
            </div>
            <br>
            <button name='act_dato' value='".$_POST["option"]."' type='submit' >Actualizar dato</button>
            </form>";//le doy el valor $POST["option"] al boton para quedarme con la columna seleccionada
        }

        if (isset($_POST["act_dato"])) {
            try {
                $nombreTabla = $_POST["act_dato"];
                $nombreColumna = $_POST["opcion"];
                $nuevoValor = $_POST["cambio"];
                $idDato = $_POST["id"];
        
                $sql = "UPDATE $nombreTabla SET $nombreColumna = ? WHERE ID = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$nuevoValor, $idDato]);
        
                echo "<div class='fade-in-out-verde show'><p>Dato actualizado correctamente</p></div>";
            } catch (PDOException $e) {
                echo "<div class='fade-in-out show'><p>Error al intentar actualizar</p></div>";
            }
        }
    ?>
    <br>
    <br>
    </div>
</main>
</body>
</html>