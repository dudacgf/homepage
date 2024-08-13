<?php
include_once('../common.php');

if (isset($requests['idCat']) and isset($requests['idGrp']))
    try {
        $categoria = new categoria($requests['idCat']);
        $grupo = new grupo($requests['idGrp']);
        if ($categoria->incluirElemento($requests['idGrp']))
            $homepage->assign('response', '{"status": "success", "message": "Grupo [' . $grupo->descricaoGrupo . '] incluído"}');
        else
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível incluir o grupo [' . $grupo->descricaoGrupo . ']"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao incluir o grupo [' . $grupo->descricaoGrupo . ']"}');
    }
else
    $homepage->assign('response', '{"status": "error", "message": "Faltam informações na chamada a essa api: idCat e idGrp são necessários!"}');
$homepage->display('response.tpl');
?>