<?php
include "conection.php";

if(!isset($_POST['id'])){
    die("ID não recebido");
}

$id = $_POST['id'];

$sql = "DELETE FROM notificacoes WHERE id = :id";
$stmt = $conec->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);

if($stmt->execute()){
    echo "OK";
}else{
    echo "ERRO";
}
?>