<?php 
    function bd_conection(){    
        $host = "localhost";
        $user = "root";
        $passwd = "";
        $db = "tinburguer";
        $puerto = "3307";

        $conection = mysqli_connect($host, $user, $passwd, $db, $puerto);
        if(!$conection){
            echo "Error en la conexion con la base de datos";
            exit();
        }
        return $conection;

        echo "Conexion exitosa";
        mysqli_close($conection);   
    }
?>