<?php
require_once('../common.php');
include_once($include_path . 'class_login.php');

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
    session_regenerate_id();
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['name'] = $usuario->usuario;
    $_SESSION['id'] = $usuario->idUsuario;

    if (isset($requests['target_url'])) {
        header('Location: ' . target_url);
    } else {
        header('Location: ' . INCLUDE_PATH . 'admin/index.php');
    }
} else 
    retornaLoginComErro('Usuário ou senha incorretos');

function retornaLoginComErro($errMsg) {
    global $homepage;

    prepare_MsgAlerta('error', $errMsg);
    header('location: ' . INCLUDE_PATH . 'admin/login.php');
}
?>
