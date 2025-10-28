<?php   
    function bd_conection(){    
        $host = "localhost";
        $user = "root";
        $passwd = "";
        $db = "tinburguer";
        $puerto = "3306"; //david
        //$puerto = "3307"; //gio cambia a este puerto

        $conection = mysqli_connect($host, $user, $passwd, $db, $puerto);
        if(!$conection){
            echo "Error en la conexion con la base de datos";
            exit();
        }
        return $conection;
        mysqli_close($conection);
  
    }
?>