<?php
require __DIR__ . '/sessao.php';
require __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Busca pelo e-mail com consulta preparada e confere a senha pelo hash
    $stmt = mysqli_prepare($conexao, "SELECT id, nome, senha, admin FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['admin'] = (bool) $usuario['admin'];
        header('Location: /StreamingDeFilmes_PHP/filmes/listar.php');
        exit;
    } else {
        $mensagem = 'E-mail ou senha inválidos.';
    }
}
?>

<?php require __DIR__ . '/cabecalho.php'; ?>

<main>
    <h1>Login</h1>

    <?php if (isset($mensagem)) { ?>
        <p class="msg-erro" role="alert"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php } elseif (($_GET['aviso'] ?? '') === 'login_necessario') { ?>
        <p class="msg-aviso" role="status">Faça login para ver e criar avaliações.</p>
    <?php } ?>

    <form action="/StreamingDeFilmes_PHP/login.php" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required autocomplete="email"><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required autocomplete="current-password"><br>

        <button type="submit">Entrar</button>
    </form>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
