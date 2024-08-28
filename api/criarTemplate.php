<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
/*****
  criarTemplate.php - salva um novo elemento-template

  recebe: dados para o novo registro através do array $requests (um merge de $_GET + $_POST + $_REQUEST)
  devolve: json resposta dizendo resultado da operação (status) e uma mensagem informativa (message)

*****/
include_once('../common.php');

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp'])) {
	$_idGrupo = $requests['idGrp'];
    $grupo = new grupo($_idGrupo);

    // copia as informações para um novo elemento-template
    $Template = new wTemplate(NULL);
    $Template->idGrupo = $requests['idGrp'];
    $Template->posGrupo = $grupo->numeroElementos() + 1;
    $Template->descricaoTemplate = $requests['descricaoTemplate'];
    $Template->nomeTemplate = $requests['nomeTemplate'];
    try {
        $_idElm = $Template->inserir();
        if (!$_idElm)
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível criar o template \[' . $global_hpDB->real_escape_string($Template->descricaoTemplate) . '\]!"}');
        else
            $homepage->assign('response', '{"status": "success", "message": "Template [' . $global_hpDB->real_escape_string($Template->descricaoTemplate) . '] criado!"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao criar elemento Template: ' . $e->getMessage() . '!"}');
    }
} else
    $homepage->assign('response', '{"status": "error", "message": "não posso prosseguir sem um grupo selecionado!"}');

$homepage->display('response.tpl');
?>
