<?php
/**
 * criarSeparador.php
 * salva um novo elemento-separador
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

    // copia as informações para um novo elemento-separador
    $separador = new Pagina\Separador(NULL);
    $separador->idGrupo = $requests['idGrp'];
    $separador->posGrupo = $grupo->numeroElementos() + 1;
    $separador->descricaoSeparador = $requests['descricaoSeparador'];
    $separador->breakBefore = ( isset($requests['breakBefore']) ) ? 1 : 0 ;
    try {
        $_idElm = $separador->inserir();
        if (!$_idElm)
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível criar o separador \[' . $global_hpDB->real_escape_string($separador->descricaoSeparador) . '\]!"}');
        else
            $homepage->assign('response', '{"status": "success", "message": "Separador [' . $global_hpDB->real_escape_string($separador->descricaoSeparador) . '] criado!"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao criar elemento separador: ' . $e->getMessage() . '!"}');
    }
} else
    $homepage->assign('response', '{"status": "error", "message": "não posso prosseguir sem um grupo selecionado!"}');

$homepage->display('response.tpl');
?>
