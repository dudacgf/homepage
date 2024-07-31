<?php
/*****
  crImg.php - salva um novo elemento-imagem

  recebe: dados para o novo registro através do array $requests (um merge de $_GET + $_POST + $_REQUEST)
  devolve: json resposta dizendo resultado da operação (status) e uma mensagem informativa (message)

*****/
include_once('../common.php');

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp'])) {
	$_idGrupo = $requests['idGrp'];
    $grupo = new grupo($_idGrupo);

    // copia as informações para um novo elemento-imagem
    $imagem = new wImagem(NULL);
    $imagem->idGrupo = $requests['idGrp'];
    $imagem->posGrupo = $grupo->numeroElementos() + 1;
    $imagem->descricaoImagem = $requests['descricaoImagem'];
    $imagem->urlImagem = $requests['urlImagem'];
    $imagem->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
    try {
        $_idElm = $imagem->inserir();
        if (!$_idElm)
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível criar a imagem \[' . $global_hpDB->real_escape_string($imagem->descricaoImagem) . '\]!"}');
        else
            $homepage->assign('response', '{"status": "success", "message": "Imagem [' . $global_hpDB->real_escape_string($imagem->descricaoImagem) . '] criada!"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao criar elemento Imagem: ' . $e->getMessage() . '!"}');
    }
} else {
    $homepage->assign('response', '{"status": "error", "message": "não posso prosseguir sem um grupo selecionado!"}');
}

$homepage->display('response.tpl');
?>
