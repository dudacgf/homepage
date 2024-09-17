<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

    
// obtém os items do menu
include($admin_path . 'ler_menu.php');

// obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);
$homepage->assign('temaPagina', $admPag->temaPagina);

$homepage->assign('grupos', Pagina\Grupo::getGrupos());
$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarGrupo']);
$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarGrupo']);
$homepage->assign('rootVars', '');
$homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display('admin/grupo_select.tpl');
?>
