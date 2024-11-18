<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    require_once 'database.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = $_POST['id'];
        
        if (!is_numeric($id)) {
            throw new Exception("ID inválido");
        }
        
        $db = getDatabaseConnection();
        $stmt = $db->prepare('DELETE FROM books WHERE id = ?');
        $stmt->execute([$id]);
        
        header('Location: index.php?mensagem=Livro excluído com sucesso!');
        exit();
    } else {
        throw new Exception("Método inválido ou ID não fornecido");
    }
} catch (Exception $e) {
    header('Location: index.php?erro=' . urlencode($e->getMessage()));
    exit();
}