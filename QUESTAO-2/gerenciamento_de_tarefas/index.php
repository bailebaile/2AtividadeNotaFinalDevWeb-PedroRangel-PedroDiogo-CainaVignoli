<?php
require_once 'database.php';

// Atualiza a tarefa se for um POST de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $descricao = trim($_POST['descricao']);
    $data_vencimento = $_POST['data_vencimento'];
    
    try {
        $query = $db->prepare('UPDATE tarefas SET descricao = ?, data_vencimento = ? WHERE id = ?');
        $query->execute([$descricao, $data_vencimento, $id]);
    } catch(PDOException $e) {
        echo "Erro ao atualizar tarefa: " . $e->getMessage();
    }
}

// Busca todas as tarefas
$query = "SELECT * FROM tarefas ORDER BY concluida ASC, data_vencimento ASC";
$tarefas = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            background-color: #f5f5f5;
        }
        .tarefa {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            background-color: white;
            border-radius: 5px;
            align-items: center;
        }
        .concluida {
            background-color: #e8f5e9;
            text-decoration: line-through;
        }
        .botoes {
            display: flex;
            gap: 10px;
        }
        .form-tarefa {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="date"] {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .excluir {
            background-color: #f44336;
        }
        .excluir:hover {
            background-color: #da190b;
        }
        .editar {
            background-color: #2196F3;
        }
        .editar:hover {
            background-color: #1976D2;
        }
        .cancelar {
            background-color: #757575;
        }
        .cancelar:hover {
            background-color: #616161;
        }
        .form-edicao {
            display: none;
            width: 100%;
        }
        .form-edicao.ativo {
            display: block;
        }
        .tarefa-conteudo {
            flex: 1;
        }
        .tarefa-conteudo.escondido {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Minhas Tarefas</h1>

    <!-- Formulário para adicionar tarefa -->
    <div class="form-tarefa">
        <h2>Nova Tarefa</h2>
        <form action="add_tarefa.php" method="POST">
            <input type="text" name="descricao" placeholder="Descrição da tarefa" required>
            <input type="date" name="data_vencimento" required>
            <button type="submit">Adicionar</button>
        </form>
    </div>

    <!-- Lista de tarefas pendentes -->
    <h2>Tarefas Pendentes</h2>
    <?php foreach ($tarefas as $tarefa): ?>
        <?php if (!$tarefa['concluida']): ?>
            <div class="tarefa" id="tarefa-<?php echo $tarefa['id']; ?>">
                <!-- Conteúdo normal da tarefa -->
                <div class="tarefa-conteudo" id="conteudo-<?php echo $tarefa['id']; ?>">
                    <p><?php echo htmlspecialchars($tarefa['descricao']); ?></p>
                    <small>Vencimento: <?php echo date('d/m/Y', strtotime($tarefa['data_vencimento'])); ?></small>
                </div>

                <!-- Formulário de edição (inicialmente oculto) -->
                <div class="form-edicao" id="form-<?php echo $tarefa['id']; ?>">
                    <form action="index.php" method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                        <input type="text" name="descricao" value="<?php echo htmlspecialchars($tarefa['descricao']); ?>" required>
                        <input type="date" name="data_vencimento" value="<?php echo $tarefa['data_vencimento']; ?>" required>
                        <button type="submit">Salvar</button>
                        <button type="button" class="cancelar" onclick="cancelarEdicao(<?php echo $tarefa['id']; ?>)">Cancelar</button>
                    </form>
                </div>

                <!-- Botões de ação -->
                <div class="botoes" id="botoes-<?php echo $tarefa['id']; ?>">
                    <button class="editar" onclick="iniciarEdicao(<?php echo $tarefa['id']; ?>)">✏️ Editar</button>
                    <form action="update_tarefa.php" method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                        <button type="submit">✓ Concluir</button>
                    </form>
                    <form action="delete_tarefa.php" method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                        <button type="submit" class="excluir" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Lista de tarefas concluídas -->
    <h2>Tarefas Concluídas</h2>
    <?php foreach ($tarefas as $tarefa): ?>
        <?php if ($tarefa['concluida']): ?>
            <div class="tarefa concluida">
                <div>
                    <p><?php echo htmlspecialchars($tarefa['descricao']); ?></p>
                    <small>Vencimento: <?php echo date('d/m/Y', strtotime($tarefa['data_vencimento'])); ?></small>
                </div>
                <div class="botoes">
                    <form action="delete_tarefa.php" method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                        <button type="submit" class="excluir" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <script>
        function iniciarEdicao(id) {
            // Esconde o conteúdo e mostra o formulário
            document.getElementById('conteudo-' + id).style.display = 'none';
            document.getElementById('form-' + id).style.display = 'block';
            document.getElementById('botoes-' + id).style.display = 'none';
        }

        function cancelarEdicao(id) {
            // Mostra o conteúdo e esconde o formulário
            document.getElementById('conteudo-' + id).style.display = 'block';
            document.getElementById('form-' + id).style.display = 'none';
            document.getElementById('botoes-' + id).style.display = 'flex';
        }
    </script>
</body>
</html>