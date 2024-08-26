<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: max-age: 10' );
/*****
  criarForm.php - salva um novo elemento-form 

  recebe: dados para o novo registro através do array $requests (um merge de $_GET + $_POST + $_REQUEST)
  devolve: json resposta dizendo resultado da operação (status) e uma mensagem informativa (message)

*****/
include_once('../common.php');

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp'])) {
	$_idGrupo = $requests['idGrp'];
    $grupo = new grupo($_idGrupo);

    // copia as informações para um novo elemento-form
    $form = new wForm(NULL);
    $form->idGrupo = $requests['idGrp'];
    $form->posGrupo = $grupo->numeroElementos() + 1;
    $form->nomeForm = $requests['nomeForm'];
    $form->acao = $requests['acao'];
    $form->tamanhoCampo = $requests['tamanhoCampo'];
    $form->nomeCampo = $requests['nomeCampo'];
    $form->descricaoForm = $requests['descricaoForm'];
    try {
        $_idElm = $form->inserir();
        if (!$_idElm)
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível criar o form \[' . $global_hpDB->real_escape_string($form->descricaoForm) . '\]!"}');
        else
            $homepage->assign('response', '{"status": "success", "message": "Form [' . $global_hpDB->real_escape_string($form->descricaoForm) . '] criado!"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao criar elemento Form: ' . $e->getMessage() . '!"}');
    }
} else
    $homepage->assign('response', '{"status": "error", "message": "não posso prosseguir sem um grupo selecionado!"}');

$homepage->display('response.tpl');
?>
