<?php 
    include("conection.php");
    $mysqli = bd_conection(); //Llamado a la funcion de conexion

    if(isset($_REQUEST['email'])) { 
        $user_name = stripslashes($_REQUEST['user_name']);
        $user_name = mysqli_real_escape_string($mysqli, $user_name);
        $user_last_name = stripslashes($_REQUEST['user_last_name']);
        $user_last_name = mysqli_real_escape_string($mysqli, $user_last_name);
        $user_phone = stripslashes($_REQUEST['telefono']);
        $user_phone = mysqli_real_escape_string($mysqli, $user_phone);
        $user_address = stripslashes($_REQUEST['direccion']);
        $user_address = mysqli_real_escape_string($mysqli, $user_address);
        $email = stripslashes($_REQUEST['email']);
        $email = mysqli_real_escape_string($mysqli, $email);
        $passwd = stripslashes($_REQUEST['contrasena']);
        $passwd = mysqli_real_escape_string($mysqli, $passwd); 
        $rol = 2; //rol por defecto usuario normal o repartidor

        // Comprobar si el email ya existe
        $query = "SELECT * FROM usuarios WHERE email='$email'";
        $result = mysqli_query($mysqli, $query);

        if(mysqli_num_rows($result) > 0){
            // Para cuando el email ya esta registrado
            echo '<div class="alert alert-danger">El email ya está registrado</div>';
        } else {
            // Insertar nuevo usuario (asegurar que el número de columnas coincide con el de VALUES)
            $query = "INSERT INTO usuarios (nombre_usuario, apellido_usuario, email, contrasena, rol, telefono, direccion) VALUES ('$user_name','$user_last_name','$email', '$passwd', '$rol', '$user_phone','$user_address')"; // Ajustar columnas y valores
            $result = mysqli_query($mysqli, $query);
            if($result){
                echo '<div class="alert alert-success">Registro exitoso. <a href="login.php">Iniciar sesión</a></div>';
            } else {
                echo '<div class="alert alert-danger">Error en el registro: '. mysqli_error($mysqli) .'</div>';
            }
        }
    }
?>