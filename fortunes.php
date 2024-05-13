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

// id_Pagina é sempre 1.
$_idPagina = 1;

// le os cookies e passa para a p�gina a ser carregada.
$cookedStyles = '';
$colorCookies = cookedStyle::getArray($_idPagina);
if ($colorCookies) 
{
	foreach ($colorCookies as $selector => $colorCookie) {
		$cookedStyles .= implode("\n", $colorCookie) . "\n}\n";
	}
}
$homepage->assign('cookedStyles', $cookedStyles);

// lê o biscoitinho da sorte
require($include_path . "class_fortune.php");
$f = new Fortune;
$biscoitinho = $f->fortune;
if (strpos($biscoitinho, "--") > 0) 
{
	$biscoitinho = str_replace("--", "<b>--", $biscoitinho) . "</b>";
}
$homepage->assign("fortuneCookie", $biscoitinho);

// Monta o restante da p�gina
$homepage->left_delimiter = '<!--{';
$homepage->right_delimiter = '}-->';

$homepage->assign('imagesPATH', $images_path);
$homepage->assign('idPagina', $_idPagina);
$homepage->assign('classPagina', 'gAbobora');

$homepage->assign('relativePATH', RELATIVE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->display('ftnPage.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
