<?php
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
$form = new wForm($requests['idElm']);
$form->idGrupo = $requests['idGrp'];
$form->nomeForm = $requests['nomeForm'];
$form->acao = $requests['acao'];
$form->tamanhoCampo = $requests['tamanhoCampo'];
$form->nomeCampo = $requests['nomeCampo'];
$form->descricaoForm = $requests['descricaoForm'];
Try {
    if (!$form->atualizar())
        $homepage->assign('response', '{"status": "warning", "message": "Não foi possível atualizar o form [' . $global_hpDB->real_escape_string($form->descricaoForm) . '!]"}');
    else
        $homepage->assign('response', '{"status": "success", "message": "Form [' . $global_hpDB->real_escape_string($form->descricaoForm) . '] atualizado!"}');
} catch (Exception $e) {
    $homepage->assign('response', '{"status": "error", "message": "Erro ao atualizar elemento [' . $global_hpDB->real_escape_string($form->descricaoForm) . ']: ' . 
                                                                  $global_hpDB-real_escape_string($e->getMessage()) . '!"}');
}
$homepage->display('response.tpl');
?>