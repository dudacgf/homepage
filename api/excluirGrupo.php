<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: max-age: 10' );
include_once('../common.php');

if (isset($requests['idCat']) and isset($requests['idGrp']))
    try {
        $categoria = new categoria($requests['idCat']);
        $grupo = new grupo($requests['idGrp']);
        if ($categoria->excluirElemento($requests['idGrp']))
            $homepage->assign('response', '{"status": "success", "message": "Grupo [' . $grupo->descricaoGrupo . '] excluido"}');
        else
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível excluir o grupo [' . $grupo->descricaoGrupo . ']"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao excluir o grupo [' . $grupo->descricaoGrupo . ']"}');
    }
else
    $homepage->assign('response', '{"status": "error", "message": "Faltam informações na chamada a essa api: idCat e idGrp são necessários!"}');
$homepage->display('response.tpl');
?>
