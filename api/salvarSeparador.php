<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
include_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp']))
{
	$_idGrupo = $requests['idGrp'];
}
else
{
	throw new Exception("não posso prosseguir sem um grupo selecionado!");
}

// lê o grupo deste elemento
$grupo = new Pagina\Grupo($_idGrupo);
$homepage->assign('grupo', $grupo->getArray());

// obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);

// organiza o que vai passar para o template
$separador = new Pagina\Separador($requests['idElm']);	
$separador->idGrupo = $requests['idGrp'];
$separador->descricaoSeparador = $requests['descricaoSeparador'];
$separador->breakBefore = ( isset($requests['breakBefore']) ) ? 1 : 0 ;
Try {
    if (!$separador->atualizar())
        $homepage->assign('response', '{"status": "warning", "message": "Não foi possível atualizar o separador [' . $global_hpDB->real_escape_string($separador->descricaoSeparador) . ']!"}');
    else
        $homepage->assign('response', '{"status": "success", "message": "Separador [' . $global_hpDB->real_escape_string($separador->descricaoSeparador) . '] atualizado!"}');
} catch (Exception $e) {
    $homepage->assign('response', '{"status": "error", "message": "Erro ao atualizar elemento [' . $global_hpDB->real_escape_string($separador->descricaoSeparador) . ']: ' . 
                                                                  $global_hpDB-real_escape_string($e->getMessage()) . '!"}');
}
$homepage->display('response.tpl');
?>
