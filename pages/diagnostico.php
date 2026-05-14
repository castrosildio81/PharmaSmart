<?php
session_start();

include "../funcoes/conection.php";
include "../seguranca/s_clt.php";

$user = $_SESSION['user'];

$user_nome = $user['nome'];
$user_email = $user['email'];

$iniciais = strtoupper(substr($user_nome, 0, 1));

$resultado = "";
$nivel = "";
$descricao = "";

/*
    SIMULAÇÃO DE IA
    Depois você poderá integrar com Flask
*/

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $sintomas = strtolower(trim($_POST['sintomas']));

    if(
        str_contains($sintomas, "febre") &&
        str_contains($sintomas, "dor")
    ){

        $resultado = "Possível Gripe";
        $nivel = "moderado";

        $descricao = "
        Os sintomas indicam um possível quadro gripal.
        Recomenda-se repouso, hidratação e acompanhamento médico.
        ";

    }elseif(
        str_contains($sintomas, "tosse")
    ){

        $resultado = "Possível Infecção Respiratória";

        $nivel = "atenção";

        $descricao = "
        Tosse persistente pode indicar infecção respiratória.
        É recomendado procurar avaliação médica.
        ";

    }elseif(
        str_contains($sintomas, "cabeça")
    ){

        $resultado = "Possível Cefaleia";

        $nivel = "leve";

        $descricao = "
        Dor de cabeça pode estar relacionada a stress,
        cansaço ou desidratação.
        ";

    }else{

        $resultado = "Diagnóstico não identificado";

        $nivel = "neutro";

        $descricao = "
        Não foi possível identificar um padrão.
        Consulte um profissional de saúde.
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Diagnóstico Inteligente</title>

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
    --warning:#f59e0b;
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
    color:white;
}

.smart{
    color:var(--primary);
}

.voltar{
    background:var(--bg-hover);
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

/* CONTAINER */

.container{
    max-width:900px;
    margin:120px auto 40px;
    padding:20px;
}

.card{
    background:var(--bg-card);
    border-radius:18px;
    padding:30px;
    margin-bottom:20px;
}

.title{
    font-size:28px;
    font-weight:bold;
    margin-bottom:10px;
}

.subtitle{
    color:var(--muted);
    margin-bottom:25px;
}

/* FORM */

textarea{
    width:100%;
    min-height:180px;
    resize:none;
    border:none;
    outline:none;
    background:#334155;
    color:white;
    border-radius:14px;
    padding:18px;
    font-size:15px;
    font-family:'Poppins', sans-serif;
}

textarea::placeholder{
    color:#94a3b8;
}

.btn{
    width:100%;
    margin-top:20px;
    padding:15px;
    border:none;
    border-radius:12px;
    background:var(--primary);
    color:#000;
    font-weight:bold;
    cursor:pointer;
    font-size:16px;
}

/* RESULTADO */

.resultado{
    margin-top:25px;
}

.badge{

    display:inline-block;

    padding:8px 14px;

    border-radius:30px;

    font-size:13px;

    font-weight:bold;

    margin-bottom:20px;
}

.leve{
    background:#1e3a8a;
}

.moderado{
    background:#854d0e;
}

.atencao{
    background:#7f1d1d;
}

.neutro{
    background:#334155;
}

.diagnostico{
    font-size:28px;
    font-weight:bold;
    margin-bottom:15px;
}

.desc{
    line-height:1.7;
    color:#cbd5e1;
}

/* ALERTA */

.alerta{
    margin-top:25px;
    background:#7f1d1d;
    padding:18px;
    border-radius:14px;
    color:#fecaca;
    line-height:1.6;
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

    <button class="voltar"
        onclick="window.location='index.php'">
        Voltar
    </button>

</div>

<!-- CONTAINER -->

<div class="container">

    <!-- FORM -->

    <div class="card">

        <div class="title">
            Diagnóstico Inteligente
        </div>

        <div class="subtitle">
            Descreva os sintomas que está sentindo
        </div>

        <form method="POST">

            <textarea
                name="sintomas"
                placeholder="Ex: febre, dores no corpo, tosse, dor de cabeça..."
                required
            ></textarea>

            <button class="btn" type="submit">
                Analisar Sintomas
            </button>

        </form>

    </div>

    <!-- RESULTADO -->

    <?php if($resultado): ?>

    <div class="card resultado">

        <div class="badge <?=$nivel?>">
            <?=$nivel?>
        </div>

        <div class="diagnostico">
            <?=$resultado?>
        </div>

        <div class="desc">
            <?=$descricao?>
        </div>

        <div class="alerta">

            ⚠ Este diagnóstico é apenas informativo
            e não substitui avaliação médica profissional.

        </div>

    </div>

    <?php endif; ?>

</div>

</body>
</html>