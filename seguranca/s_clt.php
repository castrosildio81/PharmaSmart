
<?php 
    if(isset($_SESSION['user'])){
         if($_SESSION['user']['nivel'] === 'admin'){
            session_destroy();
            header("Location: ../pages/index.php");
            exit;
        }
    }
?>