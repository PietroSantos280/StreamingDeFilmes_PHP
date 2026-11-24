<?php
require __DIR__ . '/../filmes/verifica_login.php';
require __DIR__ . '/../conexao.php';

$filme_selecionado = (int) ($_GET['filme_id'] ?? 0);
$comentario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = (int) ($_SESSION['usuario_id'] ?? 0);
    $filme_id   = (int) ($_POST['filme_id'] ?? 0);
    $score      = (int) ($_POST['Score'] ?? 0);
    $comentario = trim($_POST['comentario'] ?? '');
    $filme_selecionado = $filme_id;

    if ($usuario_id <= 0 || $filme_id <= 0 || $score < 1 || $score > 5) {
        $mensagem = "Selecione um filme e dê uma nota válida entre 1 e 5.";
    } else {
        try {
            $stmt = mysqli_prepare(
                $conexao,
                "INSERT INTO avaliacoes (usuario_id, filme_id, Score, comentario) VALUES (?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, 'iiis', $usuario_id, $filme_id, $score, $comentario);
            mysqli_stmt_execute($stmt);

            $_SESSION['mensagem'] = "Avaliação registrada com sucesso!";
            header('Location: listar.php');
            exit;
        } catch (mysqli_sql_exception $e) {
            // Ex.: o filme foi excluído em outra aba
            $mensagem = "Não foi possível registrar a avaliação. Verifique se o filme ainda existe.";
        }
    }
}

$res_filmes = mysqli_query($conexao, "SELECT id, titulo FROM filmes ORDER BY titulo ASC");

require __DIR__ . '/../cabecalho.php';
?>

<main>
    <h2>Nova Avaliação</h2>

    <?php if (isset($mensagem)) { ?>
        <p class="msg-erro" role="alert"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php } ?>

    <form action="avaliar.php" method="POST">
        <?php echo campo_csrf(); ?>

        <label for="filme_id">Filme:</label>
        <select name="filme_id" id="filme_id" required>
            <option value="">-- Selecione um filme --</option>
            <?php if ($res_filmes) { ?>
                <?php while ($filme = mysqli_fetch_assoc($res_filmes)) { ?>
                    <option value="<?php echo (int) $filme['id']; ?>" <?php echo ((int) $filme['id'] === $filme_selecionado) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($filme['titulo']); ?>
                    </option>
                <?php } ?>
            <?php } ?>
        </select>

        <label for="Score">Nota (1 a 5):</label>
        <input type="number" name="Score" id="Score" min="1" max="5" required>

        <label for="comentario">Comentário:</label>
        <textarea name="comentario" id="comentario" rows="4" placeholder="Deixe seu comentário (opcional)"><?php echo htmlspecialchars($comentario); ?></textarea>

        <button type="submit">Salvar Avaliação</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>
