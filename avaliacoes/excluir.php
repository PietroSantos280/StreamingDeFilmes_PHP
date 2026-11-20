<?php
require __DIR__ . '/../filmes/verifica_login.php';
require __DIR__ . '/../conexao.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $usuario_id = (int) ($_SESSION['usuario_id'] ?? 0);

    $busca = mysqli_query($conexao, "SELECT filme_id FROM avaliacoes WHERE id = $id AND usuario_id = $usuario_id");
    $avaliacao = $busca ? mysqli_fetch_assoc($busca) : null;

    if ($avaliacao) {
        $filme_id = (int) $avaliacao['filme_id'];
        if (mysqli_query($conexao, "DELETE FROM avaliacoes WHERE id = $id AND usuario_id = $usuario_id")) {
            mysqli_query(
                $conexao,
                "UPDATE filmes
                 SET avaliacao = COALESCE(
                     (SELECT ROUND(AVG(Score), 2) FROM avaliacoes WHERE filme_id = $filme_id),
                     0.00
                 )
                 WHERE id = $filme_id"
            );
            $_SESSION['mensagem'] = "Avaliação excluída com sucesso!";
        }
    }
}

header('Location: listar.php');
exit;