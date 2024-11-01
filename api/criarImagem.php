<?php
/**
 * criarImagem.php 
 * salva um novo elemento-imagem
 * 
 * recebe: dados para o novo registro através do array $requests (um merge de $_GET + $_POST + $_REQUEST)
 * devolve: json resposta dizendo resultado da operação (status) e uma mensagem informativa (message)
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('auth_force.php');
include_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp'])) {
	$_idGrupo = $requests['idGrp'];
    $grupo = new Pagina\Grupo($_idGrupo);

    // copia as informações para um novo elemento-imagem
    $imagem = new Pagina\Imagem(NULL);
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
