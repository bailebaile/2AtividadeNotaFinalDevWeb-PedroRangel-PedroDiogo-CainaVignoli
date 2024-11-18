<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function getDatabaseConnection() {
    try {
        $dbPath = __DIR__ . '/livraria.db';
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $db->exec("CREATE TABLE IF NOT EXISTS books (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            titulo TEXT NOT NULL,
            autor TEXT NOT NULL,
            ano INTEGER NOT NULL
        )");
        
        return $db;
    } catch(Exception $e) {
        echo "Erro na conexão: " . $e->getMessage();
        exit();
    }
}