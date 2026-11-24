<?php
require __DIR__ . '/../sessao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /StreamingDeFilmes_PHP/login.php');
    exit;
}

// Token CSRF: todo formulário POST das páginas protegidas envia este valor
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

function campo_csrf()
{
    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}

// Só administradores cadastram, editam e excluem filmes
function e_admin()
{
    return !empty($_SESSION['admin']);
}

function exigir_admin()
{
    if (!e_admin()) {
        $_SESSION['mensagem'] = 'Apenas administradores podem cadastrar, editar ou excluir filmes.';
        header('Location: /StreamingDeFilmes_PHP/filmes/listar.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    http_response_code(400);
    exit('Requisição inválida. Volte e recarregue a página.');
}
?>
