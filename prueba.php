<?php
    session_start(); // Inicia la sesión o la continúa
    if (!isset($_SESSION["id_user"])) {
        // Si no hay sesión iniciada, redirige al login
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="color: #ff00ff; font-size: 4rem;">esta es una prueba de inicio</div>

    <a href="log_out.php" class="btn btn-danger">Cerrar sesión</a>
</body>
</html>