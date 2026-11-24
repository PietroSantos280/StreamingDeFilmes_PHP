<?php
require __DIR__ . '/conexao.php';
require __DIR__ . '/cabecalho.php';

$sql_destaques = "SELECT 
                    f.id, f.titulo, f.sinopse, f.ano_lancamento, f.duracao_minutos, f.genero,
                    COALESCE(ROUND(AVG(a.Score), 1), f.avaliacao) AS nota_final
                  FROM filmes f
                  LEFT JOIN avaliacoes a ON a.filme_id = f.id
                  GROUP BY f.id
                  ORDER BY nota_final DESC, f.id DESC
                  LIMIT 3";
$res_destaques = mysqli_query($conexao, $sql_destaques);
?>

<main>
    <section class="hero-home">
        <span class="hero-tag"><i class="fa-solid fa-bolt" aria-hidden="true"></i> Streaming & Críticas</span>
        <h1>Bem-vindo ao <span>StreamUp</span></h1>
        <p>Acompanhe os filmes em destaque e as notas da comunidade.</p>
    </section>

    <section class="destaques-home">
        <h2>Destaques do Catálogo</h2>
        <?php if ($res_destaques && mysqli_num_rows($res_destaques) > 0) { ?>
            <div class="filmes-grid">
                <?php while ($filme = mysqli_fetch_assoc($res_destaques)) { ?>
                    <article class="filme-card">
                        <div class="filme-thumb">
                            <i class="fa-solid fa-clapperboard" aria-hidden="true"></i>
                            <span class="badge-nota">
                                <i class="fa-solid fa-star" aria-hidden="true"></i> <?php echo number_format((float) $filme['nota_final'], 1, ',', '.'); ?> / 5
                            </span>
                        </div>
                        <div class="filme-corpo">
                            <h3><?php echo htmlspecialchars($filme['titulo']); ?></h3>
                            <div class="filme-meta">
                                <span><?php echo htmlspecialchars($filme['ano_lancamento']); ?></span>
                                <span>•</span>
                                <span><?php echo (int) $filme['duracao_minutos']; ?> min</span>
                                <span>•</span>
                                <span><?php echo htmlspecialchars($filme['genero']); ?></span>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p>Nenhum filme em destaque no momento.</p>
        <?php } ?>
    </section>
</main>

<?php require __DIR__ . '/rodape.php'; ?>