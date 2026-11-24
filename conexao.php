<?php
    // conexao.php
    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "sistema_streaming";

    // No PHP 8.1+ o mysqli lança exceção quando o banco não responde;
    // mostra uma mensagem amigável em vez do erro técnico
    try {
        $conexao = mysqli_connect($host, $usuario, $senha, $banco);
        mysqli_set_charset($conexao, 'utf8mb4');
    } catch (mysqli_sql_exception $e) {
        http_response_code(500);
        exit('Não foi possível conectar ao banco de dados. Verifique se o MySQL está ligado no XAMPP.');
    }
?>
