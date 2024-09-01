<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
/*****
  criarLink.php - salva um novo elemento-link 

  recebe: dados para o novo registro através do array $requests (um merge de $_GET + $_POST + $_REQUEST)
  devolve: json resposta dizendo resultado da operação (status) e uma mensagem informativa (message)

*****/
require_once('auth_force.php');
include_once('../common.php');

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp'])) {
	$_idGrupo = $requests['idGrp'];
    $grupo = new grupo($_idGrupo);

    // copia as informações para um novo elemento-link
    $link = new wLink(NULL);
    $link->descricaoLink = $requests['descricaoLink'];
    $link->linkURL = $requests['linkURL'];
    $link->dicaLink = $requests['dicaLink'];
    $link->idGrupo = $requests['idGrp'];
    $link->posGrupo = $grupo->numeroElementos() + 1;
    $link->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
    $link->urlElementoSSL = ( isset($requests['urlElementoSSL']) ) ? 1 : 0 ;
    $link->urlElementoSVN = ( isset($requests['urlElementoSVN']) ) ? 1 : 0 ;
    $link->targetLink = $requests['targetLink'];
    try {
        $_idElm = $link->inserir();
        if (!$_idElm)
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível criar o link \[' . $global_hpDB->real_escape_string($link->descricaoLink) . '\]!"}');
        else
            $homepage->assign('response', '{"status": "success", "message": "Link [' . $global_hpDB->real_escape_string($link->descricaoLink) . '] criado!"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao criar elemento Link: ' . $e->getMessage() . '!"}');
    }
} else
    $homepage->assign('response', '{"status": "error", "message": "não posso prosseguir sem um grupo selecionado!"}');

$homepage->display('response.tpl');
?>
