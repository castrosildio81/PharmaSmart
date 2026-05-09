<?php
    include 'conection.php';
    $id = $_POST['id'];
    $quantidade = $_POST['quantidade'];

    $sql = "SELECT estoque FROM medicamentos WHERE id = :id";
    $stmt = $conec->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto){ 

    // soma do stock
    $novo_estoque = $produto['estoque'] + $quantidade;

    // atualizar
    $update = "UPDATE medicamentos SET estoque = :estoque WHERE id = :id";
    $stmt2 = $conec->prepare($update);
    $stmt2->bindParam(':estoque', $novo_estoque);
    $stmt2->bindParam(':id', $id);
    $stmt2->execute();
}
    // voltar para tabela
    header("Location: ../pages/produtos.php");
    exit;
?>