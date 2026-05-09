<?php
    include "../funcoes/conection.php";
    include "../seguranca/s_adm.php";

     if (!isset($_SESSION['user_id'])) {
        session_destroy();
        header("Location: index.html");
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $user_nome = $_SESSION['user_nome'];
    $user_telefone = $_SESSION['user_telefone'];
    $user_email = $_SESSION['user_email'];
    $user_data_n = $_SESSION['user_data_n'];

    
?>
<?php 
   
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../style/dashboard.css">
<link rel="icon" type="image/png" href="../Imagens/pharmasmart_logo01.png">
<title>Pharmasmart</title>


<style>

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
 <a href="dashboard.php">
  <span class="icon">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M3 10L12 3L21 10"></path>
      <path d="M5 10V21H19V10"></path>
    </svg>
  </span>
  Inicial
</a>
  <a href="produtos.php">
  <span class="icon">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M21 16V8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
      <path d="M3.27 6.96L12 12l8.73-5.04"/>
      <path d="M12 22V12"/>
    </svg>
  </span>
  Produtos
</a>
  <a href="pedidos.php">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M21 10H7"></path>
  <path d="M21 6H3"></path>
  <path d="M21 14H7"></path>
  <path d="M3 18h18v2H3z"></path>
</svg>
  </span>
  Pedidos
</a>
  
<a href="clientes.php">
    <span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="7" r="4"></circle>
        <path d="M5.5 21c1.5-3 4.5-5 6.5-5s5 2 6.5 5"></path>
    </svg>
    </span>
    Clientes
</a>
</div>

<!-- MAIN -->
<div class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <h2>PharmaSmart</h2>
    <div class="search">
    <input class="search" placeholder="Pesquisar...">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
    <circle cx="11" cy="11" r="8"/>
    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
  </svg>
</div>
    <?php
      $nomes = explode(' ', $user_nome); 
      $primeiro = $nomes[0]; 
      $ultimo = $nomes[count($nomes) - 1]; 
      $nome_exibido = $primeiro . ' ' . $ultimo;
    ?>
    
<a class="user" href="user.php">
  <h3> <?= $nome_exibido ?></h3>
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="7" r="4"></circle>
    <path d="M5.5 21c1.5-3 4.5-5 6.5-5s5 2 6.5 5"></path>
  </svg>
</a>
  </div>

  <!-- CARDS -->
  <div class="cards">
    <div class="card">
      <h3>Vendas</h3>
      <p>120.000 Kz</p>
    </div>

    <div class="card">
      <h3>Pedidos</h3>
      <p>45</p>
    </div>

    <div class="card">
      <h3>Usuários</h3>
      <p>120</p>
    </div>
  </div>

  <!-- TABLE -->
  <div class="table">
    <h3>Produtos</h3>

    <table>
      <tr>
        <th>Nome</th>
        <th>Stock</th>
        <th>Status</th>
      </tr>

      <tr>
        <td>Paracetamol</td>
        <td>50</td>
        <td><span class="badge ok">Disponível</span></td>
      </tr>

      <tr>
        <td>Ibuprofeno</td>
        <td>101</td>
        <td><span class="badge low">Baixo</span></td>
      </tr>

    </table>
  </div>

</div>

</body>
</html>
