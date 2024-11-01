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
$link = new Pagina\Link($requests['idElm']);
$link->descricaoLink = $requests['descricaoLink'];
$link->linkURL = $requests['linkURL'];
$link->dicaLink = $requests['dicaLink'];
$link->idGrupo = $requests['idGrp'];
$link->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
$link->urlElementoSSL = ( isset($requests['urlElementoSSL']) ) ? 1 : 0 ;
$link->urlElementoSVN = ( isset($requests['urlElementoSVN']) ) ? 1 : 0 ;
$link->targetLink = $requests['targetLink'];
try {
    if (!$link->atualizar())
        $homepage->assign('response', '{"status": "warning", "message": "Não foi possível atualizar o link [' . $global_hpDB->real_escape_string($link->descricaoLink) . ']!"}');
    else
        $homepage->assign('response', '{"status": "success", "message": "Link [' . $global_hpDB->real_escape_string($link->descricaoLink) . '] atualizado!"}');
} catch (Exception $e) {
    $homepage->assign('response', '{"status": "error", "message": "Erro ao atualizar elemento [' . $global_hpDB->real_escape_string($link->descricaoLink) . ']: ' . 
                                                                  $global_hpDB-real_escape_string($e->getMessage()) . '!"}');
}
$homepage->display('response.tpl');
?>
