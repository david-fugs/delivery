<?php 
    include("conection.php");
    $mysqli = bd_conection(); //Llamado a la funcion de conexion

    if(isset(&REQUEST['email'])) {
        $email = stripslashes($_REQUEST['email']);
        $email = mysqli_real_escape_string($mysqli, $email);
        $passwd = stripslashes($_REQUEST['contrasena']);
        $passwd = mysqli_real_escape_string($mysqli, $passwd); 
        $rol = 2; //rol por defecto usuario normal o rapartidor

        // Comprobar si el email ya existe
        $query = "SELECT * FROM usuarios WHERE email='$email'";
        $result = mysqli_query($mysqli, $query);

        if(mysqli_num_rows($result) > 0){
            // Para cuando el email ya esta registrado
            echo '<div class="alert alert-danger">El email ya está registrado</div>';
        } else {
            // Insertar nuevo usuario
            $query = "INSERT INTO usuarios (email, contrasena, rol) VALUES ('$email', '$passwd', '$rol')";
            $result = mysqli_query($mysqli, $query);
            if($result){
                echo '<div class="alert alert-success">Registro exitoso. <a href="login.php">Iniciar sesión</a></div>';
            } else {
                echo '<div class="alert alert-danger">Error en el registro</div>';
            }
        }

        if($result){
            echo '<div class="alert alert-success">Registro exitoso. <a href="login.php">Iniciar sesión</a></div>';
        } else {
            echo '<div class="alert alert-danger">Error en el registro</div>'. mysqli_error($mysqli);
        }
    }
?>