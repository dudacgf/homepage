<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
include_once('../common.php');

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
$grupo = new grupo($_idGrupo);
$homepage->assign('grupo', $grupo->getArray());

// obtém a página administrativa
$admPag = new pagina(ID_ADM_PAG);

// organiza o que vai passar para o template
$Template = new wTemplate($requests['idElm']);	
$Template->idGrupo = $requests['idGrp'];
$Template->descricaoTemplate = $requests['descricaoTemplate'];
$Template->nomeTemplate = $requests['nomeTemplate'];
$Template->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
Try {
    if (!$Template->atualizar())
        $homepage->assign('response', '{"status": "warning", "message": "Não foi possível atualizar o Template [' . $global_hpDB->real_escape_string($Template->descricaoTemplate) . ']!"}');
    else
        $homepage->assign('response', '{"status": "success", "message": "Template [' . $global_hpDB->real_escape_string($Template->descricaoTemplate) . '] atualizado!"}');
} catch (Exception $e) {
    $homepage->assign('response', '{"status": "error", "message": "Erro ao atualizar elemento [' . $global_hpDB->real_escape_string($Template->descricaoTemplate) . ']: ' . 
                                                                  $global_hpDB-real_escape_string($e->getMessage()) . '!"}');
}
$homepage->display('response.tpl');
?>
