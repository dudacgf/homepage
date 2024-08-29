<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
require_once('../common.php');

if (!isset($requests['nomeEstilo'])) 
    $homepage->assign('response', '{"status": "warning", "message": "Preciso do nome do estilo a ser salvo para verificar se ele existe"}');
else {
    $nomeEstilo = $requests['nomeEstilo'];
    try {
        if (cssEstilos::estiloExiste($nomeEstilo))
            $homepage->assign('response', '{"status": "info", "message": "existente"}');
        else
            $homepage->assign('response', '{"status": "info", "message": "inexistente"}');  
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "ERRO: "' . $global_hpDB->real_escape_string($e->getMessage()) . '"}');
    }
}
$homepage->display('response.tpl');
?>
