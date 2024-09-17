<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;
use Shiresco\Homepage\Fortunes as Fortunes;

// Obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);

// obtém os items do menu
include($admin_path . 'ler_menu.php');

$homepage->assign('displayImagemTitulo', '1');
$homepage->assign('tituloPagina', ':: Arquivos');
$homepage->assign('tituloTabela', ' :: Carregar Arquivos');
$homepage->assign('temaPagina', $admPag->temaPagina);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('displaySelectColor', 0);

// uma pequena estatística do numero de fortunes na base
$nFortunes = Fortunes\Fortunes::getCount();

// exibe um novo form para carga de um arquivo
$homepage->assign('nFortunes', $nFortunes);
$homepage->display('admin/arquivos_upload.tpl');
?>    
