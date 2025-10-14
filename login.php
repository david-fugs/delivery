<?php
include("conection.php");
$mysqli = bd_conection(); //Llamado a la funcion de conexion
if(!empty($_POST["btn-login"])){ //Si el boton de login fue presionado y no hay campos vacios
    if (empty($_POST["email"]) && empty($_POST["contrasena"])) {
        echo '<div class="alert alert-danger">Los Campos Estan Vacios</div>';
    } else {
        $email = $_POST["email"];
        $passwd = $_POST["contrasena"];
        $sql = $mysqli -> prepare("SELECT * FROM usuarios WHERE email = ? AND contrasena  = ? "); //prepare usado como buena practica para evitar inyeccion sql, ? se usan como marcadores de posicion
        $sql -> bind_param("ss", $email, $passwd); //ss por que son dos strings, $email y $passwd son las variables que iran en los marcadores de posicion
        $sql -> execute(); //ejecuta la consulta 
        $resultado = $sql -> get_result(); //obtiene el resultado de la consulta

        if ($resultado && $datos = $resultado->fetch_object()) { //condicional que verifica si la consulta devolvio algun resultado
            header("location: prueba.php");
            exit();
        } else {
            echo '<div class="alert alert-danger">Acceso denegado</div>';
        }
        
    }
    
}


?>