<?php
session_start();
include "conection.php";

if (!isset($_SESSION['user'])) {
    exit("Usuário não autenticado");
}

$user = $_SESSION['user'];

$user_id = $user['id'];
$item_id = $_POST['item_id']; 

$sql = "DELETE FROM carrinho 
        WHERE id = :id 
        AND usuario_id = :usuario_id";

$stmt = $conec->prepare($sql);
$stmt->bindParam(':id', $item_id);
$stmt->bindParam(':usuario_id', $user_id);
$stmt->execute();
header("Location: ../pages/carrinho.php");
?>