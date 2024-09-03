<?php
/*
 * existeTema - verifica se um determinado tema já está presente no database
 *
 * recebe:
 * o nome do tema
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
if (!isset($requests['nomeTema'])) 
    $homepage->assign('response', '{"status": "warning", "message": "Preciso do nome do tema a ser salvo para verificar se ele existe"}');
else {
    $nomeTema = $requests['nomeTema'];
    try {
        if (Temas\Temas::temaExiste($nomeTema))
            $homepage->assign('response', '{"status": "info", "message": "existente"}');
        else
            $homepage->assign('response', '{"status": "info", "message": "inexistente"}');  
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "ERRO: "' . $global_hpDB->real_escape_string($e->getMessage()) . '"}');
    }
}
$homepage->display('response.tpl');
?>
