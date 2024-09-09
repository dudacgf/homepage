<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Pagina as Pagina;

// Obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);
$homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
$homepage->assign('rootVars', '');
$homepage->assign('temas', Temas\Temas::getArray());
$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarTema']);
$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarTema']);
$homepage->assign('classPagina', $admPag->classPagina);
$homepage->assign('displaySelectColor', 0);

// obtém os items do menu
include($admin_path . 'ler_menu.php');

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display('admin/tema_select.tpl');
?>
