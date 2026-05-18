<?php
require_once "../vendor/autoload.php";
include "../funcoes/conection.php";

use Dompdf\Dompdf;

$pedido_id = $_GET['pedido'] ?? null;

if (!$pedido_id) {
    die("Pedido inválido.");
}

// Buscar pedido + usuário
$sql = "SELECT p.*, u.nome, u.morada
        FROM pedidos p
        JOIN usuario u ON p.usuario_id = u.id
        WHERE p.id = :id";

$stmt = $conec->prepare($sql);
$stmt->bindValue(":id", $pedido_id);
$stmt->execute();

$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar itens
$sql = "SELECT pi.*, m.nome 
        FROM pedido_itens pi
        JOIN medicamentos m ON pi.produto_id = m.id
        WHERE pi.pedido_id = :pedido_id";

$stmt = $conec->prepare($sql);
$stmt->bindValue(":pedido_id", $pedido_id);
$stmt->execute();

$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular total
$total = 0;
foreach ($itens as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// HTML da fatura
$html = '
<h2>Fatura - Pedido #'.$pedido_id.'</h2>
<p><strong>Cliente:</strong> '.$pedido['nome'].'</p>
<p><strong>Endereço:</strong> '.$pedido['endereco'].'</p>
<hr>
<table width="100%" border="1" cellspacing="0" cellpadding="8">
<tr>
    <th>Produto</th>
    <th>Qtd</th>
    <th>Preço</th>
</tr>';

foreach ($itens as $item) {
    $html .= '
    <tr>
        <td>'.$item['nome'].'</td>
        <td>'.$item['quantidade'].'</td>
        <td>'.number_format($item['preco'],2,",",".").' AOA</td>
    </tr>';
}

$html .= '
</table>
<h3>Total: '.number_format($total,2,",",".").' AOA</h3>
';

// Criar PDF
//$dompdf = new Dompdf();
//$dompdf->loadHtml($html);

// Tamanho A4
//$dompdf->setPaper('A4', 'portrait');

//$dompdf->render();

// FORÇAR DOWNLOAD
//$dompdf->stream("fatura_pedido_$pedido_id.pdf", [
//    "Attachment" => true
//]);
?>