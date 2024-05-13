<?php

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
define('HOMEPAGE_PATH', getcwd() . '/../');
define('RELATIVE_PATH', './../');
include_once(HOMEPAGE_PATH . 'common.php');
include_once($include_path  . 'class_fortune.php');

// obtém as estatísticas na base e as repassa ao template
$homepage->assign('numPaginas', pagina::getCount());
$homepage->assign('numCategorias', categoria::getCount());
$homepage->assign('numGrupos', grupo::getCount());
$homepage->assign('numLinks', wLink::getCount());
$homepage->assign('numForms', wForm::getCount());
$homepage->assign('numSeparadores', wSeparador::getCount());
$homepage->assign('numImagens', wImagem::getCount());
$homepage->assign('numRssFeeds', wRssFeed::getCount());
$homepage->assign('numTemplates', wTemplate::getCount());
$homepage->assign('numFortunes', Fortune::getCount());

// le os cookies e passa para a página a ser carregada.
$cookedStyles = '';
$colorCookies = cookedStyle::getArray(5);
if ($colorCookies) 
{
	foreach ($colorCookies as $selector => $colorCookie) {
		$cookedStyles .= implode("\n", $colorCookie) . "\n}\n";
	}
}
$homepage->assign('cookedStyles', $cookedStyles);

// propriedades gerais da página
$homepage->assign('displayImagemTitulo', '1');

$homepage->assign('classPagina', 'admin');
$homepage->assign('tituloPaginaAlternativo', $lang['paginaEstatisticasTituloPagina']);
$homepage->assign('tituloTabelaAlternativo', $lang['paginaEstatisticasTituloTabela']);
$homepage->assign('relativePATH', RELATIVE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->assign('displaySelectColor', 0);
$homepage->display('admin/estatisticas.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
