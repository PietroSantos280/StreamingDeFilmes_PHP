<?php
require __DIR__ . '/verifica_login.php';
exigir_admin();
require __DIR__ . '/../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        // Transação: apaga as avaliações e o filme juntos, ou nada se der erro no meio
        mysqli_begin_transaction($conexao);
        try {
            foreach (['DELETE FROM avaliacoes WHERE filme_id = ?', 'DELETE FROM filmes WHERE id = ?'] as $sql) {
                $stmt = mysqli_prepare($conexao, $sql);
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
            }
            mysqli_commit($conexao);
            $_SESSION['mensagem'] = "Filme excluído com sucesso!";
        } catch (mysqli_sql_exception $e) {
            mysqli_rollback($conexao);
            $_SESSION['mensagem'] = "Erro ao excluir filme. Nada foi apagado.";
        }
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
    <h1>Excluir Filme</h1>

    <div class="excluir-box">
        <i class="fa-solid fa-triangle-exclamation excluir-icone" aria-hidden="true"></i>
        <p>Tem certeza que deseja excluir o filme <strong><?php echo htmlspecialchars($filme['titulo']); ?></strong>?</p>
        <span class="excluir-aviso">Essa ação removerá o filme e as avaliações ligadas a ele.</span>

        <form action="excluir.php" method="POST" class="excluir-form">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="id" value="<?php echo (int) $filme['id']; ?>">
            <div class="excluir-botoes">
                <button type="submit" class="btn-excluir-confirmar">Sim, excluir</button>
                <a href="listar.php" class="btn-cancelar-acao">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>