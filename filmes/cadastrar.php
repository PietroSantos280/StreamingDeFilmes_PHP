<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $sinopse = $_POST['sinopse'];
    $ano = $_POST['ano_lancamento'];
    $duracao = $_POST['duracao_minutos'];
    $avaliacao = $_POST['avaliacao'];
    $genero = $_POST['genero'];
    if ($titulo == "" || $sinopse == "" || $ano == "" || $duracao == "" || $avaliacao == "" || $genero == "") {
        $mensagem = "Preencha todos os campos obrigatórios.";
    } else {
        $sql = "INSERT INTO filmes (titulo, sinopse, ano_lancamento, duracao_minutos, avaliacao, genero)
                VALUES ('$titulo', '$sinopse', '$ano', '$duracao', '$avaliacao','$genero')";

        if (mysqli_query($conexao, $sql)) {
            header('Location: listar.php');
            exit;
        } else {
            $mensagem = "Erro ao cadastrar produto: " . mysqli_error($conexao);
        }
    }
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Cadastrar Produto</h2>

    <?php if (isset($mensagem)) { ?>
        <p><?php echo $mensagem; ?></p>
    <?php } ?>

    <form action="cadastrar.php" method="POST">
        <label>Título:</label>
        <input type="text" name="titulo"><br>

        <label>Sinopse:</label>
        <textarea name="sinopse"></textarea><br>

        <label>Ano de Lançamento:</label>
        <input type="number" name="ano_lancamento"><br>

        <label>Duração (minutos):</label>
        <input type="number" name="duracao_minutos"><br>

        <label>Avaliação:</label>
        <input type="text" name="avaliacao"><br>

        <label>Gênero:</label>
        <input type="text" name="genero"><br>

        <button type="submit">Salvar</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>