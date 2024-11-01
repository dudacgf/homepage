<?php
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;
// entra em sessão
// 
session_set_cookie_params([
    'lifetime' => 60*60,
    'path' => __DIR__,
    'domain' => $_SERVER['SERVER_NAME'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'strict',
]);
//session_cache_limiter('private_no_expire');
session_start();

// se o usuário não está logado, volta pra fazer o login
if (!isset($_SESSION['loggedin'])) {
    $target_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $admPag = new Pagina\Pagina(ID_ADM_PAG);
    $homepage->assign('target_url', $target_url);
    $homepage->assign('admin_area', true);
    $homepage->assign('temaPagina', $admPag->temaPagina);
    $homepage->assign('includePATH', INCLUDE_PATH);
    $homepage->display('admin/login.tpl');
    exit;
    //header('location: ' . INCLUDE_PATH . 'admin/login.php');
}
$homepage->assign('admin_area', true);
?>
