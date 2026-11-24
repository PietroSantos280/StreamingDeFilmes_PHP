<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';
require __DIR__ . '/validar_filme.php';

$dados = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    [$dados, $mensagem] = validar_filme($_POST);

    if ($mensagem === null) {
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

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensagem'] = "Filme cadastrado com sucesso!";
            header('Location: listar.php');
            exit;
        }
        $mensagem = "Erro ao cadastrar filme.";
    }
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Cadastrar Filme</h2>

    <?php if (isset($mensagem)) { ?>
        <p><?php echo htmlspecialchars($mensagem); ?></p>
    <?php } ?>

    <form action="cadastrar.php" method="POST">
        <label>Título:</label>
        <input type="text" name="titulo" maxlength="150" value="<?php echo valor_campo($dados, 'titulo'); ?>"><br>

        <label>Sinopse:</label>
        <textarea name="sinopse"><?php echo valor_campo($dados, 'sinopse'); ?></textarea><br>

        <label>Ano de Lançamento:</label>
        <input type="number" name="ano_lancamento" min="1901" max="2155" value="<?php echo valor_campo($dados, 'ano_lancamento'); ?>"><br>

        <label>Duração (minutos):</label>
        <input type="number" name="duracao_minutos" min="1" max="999" value="<?php echo valor_campo($dados, 'duracao_minutos'); ?>"><br>

        <label>Avaliação (0 a 5):</label>
        <input type="number" name="avaliacao" step="0.01" min="0" max="5" value="<?php echo valor_campo($dados, 'avaliacao'); ?>"><br>

        <label>Gênero:</label>
        <input type="text" name="genero" maxlength="50" value="<?php echo valor_campo($dados, 'genero'); ?>"><br>

        <button type="submit">Salvar</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
