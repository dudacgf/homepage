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
$i = 1;
foreach ($grupo->elementos as $elemento)
{
    $tempElemento = new elementoFactory($elemento->idElemento);
    $tempElemento->setPosGrupo($i);
    $tempElemento->atualizar();
    $i++;
}
$homepage->assign('response', '{"status": "success", "message": "Posição dos elementos do grupo [' . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . '] redefinidas."}');
$homepage->display('response.tpl');
?>
