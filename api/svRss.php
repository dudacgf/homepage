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
$rssfeed = new wRssFeed($_idElm);	
$rssfeed->idGrupo = $requests['idGrp'];
$rssfeed->rssURL = $requests['rssURL'];
$rssfeed->rssItemNum = $requests['rssItemNum'];
Try {
    if (!$rssfeed->atualizar())
        $homepage->assign('response', '{"status": "warning", "message": "Não foi possível atualizar o rssfeed [' . $global_hpDB->real_escape_string($rssfeed->rssURL) . ']!"}');
    else
        $homepage->assign('response', '{"status": "success", "message": "RssFeed [' . $global_hpDB->real_escape_string($rssfeed->rssURL) . '] atualizado!"}');
} catch (Exception $e) {
    $homepage->assign('response', '{"status": "error", "message": "Erro ao atualizar elemento [' . $global_hpDB->real_escape_string($link->RssURL) . ']: ' . 
                                                                  $global_hpDB-real_escape_string($e->getMessage()) . '!"}');
}
?>
