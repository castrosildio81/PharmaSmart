<?php
    session_start();
    include 'conection.php';
    $receita_id = $_SESSION['receita_id'];
    $texto = $_SESSION['ocr_texto'];
    $user = $_SESSION["user"];
    $user_id = $user['id'];
     



    $sql = "INSERT into receitas_txt (receita_id, texto) 
    values (:receita_id, :texto)";

    $stmt = $conec->prepare($sql);
    $stmt->bindParam(':receita_id', $receita_id);
    $stmt->bindParam(":texto", $texto);
    $stmt->execute();

    $mensagem = "Receita adicionada com sucesso!";
    $tipo = "sucesso";

    $sql = "INSERT INTO notificacoes (usuario_id, tipo, mensagem, lida, data_criacao)
            VALUES (:usuario_id, :tipo, :mensagem, 0, now())";
    $stmt = $conec->prepare($sql);
    $stmt->bindParam(":usuario_id", $user_id);
    $stmt->bindParam(":tipo", $tipo);
    $stmt->bindParam(":mensagem", $mensagem);
    $stmt->execute();
    
    unset($_SESSION['receita_id']);
    unset($_SESSION['ocr_texto']);
    
    $msg = "Receita enviada com sucesso!";
    $_SESSION['msg'] = $msg;

   header("Location: ../pages/nova_receita.php");

   exit;

?>