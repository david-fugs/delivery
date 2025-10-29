    <?php
    session_start(); // Inicia sesión
    ob_start(); //Iniciar el buffer de salida
    include("conection.php");
    $mysqli = bd_conection(); //Llamado a la funcion de conexion
    
    if(!empty($_POST["btn-login"])){ //Si el boton de login fue presionado y no hay campos vacios
        if (empty($_POST["email"]) || empty($_POST["contrasena"])) {
            echo '<div class="alert alert-danger">Los Campos Están Vacíos</div>';
        } else {
            $email = $_POST["email"];
            $passwd = $_POST["contrasena"];
            $roles = [1 => "main_admin.php", 2 => "main_user_dash.php"];
            
            $sql = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ? AND contrasena = ?");
            $sql->bind_param("ss", $email, $passwd);
            $sql->execute();
            $resultado = $sql->get_result();

            if ($resultado && $datos = $resultado->fetch_object()) {
                // Guardar datos en la sesión
                $_SESSION["id_user"] = $datos->id_user;
                $_SESSION["user_name"] = $datos->nombre_usuario;
                $_SESSION["user_email"] = $datos->email;
                $_SESSION["user_rol"] = $datos->rol;

                // Redirigir según el rol
                if(array_key_exists($datos->rol, $roles)){
                    header("Location: " . $roles[$datos->rol]);
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                echo '<div class="alert alert-danger">Acceso denegado. Email o contraseña incorrectos.</div>';
            }
        }
    } 
    ob_end_flush(); //Finaliza el buffer de salida y envía el contenido al navegador
?>