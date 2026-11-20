<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        // Remove avaliações vinculadas antes de excluir o filme para não dar erro de chave estrangeira
        mysqli_query($conexao, "DELETE FROM avaliacoes WHERE filme_id = $id");
        mysqli_query($conexao, "DELETE FROM filmes WHERE id = $id");
        $_SESSION['mensagem'] = "Filme excluído com sucesso!";
    }

    header('Location: listar.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$resultado = mysqli_query($conexao, "SELECT * FROM filmes WHERE id = $id");
$filme = $resultado ? mysqli_fetch_assoc($resultado) : null;

if (!$filme) {
    header('Location: listar.php');
    exit;
}

require __DIR__ . '/../cabecalho.php';
?>

<main>
    <h2>Excluir Filme</h2>

    <div class="excluir-box">
        <i class="fa-solid fa-triangle-exclamation excluir-icone"></i>
        <p>Tem certeza que deseja excluir o filme <strong><?php echo htmlspecialchars($filme['titulo']); ?></strong>?</p>
        <span class="excluir-aviso">Essa ação removerá o filme e as avaliações ligadas a ele.</span>

        <form action="excluir.php" method="POST" class="excluir-form">
            <input type="hidden" name="id" value="<?php echo (int) $filme['id']; ?>">
            <div class="excluir-botoes">
                <button type="submit" class="btn-excluir-confirmar">Sim, excluir</button>
                <a href="listar.php" class="btn-cancelar-acao">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>