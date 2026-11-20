<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = '/StreamingDeFilmes_PHP/';
$esta_logado = isset($_SESSION['usuario_id']);
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
            <i class="fa-solid fa-clapperboard"></i>
            StreamUp
        </div>

        <div class="menu-toggle" id="mobile-menu">
            <i class="fa-solid fa-bars"></i>
        </div>

        <nav class="nav">
            <a href="<?php echo $base_url; ?>index.php">Início</a>
            <a href="<?php echo $base_url; ?>filmes/listar.php">Filmes</a>

            <?php if ($esta_logado) { ?>
                <a href="<?php echo $base_url; ?>avaliacoes/listar.php">Avaliações</a>
                <a href="<?php echo $base_url; ?>avaliacoes/avaliar.php">Avaliar</a>
                <a href="<?php echo $base_url; ?>logout.php">Sair</a>
            <?php } else { ?>
                <a href="<?php echo $base_url; ?>login.php?aviso=login_necessario">Avaliações</a>
                <a href="<?php echo $base_url; ?>login.php">Login</a>
            <?php } ?>
        </nav>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenu = document.getElementById('mobile-menu');
            const nav = document.querySelector('.nav');

            if (mobileMenu && nav) {
                mobileMenu.addEventListener('click', () => {
                    nav.classList.toggle('active');
                    const icon = mobileMenu.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-bars');
                        icon.classList.toggle('fa-xmark');
                    }
                });
            }
        });
    </script>