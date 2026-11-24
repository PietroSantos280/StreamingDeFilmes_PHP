<?php
require __DIR__ . '/../filmes/verifica_login.php';
require __DIR__ . '/../conexao.php';

$sql = "SELECT
            a.id,
            a.usuario_id,
            a.Score,
            a.comentario,
            u.nome AS usuario_nome,
            f.titulo AS filme_titulo
        FROM avaliacoes a
        INNER JOIN usuarios u ON a.usuario_id = u.id
        INNER JOIN filmes f ON a.filme_id = f.id
        ORDER BY a.id DESC";

$resultado = mysqli_query($conexao, $sql);

require __DIR__ . '/../cabecalho.php';
?>

<main>
    <div class="topo-secao">
        <h1>Avaliações dos Usuários</h1>
        <a href="avaliar.php" class="btn-novo-acao"><i class="fa-solid fa-plus" aria-hidden="true"></i> Nova Avaliação</a>
    </div>

    <?php if (isset($_SESSION['mensagem'])) { ?>
        <p class="msg-alerta" role="status"><?php echo htmlspecialchars($_SESSION['mensagem']); ?></p>
        <?php unset($_SESSION['mensagem']); ?>
    <?php } ?>

    <?php if ($resultado && mysqli_num_rows($resultado) > 0) { ?>
        <div class="avaliacoes-grid">
            <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                <article class="avaliacao-card">
                    <div class="avaliacao-header">
                        <h3><?php echo htmlspecialchars($row['filme_titulo']); ?></h3>
                        <span class="avaliacao-score"><i class="fa-solid fa-star" aria-hidden="true"></i> <?php echo (int) $row['Score']; ?> / 5</span>
                    </div>

                    <p class="avaliacao-autor">
                        <i class="fa-regular fa-user" aria-hidden="true"></i> <?php echo htmlspecialchars($row['usuario_nome']); ?>
                    </p>

                    <div class="avaliacao-comentario">
                        <?php echo !empty($row['comentario']) ? nl2br(htmlspecialchars((string) $row['comentario'])) : '<em>Sem comentário.</em>'; ?>
                    </div>

                    <?php if ((int) $row['usuario_id'] === (int) $_SESSION['usuario_id']) { ?>
                        <div class="avaliacao-rodape">
                            <form action="excluir.php" method="POST" class="form-inline" onsubmit="return confirm('Deseja excluir esta avaliação?');">
                                <?php echo campo_csrf(); ?>
                                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                <button type="submit" class="btn-excluir-card">
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i> Excluir
                                </button>
                            </form>
                        </div>
                    <?php } ?>
                </article>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>Nenhuma avaliação encontrada.</p>
    <?php } ?>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>