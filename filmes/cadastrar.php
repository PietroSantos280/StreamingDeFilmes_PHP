<?php
require __DIR__ . '/verifica_login.php';
exigir_admin();
require __DIR__ . '/../conexao.php';
require __DIR__ . '/validar_filme.php';

$dados = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    [$dados, $mensagem] = validar_filme($_POST);

    if ($mensagem === null) {
        try {
            $stmt = mysqli_prepare(
                $conexao,
                "INSERT INTO filmes (titulo, sinopse, ano_lancamento, duracao_minutos, avaliacao, genero)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param(
                $stmt,
                'ssiids',
                $dados['titulo'],
                $dados['sinopse'],
                $dados['ano_lancamento'],
                $dados['duracao_minutos'],
                $dados['avaliacao'],
                $dados['genero']
            );
            mysqli_stmt_execute($stmt);

            $_SESSION['mensagem'] = "Filme cadastrado com sucesso!";
            header('Location: listar.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            $mensagem = "Erro ao cadastrar filme.";
        }
    }
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h1>Cadastrar Filme</h1>

    <?php if (isset($mensagem)) { ?>
        <p class="msg-erro" role="alert"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php } ?>

    <form action="cadastrar.php" method="POST">
        <?php echo campo_csrf(); ?>

        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" maxlength="150" value="<?php echo valor_campo($dados, 'titulo'); ?>" required><br>

        <label for="sinopse">Sinopse:</label>
        <textarea name="sinopse" id="sinopse" required><?php echo valor_campo($dados, 'sinopse'); ?></textarea><br>

        <label for="ano_lancamento">Ano de Lançamento:</label>
        <input type="number" name="ano_lancamento" id="ano_lancamento" min="1901" max="2155" value="<?php echo valor_campo($dados, 'ano_lancamento'); ?>" required><br>

        <label for="duracao_minutos">Duração (minutos):</label>
        <input type="number" name="duracao_minutos" id="duracao_minutos" min="1" max="999" value="<?php echo valor_campo($dados, 'duracao_minutos'); ?>" required><br>

        <label for="avaliacao">Avaliação (0 a 5):</label>
        <input type="number" name="avaliacao" id="avaliacao" step="0.01" min="0" max="5" value="<?php echo valor_campo($dados, 'avaliacao'); ?>" required><br>

        <label for="genero">Gênero:</label>
        <input type="text" name="genero" id="genero" maxlength="50" value="<?php echo valor_campo($dados, 'genero'); ?>" required><br>

        <button type="submit">Salvar</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
