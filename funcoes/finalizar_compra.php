<?php
        session_start();
        include "../funcoes/conection.php";


        // Verificar login
        if (!isset($_SESSION['user'])) {
            session_destroy();
            header("Location: index.php");
            exit;
        }

        $user = $_SESSION['user'];
        $user_id = $user['id'];
        $endereco = $user['morada'];

       


        // Verificar carrinho
        if (empty($_SESSION['carrinho'])) {
            die("Carrinho vazio.");
        }
         $carrinho = $_SESSION['carrinho'];

        

        try {
            $conec->beginTransaction();

            $total = 0;

            // Calcular total e validar stock
            foreach ($carrinho as $item) {
                $sql = "SELECT estoque, preco FROM medicamentos WHERE id = :id";
                $stmt = $conec->prepare($sql);
                $stmt->bindValue(":id", $item['medicamento_id']);
                $stmt->execute();

                $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                

                if (!$produto) {
                    throw new Exception("Produto não encontrado.");
                }

                if ($produto['estoque'] < $item['quantidade']) {
                    throw new Exception("Stock insuficiente para o produto: " . $item['nome']);
                }

                $total += $produto['preco'] * $item['quantidade'];
            }

                // Criar pedido
            $sql = "INSERT INTO pedidos (usuario_id, total, metodo_pagamento, endereco_entrega,  status)
                    VALUES (:usuario_id, :total, 'indefinido' , :endereco, 'pendente')";

            $stmt = $conec->prepare($sql);
            $stmt->bindValue(":usuario_id", $user_id);
            $stmt->bindValue(":endereco", $endereco);
            $stmt->bindValue(":total", $total);
            $stmt->execute();

            $pedido_id = $conec->lastInsertId();

            // Inserir itens do pedido + atualizar stock
            foreach ($carrinho as $item) {
                // Inserir item
                $sql = "INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco)
                        VALUES (:pedido_id, :medicamento_id, :quantidade, :preco)";
                $stmt = $conec->prepare($sql);
                $stmt->bindValue(":pedido_id", $pedido_id);
                $stmt->bindValue(":medicamento_id", $item['medicamento_id']);
                $stmt->bindValue(":quantidade", $item['quantidade']);
                $stmt->bindValue(":preco", $item['preco']);
                $stmt->execute();

                // Atualizar stock
                $sql = "UPDATE medicamentos 
                        SET estoque = estoque - :quantidade 
                        WHERE id = :id";
                $stmt = $conec->prepare($sql);
                $stmt->bindValue(":quantidade", $item['quantidade']);
                $stmt->bindValue(":id", $item['medicamento_id']);
                $stmt->execute();
            }
            // limpar carrinho no banco
            $sql = "DELETE FROM carrinho WHERE usuario_id = :usuario_id";
            $stmt = $conec->prepare($sql);
            $stmt->bindValue(":usuario_id", $user_id);
            $stmt->execute();

            //adicionar notificação 

            $mensagem = "Compra finalizada com sucesso!";
            $tipo = "pedido";

            $sql = "INSERT INTO notificacoes (usuario_id, tipo, mensagem, lida, data_criacao)
                    VALUES (:usuario_id, :tipo, :mensagem, 0, now())";
            $stmt = $conec->prepare($sql);

            $stmt->bindParam(":usuario_id", $user_id);
            $stmt->bindParam(":tipo", $tipo);
            $stmt->bindParam(":mensagem", $mensagem);
            $stmt->execute();

            // Confirmar tudo
            $conec->commit();

            // Limpar carrinho
            unset($_SESSION['carrinho']);

            // Redirecionar
            header("Location: ../pages/checkout.php?pedido=" . $pedido_id);
            exit;

        } catch (Exception $e) {
            $conec->rollBack();
            die("Erro ao finalizar compra: " . $e->getMessage());
        }
?>