<?php
    $host = "localhost";
    $bd = "pharmasmart";
    $user = "root";
    $pass = "";
    
    try{
        $conec = new PDO("mysql:host=$host;dbname=$bd; charset=utf8",$user,$pass);
        $conec ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }catch(PDOException $error){
        echo "erro: ".$error->getMessage();
    }

?>