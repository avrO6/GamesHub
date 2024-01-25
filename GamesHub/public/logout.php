<?php
/*Para poder cerrar la sesion se deben eliminar todos los atributos del array de lasesion */
$_SESSION = array();
/*Además, se debe utilizar la función session_destroy(): */
session_destroy();
/*Por último, se elimina la cookie: */
setcookie(session_name(),123,time()-1000);
/*Finalmente el script lleva de nuevo al main: */
header("Location: main.php");
?>
