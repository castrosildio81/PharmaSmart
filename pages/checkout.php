<?php
session_start();

include "../funcoes/conection.php";
include "../seguranca/s_clt.php";

$user_id = $_SESSION['user']['id'];

/*
    BUSCAR CARRINHO
*/

$sql = "
SELECT 
    c.id,
    c.quantidade,
    m.id AS medicamento_id,
    m.nome,
    m.preco
FROM carrinho c
INNER JOIN medicamentos m ON c.medicamento_id = m.id
WHERE c.usuario_id = :usuario_id
";

$stmt = $conec->prepare($sql);
$stmt->execute([":usuario_id" => $user_id]);

$carrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;

foreach($carrinho as $item){
    $total += $item['preco'] * $item['quantidade'];
}

/*
    FINALIZAR COMPRA
*/

if(isset($_POST['finalizar'])){

    try{

        $conec->beginTransaction();

        // 1. criar pedido
        $sql = "INSERT INTO pedidos (usuario_id, total, status)
                VALUES (:usuario_id, :total, 'confirmado')";

        $stmt = $conec->prepare($sql);

        $stmt->execute([
            ":usuario_id" => $user_id,
            ":total" => $total
        ]);

        $pedido_id = $conec->lastInsertId();

        // 2. inserir itens
        foreach($carrinho as $item){

            $sql = "INSERT INTO pedido_itens 
                    (pedido_id, medicamento_id, quantidade, preco)
                    VALUES
                    (:pedido_id, :medicamento_id, :quantidade, :preco)";

            $stmt = $conec->prepare($sql);

            $stmt->execute([
                ":pedido_id" => $pedido_id,
                ":medicamento_id" => $item['medicamento_id'],
                ":quantidade" => $item['quantidade'],
                ":preco" => $item['preco']
            ]);
        }

        // 3. limpar carrinho
        $sql = "DELETE FROM carrinho WHERE usuario_id = :usuario_id";

        $stmt = $conec->prepare($sql);

        $stmt->execute([":usuario_id" => $user_id]);

        $conec->commit();

        header("Location: sucesso.php");

    }catch(Exception $e){

        $conec->rollBack();

        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Checkout</title>

<style>

body{
    font-family:Poppins;
    background:#0f172a;
    color:white;
    margin:0;
}

.container{
    max-width:900px;
    margin:100px auto;
    padding:20px;
}

.card{
    background:#1e293b;
    padding:20px;
    border-radius:16px;
}

.item{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px solid #334155;
}

.total{
    font-size:22px;
    margin-top:20px;
    font-weight:bold;
}

button{
    width:100%;
    margin-top:20px;
    padding:15px;
    border:none;
    border-radius:12px;
    background:#22c55e;
    font-weight:bold;
    cursor:pointer;
}

</style>

</head>
<body>

<div class="container">

<div class="card">

<h2>Resumo do Pedido</h2>

<?php foreach($carrinho as $item): ?>

<div class="item">

<span><?=$item['nome']?></span>

<span>
<?=number_format($item['preco'],2,",",".")?> x <?=$item['quantidade']?>
</span>

</div>

<?php endforeach; ?>

<div class="total">
Total: <?=number_format($total,2,",",".")?> AOA
</div>

<form method="POST">

<button name="finalizar">
Finalizar Compra
</button>

</form>

</div>

</div>

</body>
</html>