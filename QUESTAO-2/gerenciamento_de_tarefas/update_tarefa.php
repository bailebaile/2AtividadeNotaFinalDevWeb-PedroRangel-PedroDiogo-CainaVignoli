<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Obtém o ID da tarefa
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    
    try {
        // Prepara e executa a query de atualização
        $query = $db->prepare('UPDATE tarefas SET concluida = 1 WHERE id = ?');
        $query->execute([$id]);
        
        // Redireciona de volta para a página principal
        header('Location: index.php');
        exit();
        
    } catch(PDOException $e) {
        echo "Erro ao atualizar tarefa: " . $e->getMessage();
    }
}