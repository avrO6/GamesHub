    <?php
        
        session_start();

         if((!isset($_SESSION["Rol"]) || ($_SESSION["Rol"]!=0))){
            header("Location:main.php");
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

<div >
    <span>
        <b><a class="nav-link" href="../public/main.php">Pagina principal</a></b>
    </span>

</div>

</aside>

<main>
<h1>Mostrar tabla</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <select name="opcion">
        <option name="opcion" value="productos">Producto</option>
        <option name="opcion" value="Claves">Claves</option>
        <option name="opcion" value="categoria">Categorias</option>
    </select>

    <button name="mostrar" type="submit">Mostrar tabla</button>
</form>

<?php 


    if(isset($_POST["mostrar"])){

        $datos_tabla=$db->prepare("SHOW COLUMNS FROM ".$_POST["opcion"] );
        $datos_tabla->execute(array());
        
    echo"<div class='tabla'>
    <h1>Tabla de la Base de Datos</h1>
    <table>
        <tr>
            "; foreach($datos_tabla as $filas){
                echo "<td>".$filas["Field"]."</td>";
            };"
            
            <td>Nombre</td>
            <td>Clave</td>
            <td>Rol</td>
        </tr>
        ";

        $usuarios = $db->prepare("SELECT *  FROM ".$_POST["opcion"] );
        $usuarios->execute(array());

        foreach ($usuarios as $filas) {
            echo "<tr>";
            echo "<td>" . $filas[0] . "</td>";  
            echo "<td>" . $filas[1] . "</td>";
            if(isset($filas[2])){ echo "<td><div class='descrpipcion'>" . $filas[2] . "</div></td>";};
             if(isset($filas[3])){ echo "<td><div class='descrpipcion'>" . $filas[3] . "</div></td>";}; 
             if(isset($filas[4])){ echo "<td><div class='descrpipcion'>" . $filas[4] . "</div></td>";}; 
             echo "</tr>";
        }
    echo "</table></div>";

    }
?>

<br>
<h1>Añadir producto</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="contenedor">
            <div>
            <label>Nombre</label>
            <br>
            <input name="nombre" type="text">
            </div>
            <div>
            <label>Descripcion</label>
            <br>
            <textarea name="descripcion"></textarea>
            </div>
            <div>
            <label>Precio</label>
            <br>
            <input name="precio" type="text">
            </div>
            <div>
            <label>Categoria</label>
            <br>
            <input name="categoria" type="text" pattern="^[0-9]+$">
            </div>
        </div>
        <br>
        <div>
            <button name="añadir_c" type="submit">Añadir </button>
        </div>

        <?php 
            if(isset($_POST["añadir_c"])){
                    try{
                        $db->beginTransaction();

                        
                        $usuarios = $db->prepare("INSERT into productos(Precio,Categoria,Descripcion,Nombre) VALUES (:Precio, :Categoria, :Descripcion, :Nombre)");
                        $usuarios->execute(array(":Precio" => $_POST["precio"], ":Categoria" => $_POST["categoria"], ":Descripcion" => $_POST["descripcion"], ":Nombre" => $_POST["nombre"]));
                        $db->commit() ; 
                        echo"<p>Insertado correctamente</p>";
            
                    }catch(PDOException $e){

                        $db->rollBack();

                        echo "<p>Error al insertar producto</p>"/* .$e->getMessage() */;
                    }  
            }
        ?>
        
    </form>
    <br>
    <h1>Añadir Clave</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="contenedor">
            <div>
            <label>Clave</label>
            <br>
            <input name="Clave" class="clave" type="text" placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXX" pattern = '^[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}-[A-Z0-9]{5}$'>
            </div>
            <div>
            <label>Id Del Producto</label>
            <br>
            <input name="id_producto" type="text" pattern="^[0-9]+$">
            </div>
        </div>
        <br>
        <div>
            <button name="añadir_p" type="submit">Añadir </button>
        </div>

        <?php 
            if(isset($_POST["añadir_p"])){
                    try{
                        $db->beginTransaction();

                        
                        $usuarios = $db->prepare("INSERT into claves(clave,id_producto) VALUES (:clave, :id_producto)");
                        $usuarios->execute(array(":clave" => $_POST["Clave"], ":id_producto" => $_POST["id_producto"]));
                        $db->commit() ; 
                        echo"<p>Insertado correctamente</p>";
            
                    }catch(PDOException $e){

                        $db->rollBack();

                        echo "<p>Error al insertar producto</p>"/* .$e->getMessage() */;
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
                <input name="categoria" type="text" >
                </div>
            </div>
            <br>
            <div>
                <button name="añadir_c" type="submit">Añadir </button>
                <button name="eliminar_c" type="submit">Eliminar </button>
            </div>

            <?php 
                if(isset($_POST["añadir_c"])){
                        try{
                            $db->beginTransaction();
                            $usuarios = $db->prepare("INSERT into categoria(Nombre) VALUES (:Nombre)");
                            $usuarios->execute(array(":Nombre" => $_POST["categoria"]));
                            $db->commit() ; 
                            echo"<p>Insertado correctamente</p>";
                
                        }catch(PDOException $e){

                            $db->rollBack();

                            echo "<p>Error al insertar categoria</p>";
                        }  
                }
                if(isset($_POST["eliminar_C"])){
                    try{
                        $db->beginTransaction();
                        $usuarios = $db->prepare("DELETE FROM categoria WHERE Nombre = ? LIMIT 1");
                        $usuarios->execute(array( $_POST["categoria"]));
                        $db->commit() ; 
                        echo"<p>Eliminado correctamente</p>";
            
                    }catch(PDOException $e){

                        $db->rollBack();

                        echo "<p>Error al Eliminar categoria</p>";
                    }  
            }
            ?>
            
        </form>
    </div>
    <div>
    <h1>Eliminar Producto</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="contenedor categoria    ">
            <div>
            <label>Id Del Producto</label>
            <br>
            <input name="id" type="text" pattern="^[0-9]+$">
            </div>
            <div>
            <label>Nombre Del Producto</label>
            <br>
            <input name="nombre" type="text" >
            </div>
        </div>
        <br>
        <div>
            <button name="eliminar_p" type="submit">Eliminar </button>
        </div>

        <?php 

            if(isset($_POST["eliminar_p"])){
                try{
                    $db->beginTransaction();
                    $usuarios = $db->prepare("DELETE FROM producto WHERE Nombre = ? AND ID = ?");
                    $usuarios->execute(array( $_POST["nombre"],$_POST["id"]));
                    $db->commit() ; 
                    echo"<p>Eliminado correctamente</p>";
        
                }catch(PDOException $e){

                    $db->rollBack();

                    echo "<p>Error al Eliminar Producto</p>";
                }  
        }
        ?>
        
    </form>
    </div>
    <div>
    <h1>Eliminar Clave</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <div class="contenedor categoria    ">
            <div>
            <label>Id De la Clave</label>
            <br>
            <input name="clave" type="text" pattern="^[0-9]+$">
            </div>
            <div>
            <label>ID del producto</label>
            <br>
            <input name="producto" type="text" >
            </div>
        </div>
        <br>
        <div>
            <button name="eliminar_p" type="submit">Eliminar </button>
        </div>

        <?php 

            if(isset($_POST["eliminar_C"])){
                try{
                    $db->beginTransaction();
                    $usuarios = $db->prepare("DELETE FROM clave WHERE ID = ? AND id_producto = ?");
                    $usuarios->execute(array( $_POST["clave"],$_POST["producto"]));
                    $db->commit() ; 
                    echo"<p>Eliminado correctamente</p>";
        
                }catch(PDOException $e){

                    $db->rollBack();

                    echo "<p>Error al Eliminar Clave</p>";
                }  
        }
        ?>
        
    </form>
    </div>
    </section>
</main>
</body>
</html>