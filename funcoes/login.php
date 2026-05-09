<?php 
session_start();
include "conection.php";

if (!isset($_POST['number']) || !isset($_POST['senha'])) {
    header("Location: ../erros/error.html");
    exit;
}

$number = trim($_POST['number']);
$pass = $_POST['senha'];


$sql = "SELECT * FROM usuario 
        WHERE (telefone = :number OR email = :number)";

$stmt = $conec->prepare($sql);
$stmt->bindParam(":number", $number);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($user && password_verify($pass, $user['senha'])) {

    $_SESSION['user'] = [
        'id' => $user['id'],
        'nome' => $user['nome'],
        'telefone' => $user['telefone'],
        'email' => $user['email'],
        'data_nascimento' => $user['data_n'],
        'nivel' => $user['nivel_acesso'],
        'morada' => $user['morada']
    ];

    if ($user['nivel_acesso'] === "admin") {
        header("Location: ../pages/dashboard.php");
        exit;
    }

    if ($user['nivel_acesso'] === "client") {
        header("Location: ../pages/index.php");
        exit;
    }
} else {
    header("Location: ../erros/error.html");
    exit;
}
?>