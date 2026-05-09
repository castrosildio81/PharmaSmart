<?php
include "conection.php";

if (
    !isset($_POST['name']) ||
    !isset($_POST['number']) ||
    !isset($_POST['email']) ||
    !isset($_POST['date']) ||
    !isset($_POST['morada']) ||
    !isset($_POST['pass']) ||
    !isset($_POST['c_pass'])
) {
    header("Location: ../erros/error.html");
    exit;
}

$name = trim($_POST['name']);
$number = trim($_POST['number']);
$email = trim($_POST['email']);
$date = $_POST['date'];
$morada = trim($_POST['morada']);
$pass = $_POST['pass'];
$c_pass = $_POST['c_pass'];

if ($pass !== $c_pass) {
    header("Location: ../erros/error.html");
    exit;
}

try {

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario 
            (nome, telefone, email, data_n, senha, nivel_acesso, morada)
            VALUES 
            (:name, :number, :email, :date, :pass, 'client', :morada)";

    $stmt = $conec->prepare($sql);

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":number", $number);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":date", $date);
    $stmt->bindParam(":pass", $hash);
    $stmt->bindParam(":morada", $morada);


    $stmt->execute();

    header("Location: ../pages/login.html");
    exit;

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        header("Location: ../erros/error.html");
        exit;
    }

    echo "Erro: " . $e->getMessage();
}
?>