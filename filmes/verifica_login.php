<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /StreamingDeFilmes_PHP/login.php');
    exit;
}
?>