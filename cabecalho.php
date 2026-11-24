<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = '/StreamingDeFilmes_PHP/';
$esta_logado = isset($_SESSION['usuario_id']);

// Marca no menu o link da página atual
$pagina_atual = $_SERVER['SCRIPT_NAME'];
function link_ativo($trecho)
{
    global $pagina_atual;
    return str_contains($pagina_atual, $trecho) ? ' class="ativo" aria-current="page"' : '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreamUp - Streaming & Avaliações</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css">
</head>

<body>

    <header class="header">
        <div class="logo">
            <i class="fa-solid fa-clapperboard" aria-hidden="true"></i>
            StreamUp
        </div>

        <button type="button" class="menu-toggle" id="mobile-menu" aria-label="Abrir menu" aria-expanded="false" aria-controls="menu-principal">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>

        <nav class="nav" id="menu-principal" aria-label="Principal">
            <a href="<?php echo $base_url; ?>index.php"<?php echo link_ativo('/index.php'); ?>>Início</a>
            <a href="<?php echo $base_url; ?>filmes/listar.php"<?php echo link_ativo('/filmes/'); ?>>Filmes</a>

            <?php if ($esta_logado) { ?>
                <a href="<?php echo $base_url; ?>avaliacoes/listar.php"<?php echo link_ativo('/avaliacoes/listar.php'); ?>>Avaliações</a>
                <a href="<?php echo $base_url; ?>avaliacoes/avaliar.php"<?php echo link_ativo('/avaliacoes/avaliar.php'); ?>>Avaliar</a>
                <a href="<?php echo $base_url; ?>logout.php">Sair</a>
            <?php } else { ?>
                <a href="<?php echo $base_url; ?>login.php?aviso=login_necessario">Avaliações</a>
                <a href="<?php echo $base_url; ?>login.php"<?php echo link_ativo('/login.php'); ?>>Login</a>
            <?php } ?>
        </nav>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenu = document.getElementById('mobile-menu');
            const nav = document.querySelector('.nav');

            if (mobileMenu && nav) {
                mobileMenu.addEventListener('click', () => {
                    const aberto = nav.classList.toggle('active');
                    mobileMenu.setAttribute('aria-expanded', aberto);
                    mobileMenu.setAttribute('aria-label', aberto ? 'Fechar menu' : 'Abrir menu');
                    const icon = mobileMenu.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-bars');
                        icon.classList.toggle('fa-xmark');
                    }
                });
            }
        });
    </script>