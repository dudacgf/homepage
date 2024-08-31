<?php
require_once('../common.php');

// entra em sessão
// 
session_set_cookie_params([
    'lifetime' => 60*60,
    'path' => __DIR__,
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'strict',
]);
session_start();

// se o usuário não está logado, volta pra fazer o login
if (!isset($_SESSION['loggedin'])) 
    header('location: ' . INCLUDE_PATH . 'admin/login.php');

$homepage->assign('admin_area', true);
?>
