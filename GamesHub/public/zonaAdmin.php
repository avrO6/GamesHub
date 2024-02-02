    <?php
        
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

        $usuarios = $db->prepare("SELECT *  FROM ".$_POST["opcion"] );
        $usuarios->execute(array());
        
      /*   $datos_tabla->$db->select COLUMN_NAME
        from INFORMATION_SCHEMA.COLUMNS
       where TABLE_SCHEMA = 'dbo'
         and TABLE_NAME = 'Prueba'
       order by ORDINAL_POSITION */
        
    echo"<div class='tabla'>
    <h1>Tabla de la Base de Datos</h1>
    <table>
        <tr>
            <td>".$_POST["opcion"]."</td>
            <td>Nombre</td>
            <td>Clave</td>
            <td>Rol</td>
        </tr>
        ";
        //  Muestra los datos en una tabla HTML.
        foreach ($usuarios as $filas) {
            echo "<tr>";
            echo "<td>" . $filas[0] . "</td>";  
            echo "<td>" . $filas[1] . "</td>";
            echo "<td>" . $filas[2] . "</td>";
             if(isset($filas[3])){ echo "<td><div class='descrpipcion'>" . $filas[3] . "</div></td>";}; 
             if(isset($filas[4])){ echo "<td><div class='descrpipcion'>" . $filas[4] . "</div></td>";}; 
             echo "</tr>";
        }
    echo "</table></div>";

    }
?>


<h1>Añadir producto</h1>
    <form>
        <div class="contenedor">
            <div>
            <label>Nombre</label>
            <br>
            <input name="nombre" type="text">
            </div>
            <div>
            <label>Descripcion</label>
            <br>
            <textarea></textarea>
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
            <button name="añadir" type="submit">Añadir </button>
        </div>
        
    </form>
</main>
</body>
</html>