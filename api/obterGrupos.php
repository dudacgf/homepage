<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// verifica se passou a categoria
if (isset($requests['idCat'])) 
    $_idCategoria = $requests['idCat'];

// lê a categoria
$categoria = new Pagina\Categoria($_idCategoria);

// garante que vão aparecer todas as categorias.
$_REQUEST['gr'] = 'all';

// lê os grupos desta categoria
$categoria->lerElementos();
$descricoesGrupos[] = '';
foreach ($categoria->elementos as $grupo)
{
    $descricoesGrupos[] = $grupo->getArray();
}
array_shift($descricoesGrupos);
$homepage->assign('idCategoria', $_idCategoria);
$homepage->assign('gruposPresentes', $descricoesGrupos);
$homepage->assign('gruposAusentes', $categoria->lerNaoElementos());
$homepage->assign('tiposGrupos', Pagina\TiposGrupos::getArray());
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$grupos_html = $homepage->fetch('admin/grupos_div.tpl');
$homepage->assign('response', '{"status": "success", "message": "' . $global_hpDB->real_escape_string($grupos_html) . '"}');
$homepage->display('response.tpl');
?>
