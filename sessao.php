<?php
// Inicia a sessão com o cookie protegido: sem acesso por JavaScript e não enviado por outros sites
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}
