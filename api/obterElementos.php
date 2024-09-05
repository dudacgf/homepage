<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// verifica se passou grupo
if (isset($requests['idGrp']))
    $_idGrupo = $requests['idGrp'];

// lê o grupo
$grupo = new Pagina\Grupo($_idGrupo);
$homepage->assign('grupo', $grupo->getArray());

// lê os elementos deste grupo
$grupo->lerElementos();
$elementos[] = '';
foreach ($grupo->elementos as $elemento)
{
    $elementos[] = $elemento->getArray();
}
array_shift($elementos);
$homepage->assign('elementos', $elementos);
$homepage->assign('tiposElementos', Pagina\TiposElementos::getArray());
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$template = 'admin/elementos_div.tpl';
$html_template = base64_encode($homepage->fetch($template));
$homepage->assign('response', '{"status": "success", "message": "' . $html_template . '"}');
$homepage->display('response.tpl');
?>
