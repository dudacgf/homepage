<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);
$homepage->assign('rootVars', '');
$homepage->assign('displayImagemTitulo', '1');
$homepage->assign('idPagina', 0);
$homepage->assign('categorias', Pagina\Categoria::getCategorias());
$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarCategoria']);
$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarCategoria']);
$homepage->assign('classPagina', $admPag->classPagina);
$homepage->assign('displaySelectColor', 0);

// obtém os items do menu
include($admin_path . 'ler_menu.php');

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display('admin/categoria_select.tpl');
?>
