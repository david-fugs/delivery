<?php
    include("main_header.php");
    session_start(); // Inicia la sesión o la continúa 
    if (!isset($_SESSION["id_user"])) {
        // Si no hay sesión iniciada, redirige al login
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 d-md-none">
                <!-- Espacio para desarrollo dashboard admin movil -->
            </div>
            <div class="col-d-sm-none d-md-block"></div>
        </div>
    </div>
    <a href="log_out.php" class="btn btn-danger"><i class="fa-solid fa-power-off"></i></a>
</body>

</html>