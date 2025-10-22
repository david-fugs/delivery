<?php
    session_start(); // Reanuda la sesión actual
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // mata  la sesión completamente

    header("Location: index.php"); // Redirige al inicio o al login
    exit();
?>