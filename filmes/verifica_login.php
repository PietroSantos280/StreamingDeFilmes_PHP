<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    http_response_code(400);
    exit('Requisição inválida. Volte e recarregue a página.');
}
?>
