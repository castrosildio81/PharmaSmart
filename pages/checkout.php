<?php
   session_start();
        include "../funcoes/conection.php";

        // Verificar login
        if (!isset($_SESSION['user'])) {
            session_destroy();
            header("Location: index.php");
            exit;
        }

        $user = $_SESSION['user'];
        $user_id = $user['id'];
        

        $pedido_id = $_GET['pedido'] ?? null;

        if (!$pedido_id) {
            die("Pedido inválido.");
        }
        //buscar o pedido
        $sql = "SELECT * FROM pedidos WHERE id = :id";
        $stmt = $conec->prepare($sql);
        $stmt->bindValue(":id", $pedido_id);
        $stmt->execute();

        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            die("Pedido não encontrado.");
        }

        //buscar itens do pedido 
        $sql = "SELECT pi.*, m.nome, m.imagem 
                FROM pedido_itens pi
                JOIN medicamentos m ON pi.produto_id = m.id
                WHERE pi.pedido_id = :pedido_id";

        $stmt = $conec->prepare($sql);
        $stmt->bindValue(":pedido_id", $pedido_id);
        $stmt->execute();

        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        $sql = "SELECT p.*, u.nome, u.morada 
                FROM pedidos p
                JOIN usuario u ON p.usuario_id = u.id
                WHERE p.id = :id";

        $stmt = $conec->prepare($sql);
        $stmt->bindValue(":id", $pedido_id);
        $stmt->execute();

        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = 0;
        foreach ($itens as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<title>Checkout</title>

<style>
body{
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #0f172a, #020617);
    color: #e2e8f0;
    margin: 0;
}

.container{
    max-width: 900px;
    margin: 80px auto;
    padding: 20px;
}

.card{
    background: #020617;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.6);
}

h2{
    margin-bottom: 10px;
}

.info{
    margin-bottom: 20px;
    line-height: 1.6;
}

.item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 0;
    border-bottom:1px solid #1e293b;
}

.item:last-child{
    border-bottom:none;
}

.total{
    font-size:24px;
    margin-top:25px;
    font-weight:600;
    color:#22c55e;
}

.actions{
    display:flex;
    gap:10px;
    margin-top:25px;
}

button{
    flex:1;
    padding:14px;
    border:none;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.btn-download{
    background:#3b82f6;
    color:white;
}

.btn-download:hover{
    background:#2563eb;
}

.btn-back{
    background:#22c55e;
    color:white;
}

.btn-back:hover{
    background:#16a34a;
}

</style>

</head>
<body>

<div class="container">

<div class="card">

<h2> Pedido confirmado</h2>

<div class="info">
    <p><strong>Nº do Pedido:</strong> <?=$pedido_id?></p>
    <p><strong>Cliente:</strong> <?=$pedido['nome']?></p>
    <p><strong>Endereço:</strong> <?=$pedido['morada']?></p>
    <p><strong>Data:</strong> <?=$pedido['data_criacao']?></p>
</div>

<hr>

<h3> Produtos</h3>

<?php foreach($itens as $item): ?>

<div class="item">
    <span><?=$item['nome']?></span>
    <span>
        <?=number_format($item['preco'],2,",",".")?> AOA x <?=$item['quantidade']?>
    </span>
</div>

<?php endforeach; ?>

<div class="total">
    Total Pago: <?=number_format($total,2,",",".")?> AOA
</div>

<div class="actions">

<a href="fatura.php?pedido=<?=$pedido_id?>">
    <button class="btn-download"> Baixar Fatura</button>
</a>

<a href="../pages/index.php">
    <button class="btn-back"> Continuar comprando</button>
</a>

</div>

</div>

</div>

</body>
</html>