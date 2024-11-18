<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $descricao = trim($_POST['descricao']);
    $data_vencimento = $_POST['data_vencimento'];
    
    try {
        // Prepara e executa a query de inserção
        $query = $db->prepare('INSERT INTO tarefas (descricao, data_vencimento) VALUES (?, ?)');
        $query->execute([$descricao, $data_vencimento]);
        
        // Redireciona de volta para a página principal
        header('Location: index.php');
        exit();
        
    } catch(PDOException $e) {
        echo "Erro ao adicionar tarefa: " . $e->getMessage();
    }
}