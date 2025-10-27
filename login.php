    <?php
    session_start(); // Inicia sesión
    ob_start();//Iniciar el buffer de salida
    include("conection.php");
    $mysqli = bd_conection(); //Llamado a la funcion de conexion
    if(!empty($_POST["btn-login"])){ //Si el boton de login fue presionado y no hay campos vacios
        if (empty($_POST["email"]) || empty($_POST["contrasena"])) {
            echo '<div class="alert alert-danger">Los Campos Estan Vacios</div>';
        } else {
            $email = $_POST["email"];
            $passwd = $_POST["contrasena"];
            $roles = [1 => "main_admin.php", 2 => "main_user_dash.php"];
            $sql = $mysqli -> prepare("SELECT * FROM usuarios WHERE email = ? AND contrasena  = ?"); //prepare usado como buena practica para evitar inyeccion sql, ? se usan como marcadores de posicion
            $sql -> bind_param("ss", $email, $passwd); //ss por que son dos strings, $email y $passwd son las variables que iran en los marcadores de posicion
            $sql -> execute(); //ejecuta la consulta 
            $resultado = $sql -> get_result(); //obtiene el resultado de la consulta

            if ($resultado && $datos = $resultado->fetch_object()) { //condicional que verifica si la consulta devolvio algun resultado
                $_SESSION["id_user"] = $datos->id_user; // Guarda el ID del usuario en la sesión

                //Validar rol de usuario y vista
                /* if($datos -> rol == $user_rol){ */ // 1 forma de validar roles  asignados en variables diferentes...
                if(array_key_exists($datos -> rol, $roles)){// 2da forma de validar roles  dentro de un array...
                    var_dump($datos->rol);
                    header("location:" . $roles[$datos->rol]);
                    exit();
                }else {
                    header("location: index.php");
                }
            } else {
                echo '<div class="alert alert-danger">Acceso denegado</div>';
            }
            
        }
        
    } 
    ob_end_flush(); //Finaliza el buffer de salida y envía el contenido al navegador
?>