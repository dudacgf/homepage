<?php
/*****
  crRss.php - salva um novo elemento-rss

  recebe: dados para o novo registro através do array $requests (um merge de $_GET + $_POST + $_REQUEST)
  devolve: json resposta dizendo resultado da operação (status) e uma mensagem informativa (message)

*****/
include_once('../common.php');

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp'])) {
	$_idGrupo = $requests['idGrp'];
    $grupo = new grupo($_idGrupo);
    $rssfeed = new wRssFeed(NULL);
    $rssfeed->idGrupo = $requests['idGrp'];
    $rssfeed->posGrupo = $grupo->numeroElementos() + 1;
    $rssfeed->rssURL = $requests['rssURL'];
    $rssfeed->rssItemNum = $requests['rssItemNum'];
    try {
        $_idElm = $rssfeed->inserir();
        if (!$_idElm)
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível criar o link \[' . $global_hpDB->real_escape_string($link->rssURL) . '\]!"}');
        else
            $homepage->assign('response', '{"status": "success", "message": "Rss [' . $global_hpDB->real_escape_string($link->rssURL) . '] criado!"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao criar elemento Rss: ' . $e->getMessage() . '!"}');
    }
} else
    $homepage->assign('response', '{"status": "error", "message": "não posso prosseguir sem um grupo selecionado!"}');

$homepage->display('response.tpl');
?>
