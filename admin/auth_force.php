<?php
require_once('../common.php');

// entra em sessão
session_start();

// se o usuário não está logado, volta pra fazer o login
if (!isset($_SESSION['loggedin'])) 
    header('location: ' . INCLUDE_PATH . 'admin/login.php');
?>
