<?php

session_start();
include "../funcoes/conection.php";

$user_id = $_SESSION['user']['id'];

$produto_id = $_POST['produto_id'];

$sql = "SELECT * FROM carrinho
        WHERE usuario_id = :usuario_id
        AND medicamento_id = :medicamento_id";

$stmt = $conec->prepare($sql);

$stmt->execute([
    ":usuario_id" => $user_id,
    ":medicamento_id" => $produto_id
]);

$item = $stmt->fetch();

if($item){

    $sql = "UPDATE carrinho
            SET quantidade = quantidade + 1
            WHERE id = :id";

    $stmt = $conec->prepare($sql);

    $stmt->execute([
        ":id" => $item['id']
    ]);

}else{

    $sql = "INSERT INTO carrinho
            (usuario_id, medicamento_id, quantidade)
            VALUES
            (:usuario_id, :medicamento_id, 1)";

    $stmt = $conec->prepare($sql);

    $stmt->execute([
        ":usuario_id" => $user_id,
        ":medicamento_id" => $produto_id
    ]);
}

header("Location: carrinho.php");