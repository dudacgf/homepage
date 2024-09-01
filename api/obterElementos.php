<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

// verifica se passou grupo
if (isset($requests['idGrp']))
    $_idGrupo = $requests['idGrp'];

// lê o grupo
$grupo = new grupo($_idGrupo);
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
$homepage->assign('tiposElementos', tiposElementos::getArray());
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$elementos_html = $homepage->fetch('admin/elementos_div.tpl');
$homepage->assign('response', '{"status": "success", "message": "' . $global_hpDB->real_escape_string($elementos_html) . '"}');
$homepage->display('response.tpl');
?>
