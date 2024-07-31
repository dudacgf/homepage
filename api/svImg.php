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
$imagem = new wImagem($requests['idElm']);	
$imagem->idGrupo = $requests['idGrp'];
$imagem->descricaoImagem = $requests['descricaoImagem'];
$imagem->urlImagem = $requests['urlImagem'];
$imagem->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
Try {
    if (!$imagem->atualizar())
        $homepage->assign('response', '{"status": "warning", "message": "Não foi possível atualizar a imagem [' . $global_hpDB->real_escape_string($imagem->descricaoImagem) . ']!"}');
    else
        $homepage->assign('response', '{"status": "success", "message": "Imagem [' . $global_hpDB->real_escape_string($imagem->descricaoImagem) . '] atualizada!"}');
} catch (Exception $e) {
    $homepage->assign('response', '{"status": "error", "message": "Erro ao atualizar elemento [' . $global_hpDB->real_escape_string($imagem->descricaoImagem) . ']: ' . 
                                                                  $global_hpDB-real_escape_string($e->getMessage()) . '!"}');
}
$homepage->display('response.tpl');
?>
