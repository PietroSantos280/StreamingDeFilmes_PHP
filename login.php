<?php
session_start();
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Busca pelo e-mail com consulta preparada e confere a senha pelo hash
    $stmt = mysqli_prepare($conexao, "SELECT id, nome, senha FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header('Location: /StreamingDeFilmes_PHP/filmes/listar.php');
        exit;
    } else {
        $mensagem = 'E-mail ou senha inválidos.';
    }
}
?>

<?php require __DIR__ . '/cabecalho.php'; ?>

<main>
    <h2>Login</h2>

    <?php if (isset($mensagem)) { ?>
        <p><?php echo $mensagem; ?></p>
    <?php } ?>

    <form action="/StreamingDeFilmes_PHP/login.php" method="POST">
        <label>E-mail:</label>
        <input type="text" name="email"><br>

        <label>Senha:</label>
        <input type="password" name="senha"><br>

        <button type="submit">Entrar</button>
    </form>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
