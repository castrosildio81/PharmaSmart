<?php
session_start();

include "../funcoes/conection.php";
include "../seguranca/s_clt.php";

$user = $_SESSION['user'];

$user_id = $user['id'];

$user_nome = $user['nome'];

$iniciais = strtoupper(substr($user_nome, 0, 1));

/*
    BUSCAR HISTÓRICO

    Exemplo:
    receitas
    diagnósticos
    pedidos
*/

$sql = "
SELECT *
FROM receitas
WHERE usuario_id = :usuario_id
ORDER BY data_envio DESC
";

$stmt = $conec->prepare($sql);

$stmt->bindParam(":usuario_id", $user_id);

$stmt->execute();

$receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Histórico</title>

<link rel="icon" type="image/png" href="../Imagens/pharmasmart_logo01.png">
<link rel="stylesheet" href="../style/logo.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>

:root {
    --bg-main:#020617;
    --bg-card:#1e293b;
    --bg-hover:#334155;
    --primary:#38bdf8;
    --text:#e2e8f0;
    --muted:#94a3b8;
    --success:#22c55e;
    --danger:#ef4444;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins', sans-serif;
    background:var(--bg-main);
    color:var(--text);
}

/* HEADER */

.header{
    position:fixed;
    top:0;
    width:100%;
    background:var(--bg-card);
    padding:15px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    z-index:1000;
}

.logo{
    font-size:22px;
    font-weight:bold;
}

.pharma{
    color:#fff;
}

.smart{
    color:var(--primary);
}

.voltar{
    background:#334155;
    color:#fff;
    border:none;
    padding:10px 15px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

/* CONTAINER */

.container{
    max-width:1100px;
    margin:120px auto 40px;
    padding:20px;
}

/* TITULO */

.title{
    font-size:32px;
    font-weight:bold;
    margin-bottom:10px;
}

.subtitle{
    color:var(--muted);
    margin-bottom:30px;
}

/* GRID */

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));
    gap:20px;
}

/* CARD */

.card{
    background:var(--bg-card);
    border-radius:18px;
    overflow:hidden;
    transition:0.3s;
    border:1px solid transparent;
}

.card:hover{
    transform:translateY(-5px);
    border-color:#334155;
}

/* IMG */

.card img{
    width:100%;
    height:220px;
    object-fit:cover;
}

/* CONTENT */

.content{
    padding:20px;
}

.badge{
    display:inline-block;
    background:#0f172a;
    color:var(--primary);
    padding:6px 12px;
    border-radius:30px;
    font-size:12px;
    margin-bottom:15px;
}

.data{
    color:var(--muted);
    font-size:13px;
    margin-bottom:15px;
}

.nome{
    font-size:20px;
    font-weight:bold;
    margin-bottom:12px;
}

.btns{
    display:flex;
    gap:10px;
    margin-top:20px;
}

.btn{
    flex:1;
    padding:12px;
    border:none;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
}

.visualizar{
    background:var(--primary);
    color:#000;
}

.remover{
    background:#7f1d1d;
    color:#fff;
}

/* VAZIO */

.empty{
    background:var(--bg-card);
    padding:40px;
    border-radius:18px;
    text-align:center;
    color:var(--muted);
}

.empty h2{
    margin-bottom:15px;
    color:white;
}

</style>

</head>
<body>

<!-- HEADER -->

<div class="header">

    <div class="logo">
        <span class="pharma">Pharma</span>
        <span class="smart">Smart</span>
    </div>

    <button
        class="voltar"
        onclick="window.location='index.php'"
    >
        Voltar
    </button>

</div>

<!-- CONTAINER -->

<div class="container">

    <div class="title">
        Histórico Médico
    </div>

    <div class="subtitle">
        Receitas enviadas e análises anteriores
    </div>

    <?php if(count($receitas) > 0): ?>

    <div class="grid">

        <?php foreach($receitas as $receita): ?>

        <div class="card">

            <img src="<?=$receita['imagem']?>">

            <div class="content">

                <div class="badge">
                    Receita Médica
                </div>

                <div class="data">
                    <?=date('d/m/Y H:i', strtotime($receita['data_envio']))?>
                </div>

                <div class="nome">
                    Receita enviada
                </div>

                <div class="btns">

                    <button
                        class="btn visualizar"
                        onclick="window.open('<?=$receita['imagem']?>')"
                    >
                        Visualizar
                    </button>

                    <button
                        class="btn remover"
                    >
                        Remover
                    </button>

                </div>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <?php else: ?>

    <div class="empty">

        <h2>
            Nenhum histórico encontrado
        </h2>

        <p>
            Você ainda não enviou receitas médicas.
        </p>

    </div>

    <?php endif; ?>

</div>

</body>
</html>