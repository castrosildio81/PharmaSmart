<?php
    include "../funcoes/conection.php";
    include "../seguranca/s_adm.php";

     if (!isset($_SESSION['user_id'])) {
        session_destroy();
        header("Location: index.php");
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $user_nome = $_SESSION['user_nome'];
    $user_telefone = $_SESSION['user_telefone'];
    $user_email = $_SESSION['user_email'];
    $user_data_n = $_SESSION['user_data_n'];

    $sql = "select * from medicamentos;";
    $stmt = $conec->query($sql);
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
}


.sidebar {
  width: 250px;
  height: 100vh;
  background: #2e4136;
  padding: 25px;
  border-right: 1px solid #7e8ca3;
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

/* MAIN */
.main {
  flex: 1;
  padding: 25px;
  
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

.card h3 {
  font-size: 13px;
  color: #94a3b8;
}

.card p {
  font-size: 22px;
  margin-top: 10px;
}

/* STATUS */
.badge {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
}


/* TABLE */
.table {
  margin-top: 30px;
  background: #2e4136;
  padding: 20px;
  border-radius: 12px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  text-align: left;
  font-size: 12px;
  color: #94a3b8;
  padding-bottom: 10px;
}

td {
  align-items: center;
  padding: 12px 0;
  border-top: 1px solid #334155;
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
  vertical-align: middle; /* Alinha o centro do SVG com o texto */
  margin-left: 4px; /* Espaço entre o nome e o ícone */
}
.user svg{
    border: 1px solid white;
    border-radius: 15px;
    

}
.user{
  color: #080808;
}
.pharma{
  position: relative;
  width: 50px;
}
.bom {
  background: #99f7bb;
  color: #16a34a;
}

  .medio {
    background-color: #f7dc66;
    color: #995d18;
}


.baixo {
    background-color: #ffb5b5;
    color: #b30000;
    font-weight: bold;
}

.baixo, .bom, .medio {
  border-radius: 10px;
  margin: 10px;
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
    <h2><img class="pharma" src="../Imagens/pharmasmart_logo01.png" alt="" srcset=""></h2>
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

    <?php 
      $sql_count = "SELECT COUNT(*) as total_produtos from medicamentos WHERE 1";
      $count = $conec->query($sql_count);

      $count_produtos = $count->fetch(PDO::FETCH_ASSOC);
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
      <h3>Novo Produto</h3>
      <p>Adicionar <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="green" viewBox="0 0 16 16">
    <path d="M8 1a.75.75 0 0 1 .75.75v5.5h5.5a.75.75 0 0 1 0 1.5h-5.5v5.5a.75.75 0 0 1-1.5 0v-5.5h-5.5a.75.75 0 0 1 0-1.5h5.5v-5.5A.75.75 0 0 1 8 1z"/>
  </svg></p>
    </div>

    <div class="card">
      <h3>Pedidos</h3>
      <p>45</p>
    </div>

    <div class="card">
      <h3>Produtos</h3>
      <p><?=$count_produtos['total_produtos']; ?></p>
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
        <th>Adicionar</th>
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
            echo "<td>
                    <form method='POST' action='../funcoes/add_stock.php' style='display:flex; gap:5px; justify-content:center;'>
                      <input type='hidden' name='id' value='{$row['id']}'>
                      <input type='number' name='quantidade' min='100' required style='width:60px; text-align:center;'>
                      <button type='submit'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='green' viewBox='0 0 16 16'>
                          <path d='M8 1a.75.75 0 0 1 .75.75v5.5h5.5a.75.75 0 0 1 0 1.5h-5.5v5.5a.75.75 0 0 1-1.5 0v-5.5h-5.5a.75.75 0 0 1 0-1.5h5.5v-5.5A.75.75 0 0 1 8 1z'/>
                        </svg>
                      </button>
                    </form>
                </td>";
            echo "</tr>";
            }
    ?>
 

    </table>
  </div>

</div>

</body>
</html>