<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    require_once 'database.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titulo = trim($_POST['titulo']);
        $autor = trim($_POST['autor']);
        $ano = trim($_POST['ano']);
        
        if (empty($titulo) || empty($autor) || empty($ano)) {
            throw new Exception("Todos os campos são obrigatórios");
        }
        
        if (!is_numeric($ano)) {
            throw new Exception("O ano deve ser um número");
        }
        
        $db = getDatabaseConnection();
        $stmt = $db->prepare('INSERT INTO books (titulo, autor, ano) VALUES (?, ?, ?)');
        $stmt->execute([$titulo, $autor, $ano]);
        
        header('Location: index.php?mensagem=Livro adicionado com sucesso!');
        exit();
    }
} catch (Exception $e) {
    header('Location: index.php?erro=' . urlencode($e->getMessage()));
    exit();
}