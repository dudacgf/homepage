<?php
use Shiresco\Homepage\Pagina as Pagina;

require_once('../common.php');
$admPag = new Pagina\Pagina(ID_ADM_PAG);

$menu = $admPag->lerPagina();
$homepage->assign('menuCategorias', $menu['descricoesCategorias']);
$homepage->assign('menuGrupos', $menu['descricoesGrupos']);
?>
