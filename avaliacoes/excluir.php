<?php
require __DIR__ . '/../filmes/verifica_login.php';
require __DIR__ . '/../conexao.php';

// Só aceita POST (com token CSRF, conferido em verifica_login.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $usuario_id = (int) ($_SESSION['usuario_id'] ?? 0);

    // O "AND usuario_id" garante que cada usuário só exclui as próprias avaliações.
    // A média é calculada na hora nas telas; a nota cadastrada do filme não é alterada.
    $stmt = mysqli_prepare($conexao, "DELETE FROM avaliacoes WHERE id = ? AND usuario_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $id, $usuario_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) === 1) {
        $_SESSION['mensagem'] = "Avaliação excluída com sucesso!";
    }
}

header('Location: listar.php');
exit;
