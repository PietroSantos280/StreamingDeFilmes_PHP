<?php
require __DIR__ . '/sessao.php';

// Limpa os dados, apaga o cookie e encerra a sessão
$_SESSION = [];
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
session_destroy();

header('Location: login.php');
exit;
