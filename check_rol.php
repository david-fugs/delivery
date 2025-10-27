<?php 
    include('conection.php');
    if(in_array($row['rol']) === 2) {
        header("location: main_user_dash.php");
        exit();
    } else {
        header("location: index.php");
        echo "regrese al inicio"
    }
?>