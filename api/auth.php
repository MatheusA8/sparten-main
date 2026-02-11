<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function verificar_login() {
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header("Location: index.php");
        exit;
    }
}


function verificar_admin() {
    verificar_login();

    if ($_SESSION['usuario_tipo'] !== 'admin') {
        header("Location: dashboard.php");
        exit;
    }
}
