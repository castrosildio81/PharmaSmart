<?php
    if(!isset($_SESSION['user'])){
        if($_SESSION['nivel_acesso'] === 'client'){
                session_destroy();
                header("Location: ../pages/index.php");
                exit;
        }
    }
?>