<?php
require __DIR__ . '/../filmes/verifica_login.php';
require __DIR__ . '/../conexao.php';

if (!$conexao) {
    die("Erro na conexão com o banco: " . mysqli_connect_error());
}
function atualizarMediaFilme(mysqli $conexao, int $filme_id): void
{
    $sql_media = "UPDATE filmes
                  SET avaliacao = COALESCE(
                      (SELECT ROUND(AVG(Score), 2) FROM avaliacoes WHERE filme_id = $filme_id),
                      0.00
                  )
                  WHERE id = $filme_id";
    mysqli_query($conexao, $sql_media);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = (int) ($_SESSION['usuario_id'] ?? 0);
    $filme_id   = (int) ($_POST['filme_id'] ?? 0);
    $score      = (int) ($_POST['Score'] ?? 0);
    $comentario = mysqli_real_escape_string($conexao, trim($_POST['comentario'] ?? ''));

    if ($usuario_id <= 0 || $filme_id <= 0 || $score < 1 || $score > 5) {
        $mensagem = "Selecione um filme e dê uma nota válida entre 1 e 5.";
    } else {
        $sql = "INSERT INTO avaliacoes (usuario_id, filme_id, Score, comentario)
                VALUES ($usuario_id, $filme_id, $score, '$comentario')";

        if (mysqli_query($conexao, $sql)) {
            atualizarMediaFilme($conexao, $filme_id);
            $_SESSION['mensagem'] = "Avaliação registrada com sucesso!";
            header('Location: listar.php');
            exit;
        } else {
            $mensagem = "Erro ao registrar avaliação: " . mysqli_error($conexao);
        }
    }
}

$sql_filmes = "SELECT id, titulo FROM filmes ORDER BY titulo ASC";
$res_filmes = mysqli_query($conexao, $sql_filmes);
$filme_selecionado = isset($_GET['filme_id']) ? (int) $_GET['filme_id'] : 0;

require __DIR__ . '/../cabecalho.php';
?>

<main>
    <h2>Nova Avaliação</h2>

    <form action="avaliar.php" method="POST">
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
        <textarea name="comentario" id="comentario" rows="4" placeholder="Deixe seu comentário (opcional)"></textarea>

        <button type="submit">Salvar Avaliação</button>
    </form>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>