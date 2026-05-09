<?php
    session_start();
    include "../funcoes/conection.php";


    if (isset($_SESSION['user'])) {

            $user = $_SESSION['user'];

            $user_id = $user['id'];
            $user_nome = $user['nome'];
            $user_telefone = $user['telefone'];
            $user_email = $user['email'];
            $nivel_acesso = $user['nivel'];
            $user_data_n = $user['data_nascimento'];   
            $user_morada = $user['morada'];



            $primeiro = 
        
}

   

    include "../seguranca/s_clt.php";

    $sql = "SELECT * FROM medicamentos";
    $stmt = $conec->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmasmart</title>
    <link rel="icon" type="image/png" href="../Imagens/pharmasmart_logo01.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/logo.css">
    <link rel="stylesheet" href="../style/index.css">
</head>
<body>
    <div class="main">
        <div class="cabecalho">
            <div class="logo">
                <span class="pharma">Pharma</span>
                <span class="smart">Smart</span>
            </div>
            <div class="pesquisa">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                    viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" placeholder="Procurar medicamentos...">
            </div>
            </div>
            <div class="acoes">
                <?php if(isset($_SESSION['user'])):?>
                    <span class="user">
                        <div class="user-menu">  
                            <div class="user-icon">
                                <a href="user.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24"
                                        viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="8" r="4"></circle>
                                        <path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"></path>
                                    </svg>
                                    <?= htmlspecialchars($user_nome) ?>
                                </a> 
                            </div>
                            <div class="dropdown">
                                <a href="user.php">Perfil</a>
                                <a href="">Diagnóstico</a>
                                <a href="">Nova receita</a>
                                <a href="">Histórico</a>
                                <a href="../funcoes/logout.php">Terminar sessão</a>
                            </div>  
                        </div>
                    </span>
                <?php else: ?>   
                    <a href="login.html">Entrar</a>
                    <a href="criar_conta.html">Criar conta</a>
                <?php endif; ?>
                 <?php if(isset($_SESSION['user'])):?>
                <div class="notif" style="position: relative; display: inline-block;">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        width="24" height="24" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 7h18s-3 0-3-7"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>

                    <span class="notif-bolinha">
                    </span>

                </div>
                <div class="carrinho" onclick="window.location='carrinho.php'">
                <?php else: ?>
                <div class="carrinho" onclick="window.location='login.html'">
                <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        width="24" height="22" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round">
                        
                        <circle cx="9" cy="19" r="1"></circle>
                        <circle cx="20" cy="19" r="1"></circle>
                        
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 
                                2 1.61h9.72a2 2 0 0 0 
                                2-1.61L23 6H6">
                        </path>
                    </svg>
                     <span class="cart-count">14</span>
                </div>
            </div>
        </div>
         <div class="banner">
                <h2 id="frases">Cuide da sua saúde com a PharmaSmart!</h2>
                <p id="frases">Entrega rápida • Produtos certificados e de qualidade • Segurança garantida</p>
        </div>
        <div class="prod">
            <p>PRODUTOS DIVERSOS</p>
        </div>
        <?php if(empty($produtos)): ?>
            <p style="color:white; padding:20px;">Nenhum produto disponível</p>
        <?php endif; ?>
        <div class="grelha">
            <?php foreach($produtos as $row): ?>
                <div class="item" >
                    <img src="../<?= !empty($row['imagem']) ? $row['imagem'] : 'uploads/default.png' ?>" alt="<?= htmlspecialchars($row['nome']) ?>" alt="produto">
                    <h4><?= htmlspecialchars($row['nome']) ?></h4>
                    <p class="pulse"><?= number_format($row['preco'], 2, ',', '.') ?> AOA</p>

                     <?php if(isset($_SESSION['user'])):?>
                        <form action="add_cart.php" method="post">
                            <input type="hidden" name="produto_id" value="<?= $row['id'] ?>">
                            <button type="submit">Adicionar</button>
                        </form>
                    <?php else: ?> 
                        <a href="login.html"><button>Adicionar</button></a>
                    <?php endif; ?>
                            
                        
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="footer">
     © 2026 Pharmasmart • Todos os direitos reservados • pharmasmartsuporte@gmail.com • +244 938 530 558 • v1.0
    </div>
    
</body>
</html>