<?php
    include "../funcoes/conection.php";
    session_start();

    if(!isset($_SESSION['user'])){
        session_destroy();
        header("Location: index.php");
        exit;
    }

    $user = $_SESSION['user'];

    $user_id = $user['id'];
    $user_nome = $user['nome'];
    $user_telefone = $user['telefone'];
    $user_email = $user['email'];
    $nivel_acesso = $user['nivel'];
    $user_data_n = $user['data_nascimento'];
    $user_morada = $user['morada'];

    //primeiro e último nome
    $partes = explode(" ", trim($user_nome));
    $primeiro = $partes[0];
    $ultimo = $partes[count($partes)-1]; 
    //iniciais do nome
    $iniciais = mb_substr($primeiro, 0, 1) . mb_substr($ultimo, 0, 1);

    //espaçar telefone
    $tel = chunk_split($user_telefone, 3, " ");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../Imagens/pharmasmart_logo01.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/logo.css">
    <link rel="stylesheet" href="../style/user.css">
    <title>Perfil</title>
    <style>
      


    </style>
</head>
<body>
    <div class="header">
    <div class="logo">
                <span class="pharma">Pharma</span>
                <span class="smart">Smart</span>
            </div>
    <div>
        <?php if($user['nivel'] === 'admin'):?>
        <button onclick="window.location='dashboard.php'" class="voltar">Voltar</button>
        <?php else:?>
        <button onclick="window.location='index.php'" class="voltar">Voltar</button>
        <?php endif;?>

    </div>
</div>

<div class="container">

    <div class="grid">

        <!-- Lado esquerdo -->
        <div class="card">
           
            <div class="profile-img"><?=$iniciais?></div>

            <div class="value"><?=$user_nome ?></div>
            <div class="label"><?=$user_email?></div>
        </div>

        <!-- Lado direito -->
        <div class="card">

            <div class="section-title">Dados Pessoais</div>

            <div class="info-box">
                <div class="label">Nome</div>
                <div class="value"><?=$user_nome ?></div>
            </div>

            <div class="info-box">
                <div class="label">Email</div>
                <div class="value"><?=$user_email?></div>
            </div>

            <div class="info-box">
                <div class="label">Telefone</div>
                <div class="value">+244 <?=$tel?></div>
            </div>

            <div class="info-box">
                <div class="label">Data de Nascimento</div>
                <div class="value"><?=$user_data_n?></div>
            </div>

            <div class="info-box">
                <div class="label">Morada</div>
                <div class="value"><?=$user_morada?></div>
            </div>

            <button class="btn-primary">Editar Dados</button>

            <hr style="margin:25px 0; border-color:#334155;">

            <div class="section-title">Segurança</div>

            <div class="info-box">
                <div class="label">Palavra-passe</div>
                <div class="value">********</div>
            </div>

            <button class="btn-primary">Alterar Palavra-passe</button>

        </div>

    </div>

</div>

    
</body>
</html>

