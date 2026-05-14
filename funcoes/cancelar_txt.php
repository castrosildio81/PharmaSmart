<?php
    session_start();
    include 'conection.php';
    $receita_id = $_SESSION['receita_id'];
    

    // Buscar caminho da imagem
    $sql = "SELECT imagem FROM receitas WHERE id = :id";
    $stmt = $conec->prepare($sql);  
    $stmt->bindParam(":id", $receita_id);
    $stmt->execute();
    $dados = $stmt->fetch();

    if ($dados) {
    $imagem = $dados['imagem'];

    //  apagar ficheiro
    if (file_exists($imagem)) {
        unlink($imagem);
    }

    //  apagar registro
    $sql = "DELETE FROM receitas WHERE id = :id";
    $stmt = $conec->prepare($sql);
    $stmt->bindParam(":id", $receita_id);
    $stmt->execute();
}

// Limpar sessão
unset($_SESSION['receita_id']);
unset($_SESSION['ocr_texto']);

$msg = "Receita enviada com sucesso!";
$_SESSION['msg'] = $msg;

header("Location: pagina.php");
exit;

?>