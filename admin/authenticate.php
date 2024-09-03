<?php
require_once('../common.php');
include_once($include_path . 'class_login.php');

// entra em sessão
session_cache_limiter('private_no_expires');
session_start();

if ( !isset($requests['username'], $requests['password']) ) 
    // Could not get the data that should have been sent.
    retornaLoginComErro('Preencha os campos Usuário e Senha');

$senha = $requests['password'];
$usuario = usuario::comNome($requests['username']) ;

if (!$usuario) {
    // não achei o usuário
    retornaLoginComErro('Usuário ou senha incorretos');
}

if (password_verify($senha, $usuario->senha)) {
//    session_regenerate_id();
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['name'] = $usuario->usuario;
    $_SESSION['id'] = $usuario->idUsuario;

    if (isset($requests['target_url']))
        header('Location: ' . $requests['target_url']);
    else
        header('Location: ' . INCLUDE_PATH . 'admin/index.php');
} else 
    retornaLoginComErro('Usuário ou senha incorretos');

function retornaLoginComErro($errMsg) {
    global $homepage;

    prepararToast('error', $errMsg);
    $admPag = new pagina(ID_ADM_PAG);
    $homepage->assign('target_url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'//:'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
    $homepage->assign('admin_area', true);
    $homepage->assign('classPagina', $admPag->classPagina);
    $homepage->assign('includePATH', INCLUDE_PATH);
    $homepage->display('admin/login.tpl');
    exit;
}
?>
