<?php
$base_url = '/projeto_php/';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css">
    <title>Projeto PHP - CRUD</title>
</head>

<body>
    <header>
        <h1>Sistema de Produtos</h1>
        <input type="checkbox" id="nav-toggle" class="nav-toggle-input">

        <!-- Ícone do Sanduíche com as 3 barrinhas -->
        <label for="nav-toggle" class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
        </label>
        <nav>
            <a href="<?php echo $base_url; ?>index.php">Início</a>
            <a href="<?php echo $base_url; ?>produtos/listar.php">Produtos</a>
            <a href="<?php echo $base_url; ?>login.php">Login</a>
            <a href="<?php echo $base_url; ?>logout.php">Sair</a>
        </nav>
    </header>v