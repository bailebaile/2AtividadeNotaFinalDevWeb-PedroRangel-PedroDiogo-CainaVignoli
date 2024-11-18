<?php
try {
    // Conecta ao banco SQLite (cria se não existir)
    $db = new PDO('sqlite:tarefas.db');
    
    // Configura o PDO para lançar exceções em caso de erros
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Cria a tabela se não existir
    $db->exec('CREATE TABLE IF NOT EXISTS tarefas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        descricao TEXT NOT NULL,
        data_vencimento DATE NOT NULL,
        concluida INTEGER DEFAULT 0
    )');
    
} catch(PDOException $e) {
    // Em caso de erro, exibe a mensagem e encerra a execução
    echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
    die();
}