<?php
session_start();

include "../funcoes/conection.php";

if (!isset($_SESSION['user'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// buscar notificacoes
$sql = "SELECT * FROM notificacoes Where usuario_id = :usuario_id ORDER BY data_criacao DESC";

$stmt = $conec->prepare($sql);
$stmt->bindParam('usuario_id', $user_id);
$stmt->execute();

$notificacoes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../style/logo.css">
<title>Notificações</title>

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
    --warning:#f59e0b;
    --danger:#ef4444;
}

*{margin:0;padding:0;box-sizing:border-box;}

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

.logo{font-size:22px;font-weight:bold;}
.pharma{color:white;}
.smart{color:var(--primary);}

/* BOTÃO VOLTAR */
.voltar{
    display:flex;
    align-items:center;
    gap:8px;
    background:var(--bg-hover);
    color:white;
    padding:10px 14px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
    transition:0.2s;
}

.voltar:hover{
    transform:translateX(-3px);
    background:#475569;
}

.voltar svg{
    width:18px;
    height:18px;
}

/* CONTAINER */
.container{
    max-width:900px;
    margin:120px auto 40px;
    padding:20px;
}

/* CARD */
.card{
    background:var(--bg-card);
    border-radius:18px;
    padding:25px;
    margin-bottom:20px;
}

/* TITLE */
.title{
    font-size:26px;
    font-weight:bold;
    margin-bottom:5px;
}

.subtitle{
    color:var(--muted);
    margin-bottom:20px;
}

/* NOTIFICAÇÃO */
.notification{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px;
    border-radius:14px;
    margin-bottom:12px;
    background:var(--bg-hover);
    transition:0.2s;
}

.notification:hover{
    transform:scale(1.01);
}

.notification.unread{
    border-left:4px solid var(--primary);
}

/* INFO */
.info{
    display:flex;
    gap:12px;
    align-items:center;
}

.icon{
    width:42px;
    height:42px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* CORES */
.pedido{background:rgba(56,189,248,0.2);}
.usuario{background:rgba(34,197,94,0.2);}
.sistema{background:rgba(245,158,11,0.2);}

/* TEXTO */
.msg{
    font-size:14px;
    font-weight:600;
}

.time{
    font-size:12px;
    color:var(--muted);
}

/* ACTIONS */
.actions{
    display:flex;
    gap:10px;
}

.actions button{
    background:none;
    border:none;
    cursor:pointer;
    color:var(--muted);
}

.actions button:hover{
    color:white;
}

svg{
    width:18px;
    height:18px;
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

    <a href="index.php" class="voltar">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 18l-6-6 6-6"/>
        </svg>
        Voltar
    </a>

</div>

<!-- CONTAINER -->
<div class="container">

    <div class="card">

        <div class="title">Notificações</div>
        <div class="subtitle">Veja as atividades recentes do sistema</div>

        <?php foreach($notificacoes as $n): ?>

        <div class="notification <?= !$n['lida'] ? 'unread' : '' ?>">

            <div class="info">

                <div class="icon <?= $n['tipo'] ?>">

                    <?php if($n['tipo']=="pedido"): ?>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 16V8l-9-5-9 5v8l9 5 9-5z"/>
                        </svg>
                    <?php elseif($n['tipo']=="usuario"): ?>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="4"/>
                            <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                        </svg>
                    <?php else: ?>
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82"/>
                        </svg>
                    <?php endif; ?>

                </div>

                <div>
                    <div class="msg"><?= $n['mensagem'] ?></div>
                    <div class="time"><?= $n['data_criacao'] ?></div>
                </div>

            </div>

            <div class="actions">

                <button onclick="marcarLida(<?= $n['id'] ?>, this)" title="Marcar como lida">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                </button>

                <button onclick="excluirNotificacao(<?= $n['id'] ?>, this)" title="Excluir">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 6h18M8 6V4h8v2m-1 0v14H9V6"/>
                    </svg>
                </button>

            </div>

        </div>

        <?php endforeach; ?>

    </div>
    <script>

function marcarLida(id, el){

    fetch("../funcoes/marcar_lida.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ id })
    })
    .then(res => res.text())
    .then(data => {
        console.log(data); // DEBUG
        el.closest(".notification").classList.remove("unread");
    })
    .catch(err => console.error("Erro:", err));

}

function excluirNotificacao(id, el){

    fetch("../funcoes/excluir_notificacao.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ id })
    })
    .then(res => res.text())
    .then(data => {
        console.log(data);
        el.closest(".notification").remove();
    })
    .catch(err => console.error("Erro:", err));

}

</script>

</div>

</body>
</html>