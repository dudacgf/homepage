<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

// verifica se passou a página
if (isset($requests['id'])) 
    $_idPagina = $requests['id'];
if (isset($requests['idPagina'])) 
    $_idPagina = $requests['idPagina'];

// lê a página
$pagina = new pagina($_idPagina);
$homepage->assign('idPagina', $_idPagina);

// garante que vão aparecer todas as categorias.
$_REQUEST['gr'] = 'all';

// lê as categorias desta página
$pagina->lerElementos();
$descricoesCategorias[] = '';
foreach ($pagina->elementos as $categoria)
{
    $descricoesCategorias[] = $categoria->getArray();
}
array_shift($descricoesCategorias);
$homepage->assign('categoriasPresentes', $descricoesCategorias);
$homepage->assign('categoriasAusentes', $pagina->lerNaoElementos());
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$categorias_html = $homepage->fetch('admin/categorias_div.tpl');
$homepage->assign('response', '{"status": "success", "message": "' . $global_hpDB->real_escape_string($categorias_html) . '"}');
$homepage->display('response.tpl');
?>
