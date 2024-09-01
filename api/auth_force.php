<?php
header( 'Content-Type: application/json');
require_once('../common.php');

// entra em sessão
session_start();

// se o usuário não está logado, volta pra fazer o login
if (!isset($_SESSION['loggedin'])) {
    $homepage->assign('response', '{"status": "error", "message": "you must log in before using any api"}');
    $homepage->display('response.tpl');
    exit;
}
?>
