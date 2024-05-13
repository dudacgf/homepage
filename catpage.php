<?php

//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
define('HOMEPAGE_PATH', './');
define('RELATIVE_PATH', './');
include_once(HOMEPAGE_PATH . 'common.php');

// classes espec�ficas da homepage
include_once($include_path . 'class_homepage.php');

// garante que v�o aparecer todos os grupos
$_REQUEST['gr'] = 'all';

$homepage->assign('idPagina', 1);
$homepage->assign('categorias', categoria::getCategorias());
$homepage->assign('tituloPaginaAlternativo', 'teste de Categorias din�micas');
$homepage->assign('tituloTabelaAlternativo', 'selecione a categoria a exibir:');
$homepage->assign('classPagina', 'admin');

$homepage->assign('relativePATH', RELATIVE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->display('catPage.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
