<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
include_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

if (isset($requests['idCat']) and isset($requests['idGrp']))
    try {
        $categoria = new Pagina\Categoria($requests['idCat']);
        $grupo = new Pagina\Grupo($requests['idGrp']);
        if ($categoria->deslocarElementoParaBaixo($requests['idGrp']))
            $homepage->assign('response', '{"status": "success", "message": "Grupo [' . $grupo->descricaoGrupo . '] deslocado para baixo"}');
        else
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível deslocar o grupo [' . $grupo->descricaoGrupo . '] para baixo"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao deslocar grupo [' . $grupo->descricaoGrupo . '] para baixo"}');
    }
else
    $homepage->assign('response', '{"status": "error", "message": "Faltam informações na chamada a essa api: idCat e idGrp são necessários!"}');
$homepage->display('response.tpl');
?>
