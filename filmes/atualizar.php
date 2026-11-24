<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';
require __DIR__ . '/validar_filme.php';

// 1. Quando clicar em "Salvar alterações" (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    [$dados, $mensagem] = validar_filme($_POST);

    if ($id > 0 && $mensagem === null) {
        $stmt = mysqli_prepare(
            $conexao,
            "UPDATE filmes SET titulo = ?, sinopse = ?, ano_lancamento = ?, avaliacao = ?, duracao_minutos = ?, genero = ?
             WHERE id = ?"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'ssidisi',
            $dados['titulo'],
            $dados['sinopse'],
            $dados['ano_lancamento'],
            $dados['avaliacao'],
            $dados['duracao_minutos'],
            $dados['genero'],
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensagem'] = "Filme atualizado com sucesso!";
            header('Location: listar.php');
            exit;
        }
        $mensagem = "Erro ao atualizar filme.";
    }

    // Validação falhou: mostra o formulário de novo com o que foi digitado
    $filme = $dados + ['id' => $id];
}

// 2. Abrindo a página pelo botão "Editar" (GET)
if (!isset($filme)) {
    $id = (int) ($_GET['id'] ?? 0);
    $stmt = mysqli_prepare($conexao, "SELECT * FROM filmes WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $filme = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$filme) {
        header('Location: listar.php');
        exit;
    }
}

require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Atualizar Filme</h2>

    <?php if (isset($mensagem)) { ?>
        <p><?php echo htmlspecialchars($mensagem); ?></p>
    <?php } ?>

    <form action="atualizar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo (int) $filme['id']; ?>">

        <label>Título:</label>
        <input type="text" name="titulo" maxlength="150" value="<?php echo valor_campo($filme, 'titulo'); ?>" required><br>

        <label>Sinopse:</label>
        <textarea name="sinopse" required><?php echo valor_campo($filme, 'sinopse'); ?></textarea><br>

        <label>Ano de Lançamento:</label>
        <input type="number" name="ano_lancamento" min="1901" max="2155" value="<?php echo valor_campo($filme, 'ano_lancamento'); ?>"
            required><br>

        <label>Avaliação (0 a 5):</label>
        <input type="number" name="avaliacao" step="0.01" min="0" max="5" value="<?php echo valor_campo($filme, 'avaliacao'); ?>"
            required><br>

        <label>Duração (minutos):</label>
        <input type="number" name="duracao_minutos" min="1" max="999" value="<?php echo valor_campo($filme, 'duracao_minutos'); ?>" required><br>

        <label>Gênero:</label>
        <input type="text" name="genero" maxlength="50" value="<?php echo valor_campo($filme, 'genero'); ?>" required><br>

        <button type="submit">Salvar alterações</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
