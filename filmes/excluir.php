<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM filmes WHERE id = '$id'";
    mysqli_query($conexao, $sql);

    header('Location: listar.php');
    exit;
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM filmes WHERE id = '$id'";
    $resultado = mysqli_query($conexao, $sql);
    $filme = mysqli_fetch_assoc($resultado);
}
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Excluir Filme</h2>
    <p>Tem certeza que deseja excluir o filme
       <strong><?php echo $filme['titulo']; ?></strong>?</p>

    <form action="excluir.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $filme['id']; ?>">
        <button type="submit">Sim, excluir</button>
        <a href="listar.php">Cancelar</a>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>