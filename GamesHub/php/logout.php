<?php

    session_start();

    $_SESSION = array();

    session_destroy();

    setcookie(session_name(),1,time() -1000);

    header("Location: ../public/main.php");

?>