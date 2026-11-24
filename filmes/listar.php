<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

$sql = "SELECT 
            f.id,
            f.titulo,
            f.sinopse,
            f.ano_lancamento,
            f.duracao_minutos,
            f.genero,
            f.avaliacao AS avaliacao_base,
            ROUND(AVG(a.Score), 1) AS media_avaliacoes,
            COUNT(a.id) AS total_avaliacoes
        FROM filmes f
        LEFT JOIN avaliacoes a ON a.filme_id = f.id
        GROUP BY f.id
        ORDER BY f.id DESC";

$resultado = mysqli_query($conexao, $sql);

// Busca comentários por filme para passar ao modal em JS
$comentarios_por_filme = [];
$sql_com = "SELECT 
                a.filme_id,
                a.Score,
                a.comentario,
                u.nome AS usuario_nome
            FROM avaliacoes a
            INNER JOIN usuarios u ON a.usuario_id = u.id
            ORDER BY a.id DESC";
$res_com = mysqli_query($conexao, $sql_com);
if ($res_com) {
    while ($c = mysqli_fetch_assoc($res_com)) {
        $fid = (int) $c['filme_id'];
        $comentarios_por_filme[$fid][] = [
            'usuario'    => $c['usuario_nome'],
            'score'      => (int) $c['Score'],
            'comentario' => $c['comentario'],
        ];
    }
}

require __DIR__ . '/../cabecalho.php';
?>

<main>
    <h1>Filmes cadastrados</h1>

    <?php if (isset($_SESSION['mensagem'])) { ?>
        <p class="msg-alerta" role="status"><?php echo htmlspecialchars($_SESSION['mensagem']); ?></p>
        <?php unset($_SESSION['mensagem']); ?>
    <?php } ?>

    <?php if (e_admin()) { ?>
        <a href="cadastrar.php" class="btn-novo">+ Cadastrar novo filme</a>
    <?php } ?>

    <?php if ($resultado && mysqli_num_rows($resultado) > 0) { ?>
        <div class="filmes-grid">
            <?php while ($filme = mysqli_fetch_assoc($resultado)) {
                $id_filme = (int) $filme['id'];
                $media_texto = ($filme['total_avaliacoes'] > 0)
                    ? number_format((float) $filme['media_avaliacoes'], 1, ',', '.') . ' / 5 (' . (int) $filme['total_avaliacoes'] . ')'
                    : number_format((float) $filme['avaliacao_base'], 1, ',', '.') . ' (base)';

                $payload = json_encode([
                    'id'          => $id_filme,
                    'titulo'      => $filme['titulo'],
                    'sinopse'     => $filme['sinopse'],
                    'ano'         => $filme['ano_lancamento'],
                    'duracao'     => (int) $filme['duracao_minutos'],
                    'genero'      => $filme['genero'],
                    'avaliacao'   => $media_texto,
                    'comentarios' => $comentarios_por_filme[$id_filme] ?? [],
                ], JSON_UNESCAPED_UNICODE);

                $dados_attr = htmlspecialchars($payload, ENT_QUOTES, 'UTF-8');
            ?>
                <article class="filme-card">
                    <div class="filme-thumb">
                        <i class="fa-solid fa-film" aria-hidden="true"></i>
                        <span class="badge-nota"><i class="fa-solid fa-star" aria-hidden="true"></i> <?php echo htmlspecialchars($media_texto); ?></span>
                    </div>
                    <div class="filme-corpo">
                        <h3><?php echo htmlspecialchars($filme['titulo']); ?></h3>
                        <div class="filme-meta">
                            <span><?php echo htmlspecialchars($filme['ano_lancamento']); ?></span>
                            <span>•</span>
                            <span><?php echo (int) $filme['duracao_minutos']; ?> min</span>
                        </div>
                        <button type="button" class="btn-ver-mais" data-filme="<?php echo $dados_attr; ?>">
                            Ver mais
                        </button>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p>Nenhum filme cadastrado ainda.</p>
    <?php } ?>
</main>

<!-- Modal de Detalhes do Filme -->
<div id="modal-filme" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-titulo">
        <h3 id="modal-titulo"></h3>
        <p class="modal-sub" id="modal-meta"></p>
        <p class="modal-genero"><strong>Gênero:</strong> <span id="modal-genero"></span></p>
        <p class="modal-avaliacao"><strong>Média de avaliação:</strong> <span id="modal-avaliacao"></span></p>
        <p class="modal-sinopse" id="modal-sinopse"></p>

        <div class="modal-comentarios-area">
            <h4>Avaliações</h4>
            <div id="modal-comentarios"></div>
        </div>

        <div class="modal-acoes">
            <?php if (e_admin()) { ?>
                <a id="btn-editar" href="#" class="acao-editar">Editar</a>
                <a id="btn-excluir" href="#" class="acao-excluir">Excluir</a>
            <?php } ?>
            <a id="btn-avaliar" href="#" class="acao-avaliar">Fazer avaliação</a>
            <button type="button" id="btn-fechar-modal" class="acao-sair">Sair</button>
        </div>
    </div>
</div>

<script src="<?php echo $base_url; ?>js/modal-filmes.js"></script>

<?php require __DIR__ . '/../rodape.php'; ?>