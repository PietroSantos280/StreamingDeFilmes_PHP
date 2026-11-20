<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

// 1. Quando clicar em "Salvar alterações" (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $sinopse = $_POST['sinopse'];
    $ano_lancamento = $_POST['ano_lancamento'];
    $avaliacao = $_POST['avaliacao'];
    $duracao_minutos = $_POST['duracao_minutos'];
    $genero = $_POST['genero'];

    $sql = "UPDATE filmes SET 
                titulo = '$titulo',
                sinopse = '$sinopse',
                ano_lancamento = '$ano_lancamento',
                avaliacao = '$avaliacao',
                duracao_minutos = '$duracao_minutos',
                genero = '$genero'
            WHERE id = '$id'";

    if (mysqli_query($conexao, $sql)) {
        $_SESSION['mensagem'] = "Filme atualizado com sucesso!";
        header('Location: listar.php');
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conexao);
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: listar.php');
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM filmes WHERE id = '$id'";
$resultado = mysqli_query($conexao, $sql);
$filme = mysqli_fetch_assoc($resultado);

if (!$filme) {
    die("Filme não encontrado.");
}

require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Atualizar Filme</h2>
    <form action="atualizar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $filme['id']; ?>">

        <label>Título:</label>
        <input type="text" name="titulo" maxlength="150" value="<?php echo $filme['titulo']; ?>" required><br>

        <label>Sinopse:</label>
        <textarea name="sinopse" required><?php echo $filme['sinopse']; ?></textarea><br>

        <label>Ano de Lançamento:</label>
        <input type="number" name="ano_lancamento" min="1901" max="2155" value="<?php echo $filme['ano_lancamento']; ?>"
            required><br>

        <label>Avaliação:</label>
        <input type="number" name="avaliacao" step="0.01" min="0" max="9.99" value="<?php echo $filme['avaliacao']; ?>"
            required><br>

        <label>Duração (minutos):</label>
        <input type="number" name="duracao_minutos" value="<?php echo $filme['duracao_minutos']; ?>" required><br>

        <label>Gênero:</label>
        <input type="text" name="genero" maxlength="50" value="<?php echo $filme['genero']; ?>" required><br>

        <button type="submit">Salvar alterações</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>