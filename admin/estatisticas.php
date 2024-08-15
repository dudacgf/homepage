<?php
//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');
include_once($include_path  . 'class_fortune.php');

// obtém as estatísticas na base e as repassa ao template
$homepage->assign('numPaginas', pagina::getCount());
$homepage->assign('numCategorias', categoria::getCount());
$homepage->assign('numGrupos', grupo::getCount());
$homepage->assign('numLinks', wLink::getCount());
$homepage->assign('numForms', wForm::getCount());
$homepage->assign('numSeparadores', wSeparador::getCount());
$homepage->assign('numImagens', wImagem::getCount());
$homepage->assign('numTemplates', wTemplate::getCount());
$homepage->assign('numFortunes', Fortune::getCount());

// le os cookies e passa para a página a ser carregada.
$cookedStyles = ':root {';
$colorCookies = cookedStyle::getArray(ID_ADM_PAG);
if ($colorCookies) 
{
    foreach ($colorCookies as $elementoColorido) {
        $cookedStyles .= $elementoColorido['root_var'] . ': ' . $elementoColorido['color'] . '; ';
    }
}
$cookedStyles .= '}';
$homepage->assign('cookedStyles', $cookedStyles);

// propriedades gerais da página
$homepage->assign('displayImagemTitulo', '1');

// obtém a página administrativa
$admPag = new pagina(ID_ADM_PAG);

$homepage->assign('classPagina', $admPag->classPagina);
$homepage->assign('tituloPaginaAlternativo', $lang['paginaEstatisticasTituloPagina']);
$homepage->assign('tituloTabelaAlternativo', $lang['paginaEstatisticasTituloTabela']);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->assign('displaySelectColor', 0);
$homepage->display('admin/estatisticas.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
