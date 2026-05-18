<?php
include "conection.php";

if(!isset($_POST['id'])){
    die("ID não recebido");
}

$id = $_POST['id'];

$sql = "UPDATE notificacoes SET lida = 1 WHERE id = :id";
$stmt = $conec->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);

if($stmt->execute()){
    echo "OK";
}else{
    echo "ERRO";
}