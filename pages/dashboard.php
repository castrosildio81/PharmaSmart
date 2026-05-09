<?php
    session_start();
    include "../funcoes/conection.php";
    include "../seguranca/s_adm.php";
    
    
     if (!isset($_SESSION['user'])) {
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

    $sql = "select * from medicamentos;";
    $stmt = $conec->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../style/logo.css">
<link rel="icon" type="image/png" href="../Imagens/pharmasmart_logo01.png">
<title>Pharmasmart</title>


<style>
  
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', sans-serif;
}

body {
  display: flex;
  background: #ffffff;
  color: #e5e7eb;
  overflow: hidden;
}


.sidebar {
  width: 250px;
  height: 100vh;
  background: #2e4136;
  padding: 25px;
  border-right: 1px solid #7e8ca3;

  
  position: fixed;
  left: 0;
  top: 0;
  overflow-y: auto
}

.logo {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 30px;
}

.sidebar a {
  display: block;
  padding: 12px;
  border-radius: 8px;
  color: #ffffff;
  text-decoration: none;
  margin-bottom: 8px;
  transition: 0.3s;
}

.sidebar a:hover {
  background: #1e293b;
  color: white;
}
.topbar {
  position: fixed;
  top: 0;
  left: 250px;
  right: 0;

  height: 70px;
  background: #2e4136;
  border-bottom: 1px solid #7e8ca3;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 0 20px;
  z-index: 1000;
}

/* MAIN */
.main {
  margin-left: 250px;
  flex: 1;
  padding: 90px 25px 25px; /* espaço para topbar fixa */
  overflow-y: auto;
  height: 100vh;
}

/* TOPBAR */
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.search {
  padding: 8px 12px;
  border-radius: 8px;
  border: none;
  background: #2e4136;
  color: white;
}

/* CARDS */
.cards {
  display: flex;
  gap: 20px;
}

.card {
  flex: 1;
  background: #2e4136;
  padding: 20px;
  border-radius: 12px;
  transition: 0.3s;
}

.card:hover {
  transform: translateY(-4px);
  background: #334155;
}

.table h3 {
  margin-bottom: 15px;
  font-size: 16px;
  color: #ffffff;
}

.card p {
  font-size: 22px;
  margin-top: 10px;
}

/* STATUS */
table {
  width: 100%;
  border-collapse: collapse;
  min-width: 700px;
}


/* TABLE */
.table {
  margin-top: 30px;
  background: #2e4136;
  padding: 20px;
  border-radius: 12px;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}


thead {
  background: #1f2e26;
}

th {
  text-align: left;
  font-size: 12px;
  color: #94a3b8;
  padding: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
tr:nth-child(even) {
  background: rgba(255, 255, 255, 0.02);
}

tr:hover {
  background: rgba(255, 255, 255, 0.06);
  transition: 0.2s;
}


td {
  padding: 12px;
  border-top: 1px solid #334155;
  color: #e5e7eb;
}

th, td {
  text-align: center;
  vertical-align: middle;
}


/* RESPONSIVO */
@media(max-width: 768px){
  .sidebar {
    display: none;
  }

  .cards {
    flex-direction: column;
  }
}
.sidebar a {
  display: flex;
  align-items: center;
  gap: 10px;
}

.icon {
  display: flex;
}
.user{
  text-decoration: none;
  color: #e5e7eb;
  display: inline-flex;
  align-items: center; /* centraliza verticalmente texto + ícone */
  gap: 4px; /* espaçamento entre o nome e o ícone */
}

.user svg, .search svg {
  vertical-align: middle; 
  margin-left: 4px; /* Espaço entre o nome e o ícone */
}
.user svg{
    border: 1px solid white;
    border-radius: 15px;
    

}
.user {
  color: white;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}
.pharma{
  position: relative;
  width: 50px;
}
.bom {
  background: rgba(34, 197, 94, 0.15);
  color: #22c55e;
}

.medio {
  background: rgba(234, 179, 8, 0.15);
  color: #eab308;
}


.baixo {
  background: rgba(239, 68, 68, 0.15);
  color: #ef4444;
}

.bom, .medio, .baixo {
  padding: 6px 10px;
  border-radius: 999px;
  font-weight: bold;
  display: inline-block;
  font-size: 12px;
}
.search-box {
  display: flex;
  align-items: center;
  background: #1f2e26;
  padding: 6px 10px;
  border-radius: 8px;
}

.search-box input {
  border: none;
  outline: none;
  background: transparent;
  color: white;
  margin-right: 8px;
  width: 220px;
}
.logo{
  margin-top: 30px;
}

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
    <div class="logo">
                <span class="pharma">Pharma</span>
                <span class="smart">Smart</span>
    </div>
    <div class="search-box">
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

    <?php 
      $sql_count = "SELECT COUNT(*) as total_clientes from usuario WHERE nivel_acesso = 'client'";
      $count = $conec->query($sql_count);

      $count_user = $count->fetch(PDO::FETCH_ASSOC);
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
      <p><?=$count_user['total_clientes']; ?></p>
    </div>
  </div>
  <!-- TABLE -->
  <div class="table">
    <h3>Produtos</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Preço</th>
        <th>Estoque</th>
        <th>Precisa receita</th>    
      </tr> 
      <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

             $classe = ($row['estoque'] <= 500) ? 'baixo':'bom';

              if ($row['estoque'] <= 500) {
                  $classe = 'baixo';
              } elseif ($row['estoque'] <= 1000) {
                  $classe = 'medio';
              } else {
                  $classe = 'bom';
              }

            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['nome']}</td>";
            echo "<td>{$row['descricao']}</td>";
            echo "<td>{$row['preco']}</td>";
            echo "<td class='$classe'>{$row['estoque']}</td>";
            echo "<td>" . ($row['necessita_receita'] ? "Sim" : "Não")."</td>";
            echo "</tr>";
            }
    ?>
     </table>
  </div>
</div>
</body>
</html>