<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Pagina as Pagina;

// Obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);
$homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
$homepage->assign('rootVars', '');
$homepage->assign('paginas', Pagina\Pagina::getPaginas());
$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarPagina']);
$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarPagina']);
$homepage->assign('temaPagina', $admPag->temaPagina);
$homepage->assign('displaySelectColor', 0);

// obtém os items do menu
include($admin_path . 'ler_menu.php');

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display('admin/page_select.tpl');
?>
