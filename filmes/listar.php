
<?php
require __DIR__ . '/verifica_login.php';
require __DIR__ . '/../conexao.php';

$sql = "SELECT * FROM filmes";
$resultado = mysqli_query($conexao, $sql);
?>

<?php require __DIR__ . '/../cabecalho.php'; ?>

<main>
    <h2>Produtos cadastrados</h2>
    <a href="cadastrar.php">Cadastrar novo produto</a>

    <table>
        <tr>
            <th>Titulo</th>
            <th>Sinopse</th>
            <th>Ano</th>
            <th>Avaliação</th>
            <th>duração</th>
            <th>Genêro</th>
        </tr>

        <?php while ($filme = mysqli_fetch_assoc($resultado)) { ?>
        <tr>
            <td><?php echo $filme['titulo']; ?></td>
            <td><?php echo $filme['sinopse']; ?></td>
            <td><?php echo $filme['ano_lancamento']; ?></td>
            <td><?php echo $filme['avaliacao']; ?></td>
            <td><?php echo $filme['duracao_minutos']; ?></td>
            <td><?php echo $filme['genero']; ?></td>
            <td>
                <a href="atualizar.php?id=<?php echo $filme['id']; ?>">Editar</a>
                <a href="excluir.php?id=<?php echo $filme['id']; ?>">Excluir</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</main>

<?php require __DIR__ . '/../rodape.php'; ?>