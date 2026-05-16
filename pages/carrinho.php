<?php
    session_start();
    include "../funcoes/conection.php";
    include "../seguranca/s_clt.php";

    if (!isset($_SESSION['user'])) {
        session_destroy();
        header("Location: index.php");
        exit;
    }

    $user = $_SESSION['user'];

    $user_id = $user['id'];
    $user_nome = $user['nome'];
    $user_email = $user['email'];

    $iniciais = strtoupper(substr($user_nome, 0, 1));


    // quantidade de itens no carrinho
    $sql = "SELECT SUM(quantidade) AS total_itens
            FROM carrinho
            WHERE usuario_id = :user_id";

    $stmt = $conec->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $result = $stmt->fetch();

    $total = $result['total_itens'] ?? 0;

    // quntidade de tipos de produto
    $sql = "SELECT COUNT(*) AS total_tipos 
            FROM carrinho 
            WHERE usuario_id = :usuario_id";

    $stmt = $conec->prepare($sql);
    $stmt->bindParam('usuario_id', $user_id);
    $stmt->execute();

    $result = $stmt->fetch();

    $total_tipos = $result['total_tipos'] ?? 0;

    // Nome, preço, quantidade de produtos no carrinho
    $sql = "SELECT  c.id, m.nome, m.imagem, m.preco, c.quantidade
            FROM carrinho c
            JOIN medicamentos m ON c.medicamento_id = m.id
            WHERE c.usuario_id = :usuario_id";

    $stmt = $conec->prepare($sql);
    $stmt->bindParam('usuario_id', $user_id );
    $stmt->execute();

    $carrinho = $stmt->fetchAll();


    //preço total do carrinho
    $total = 0;

    foreach($carrinho as $item){
        $total += $item['preco'] * $item['quantidade'];
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Carrinho</title>

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
    --danger:#ef4444;
    --success:#22c55e;
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
    background:var(--bg-hover);
    color:#fff;
    border:none;
    padding:10px 15px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

/* CONTAINER */

.container{
    max-width:1200px;
    margin:110px auto 40px;
    padding:20px;
}

.title{
    font-size:28px;
    font-weight:bold;
    margin-bottom:25px;
}

/* GRID */

.grid{
    display:grid;
    grid-template-columns: 2fr 1fr;
    gap:20px;
}

/* CARD */

.card{
    background:var(--bg-card);
    border-radius:18px;
    padding:20px;
}

/* ITEM */

.item{
    display:flex;
    align-items:center;
    gap:20px;
    padding:15px 0;
    border-bottom:1px solid #334155;
}

.item:last-child{
    border-bottom:none;
}

.item img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:12px;
    background:#fff;
}

.info{
    flex:1;
}

.nome{
    font-size:18px;
    font-weight:600;
    margin-bottom:8px;
}

.preco{
    color:var(--primary);
    font-weight:bold;
}

.quantidade{
    display:flex;
    align-items:center;
    gap:10px;
    margin-top:10px;
}

.qtd-btn{
    width:30px;
    height:30px;
    border:none;
    border-radius:8px;
    background:var(--bg-hover);
    color:white;
    cursor:pointer;
    font-weight:bold;
}

/* REMOVER */

.remover{
    background:var(--danger);
    color:white;
    border:none;
    padding:10px 14px;
    border-radius:10px;
    cursor:pointer;
}

/* RESUMO */

.resumo-title{
    font-size:22px;
    margin-bottom:20px;
}

.resumo-item{
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
    color:var(--muted);
}

.total{
    display:flex;
    justify-content:space-between;
    font-size:22px;
    font-weight:bold;
    margin-top:20px;
}

.checkout{
    width:100%;
    margin-top:25px;
    padding:15px;
    border:none;
    border-radius:12px;
    background:var(--success);
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}

/* RESPONSIVO */

@media(max-width:900px){

    .grid{
        grid-template-columns:1fr;
    }

    .item{
        flex-direction:column;
        align-items:flex-start;
    }

    .item img{
        width:100%;
        height:200px;
    }
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

    <button class="voltar" onclick="window.location='index.php'">
        Voltar
    </button>

</div>

<!-- CONTAINER -->

<div class="container">

    <div class="title">
        Meu Carrinho
    </div>

    <div class="grid">

        <!-- PRODUTOS -->

        <div class="card">

            <?php foreach($carrinho as $item): ?>

            <div class="item">

                <img src="<?="../" . $item['imagem']?>" alt="produto">

                <div class="info">

                    <div class="nome">
                        <?=$item['nome']?>
                    </div>

                    <div class="preco">
                        <?=number_format($item['preco'],2,",",".")?> AOA
                    </div>

                    <div class="quantidade">

                        <button class="qtd-btn">-</button>

                        <span><?=$item['quantidade']?></span>

                        <button class="qtd-btn">+</button>

                    </div>

                </div>
                <form action="../funcoes/remover_do_carrinho.php" method="post">
                    <input type="hidden" name="item_id" value="<?=$item['id']?>">
                    <button type="submit" class="remover">Remover</button>
                </form>

            </div>

            <?php endforeach; ?>

        </div>

        <!-- RESUMO -->

        <div class="card">

            <div class="resumo-title">
                Resumo
            </div>

            <div class="resumo-item">
                <span>Produtos</span>
                <span><?=count($carrinho)?></span>
            </div>

            <div class="resumo-item">
                <span>Entrega</span>
                <span>Grátis</span>
            </div>

            <div class="total">
                <span>Total</span>
                <span>
                    <?=number_format($total,2,",",".")?> AOA
                </span>
            </div>

            <button class="checkout">
                Finalizar Compra
            </button>

        </div>

    </div>

</div>

</body>
</html>