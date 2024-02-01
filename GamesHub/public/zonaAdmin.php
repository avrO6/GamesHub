    <?php
        
        $cadena_conexion = "mysql:dbname=empresa;host=127.0.0.1";
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
    <select >
        <option value="Producto">Producto</option>
        <option value="Claves">Claves</option>
        <option value="categoria">Categorias</option>
    </select>

    <button name="mostrar" type="submit">Mostrar tabla</button>
</form>

<?php 


    if(isset($_POST["mostrar"])){

        $usuarios = $db->prepare("SELECT codigo, nombre, clave, rol FROM ?");
        $usuarios->execute(array());
        
        
    echo"<div class='tabla'>
    <h1>Tabla de la Base de Datos</h1>
    <table>
        <tr>
            <td>Codigo</td>
            <td>Nombre</td>
            <td>Clave</td>
            <td>Rol</td>
        </tr>
        ";
        //  Muestra los datos en una tabla HTML.
        foreach ($usuarios as $filas) {
            echo "<tr>";
            echo "<td>" . $filas['codigo'] . "</td>";
            echo "<td>" . $filas['nombre'] . "</td>";
            echo "<td>" . $filas['clave'] . "</td>";
            echo "<td>" . $filas['rol'] . "</td>";
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