<?php
session_start();
include "../funcoes/conection.php";
include "../seguranca/s_clt.php";

$user = $_SESSION["user"];

$user_id = $user["id"];

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['imagem'])) {

        $arquivo = $_FILES['imagem'];

        // Verifica erros
        if ($arquivo['error'] == 0) {

            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            $nome_arquivo = $arquivo['name'];
            $tmp = $arquivo['tmp_name'];

            $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

            //Valida extensão
            if (in_array($extensao, $extensoes_permitidas)) {

                //Nome único
                $novo_nome = uniqid() . "." . $extensao;

                $destino = "../imagens/receitas/" . $novo_nome;

                // Move arquivo
                if (move_uploaded_file($tmp, $destino)) {

                    // Salvar no banco

                    $sql = "INSERT INTO receitas (usuario_id, imagem, data_envio)
                            VALUES (:usuario_id, :imagem, NOW())";

                   


                    $stmt = $conec->prepare($sql);

                    $stmt->bindParam(":usuario_id", $user_id);
                    $stmt->bindParam(":imagem", $destino);

                    $stmt->execute();

                    $receita_id = $conec->lastInsertId();

                    

                    $python = '"C:\\Users\\Sildio Castro\\AppData\\Local\\Programs\\Python\\Python311\\python.exe"';

                    $script = 'C:\\xampp\\htdocs\\pharmasmart\\script_py\\ocr.py';

                    $comando = $python . " " . $script . " " . escapeshellarg($destino) . " 2>&1";
                    set_time_limit(60);

                    $resultado = shell_exec($comando);

                    $_SESSION['receita_id'] = $receita_id;
                    $_SESSION['ocr_texto'] = $resultado;

                } else {
                    $msg = "Erro ao salvar imagem.";
                }
            } else {
                $msg = "Formato inválido.";
            }
        } else {
            $msg = "Erro no upload.";
        }
    }
}
if(isset($SESSION['msg'])){
    $msg = $_SESSION['msg'];
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/logo.css">

    <title>Nova Receita</title>

    <style>
        :root {
            --bg-main: #020617;
            --bg-card: #1e293b;
            --bg-hover: #334155;
            --primary: #38bdf8;
            --text: #e2e8f0;
            --muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background: var(--bg-card);
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            font-weight: bold;
            font-size: 20px;
        }

        .voltar {
            background-color: #334155;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        body {
            margin: 0;
            font-family: Poppins, sans-serif;
            background: #0f172a;
            color: white;
            min-height: 100vh;
            padding-top: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card {
            background: #1e293b;
            padding: 30px;
            border-radius: 16px;
            width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type=file] {
            width: 100%;
            padding: 12px;
            background: #334155;
            border: none;
            border-radius: 10px;
            color: white;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1d4ed8;
        }

        .msg {
            margin-top: 15px;
            text-align: center;
            color: #22c55e;
        }

        .preview {
            width: 100%;
            margin-top: 20px;
            border-radius: 12px;
            display: none;
        }
    </style>
</head>

<body>
    <div class="header">

        <div class="logo">
            <span class="pharma">Pharma</span>
            <span class="smart">Smart</span>
        </div>

        <div>
            <button onclick="window.location='index.php'" class="voltar">
                Voltar
            </button>
        </div>

    </div>

    <div class="card">

        <h2>Enviar Receita Médica</h2>

        <form method="POST" action="nova_receita.php" enctype="multipart/form-data">

            <input
                type="file"
                name="imagem"
                accept="image/*"
                onchange="previewImagem(event)"
                required>
            <img class="preview" id="preview">

            <button type="submit">
                Enviar Receita
            </button>

        </form>
        <?php
        if (isset($resultado)) {
            if ($resultado) {

                $texto = $resultado;
            } else {

                $texto = "Erro na leitura de imagem!";
            }
        }
        ?>
        
            

        <?php if ($msg): ?>
            <div class="msg">
                <?= $msg ?>
            </div>
        <?php endif; ?>

    </div>
    <div id="resultado">
        <div class="card">
            <h3>Texto extraído da receita:</h3>
            <p>
                <?php if (isset($texto)): ?>
                    <?= nl2br(htmlspecialchars($texto)); ?>

                <?php else: ?>
                    <?= "Adicione uma receita!" ?>
                <?php endif; ?>
            </p>

        </div>
        <?php if (isset($texto)): ?>
            <div>
                <button onclick="confirmarTxt()">Confirmar texto</button>
                <button onclick="cancelarTxt()">Cancelar</button>
            </div>
        <?php endif; ?>
    </div>
    <script>
        function confirmarTxt(){
            window.location.href ="../funcoes/receita_txt.php";
        }
        function cancelarTxt(){
            window.location.href ="../funcoes/cancelar_txt.php";
        }
    </script>



    <script>
        function previewImagem(event) {

            const preview = document.getElementById('preview');

            preview.src = URL.createObjectURL(event.target.files[0]);

            preview.style.display = 'block';
        }
    </script>


</body>

</html>