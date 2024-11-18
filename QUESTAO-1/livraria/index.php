<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'database.php';

try {
    $db = getDatabaseConnection();
    $books = $db->query('SELECT * FROM books ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Biblioteca</title>
    <style>
        body {
            background-color: #e0e0e0;
            font-family: Arial;
            margin: 20px;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
        }

        /* Mensagens */
        .mensagem {
            padding: 10px;
            margin: 10px;
            background-color: #fff;
        }

        .sucesso {
            border: 2px solid #27ae60;
        }

        .erro {
            border: 2px solid #c0392b;
        }

        /* Formulário */
        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px 0;
        }

        input {
            width: 100%;
            padding: 5px;
            margin: 5px 0;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        /* Tabela */
        table {
            width: 100%;
            background-color: #fff;
        }

        th, td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .btn-deletar {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Minha Biblioteca</h1>

    <!-- Mensagens -->
    <?php if (isset($_GET['mensagem'])): ?>
        <div class="mensagem sucesso">
            <?= htmlspecialchars($_GET['mensagem']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>
        <div class="mensagem erro">
            <?= htmlspecialchars($_GET['erro']) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário para adicionar livro -->
    <form action="add_book.php" method="post">
        <h2>Adicionar Livro</h2>
        <div>
            <label>Título:</label>
            <input type="text" name="titulo" required>
        </div>
        <div>
            <label>Autor:</label>
            <input type="text" name="autor" required>
        </div>
        <div>
            <label>Ano:</label>
            <input type="number" name="ano" required>
        </div>
        <button type="submit">Adicionar</button>
    </form>

    <!-- Lista de livros -->
    <h2>Livros Cadastrados</h2>
    <?php if (isset($error)): ?>
        <div class="mensagem erro">
            Erro: <?= htmlspecialchars($error) ?>
        </div>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Ano</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['id']) ?></td>
                <td><?= htmlspecialchars($book['titulo']) ?></td>
                <td><?= htmlspecialchars($book['autor']) ?></td>
                <td><?= htmlspecialchars($book['ano']) ?></td>
                <td>
                    <form action="delete_book.php" method="post" style="margin:0; padding:0; background:none;">
                        <input type="hidden" name="id" value="<?= $book['id'] ?>">
                        <button type="submit" class="btn-deletar" 
                                onclick="return confirm('Confirma exclusão?')">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>