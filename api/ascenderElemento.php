<?php
/**
 * ascenderElemento.php 
 * desloca um elemento de um grupo uma posição para cima
 * 
 * recebe - id do grupo e do elemento a ser deslocado
 * 
 * devolve - json response contendo status e mensagem informativa
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('auth_force.php');
require_once('../common.php');

Use Shiresco\Homepage\Pagina as Pagina;

// verifica se passou grupo
if (isset($requests['idGrp']) and isset($requests['idElm'])) {
    Try {
        $grupo = new Pagina\Grupo($requests['idGrp']);
        $grupo->deslocarElementoParaCima($requests['idElm']);
        $elemento = $grupo->elementoDeCodigo($requests['idElm']);
        $homepage->assign('response', '{"status": "success", "message": "Elemento [' . $global_hpDB->real_escape_string($elemento->descricaoElemento) . '] deslocado para cima."}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao deslocar elemento: ' . $global_hpDB->real_escape_string($e->getMessage() . '"}'));
    }
} else
    $homepage->assign('response', '{"status": "error", "message": "Faltam informações na chamada a essa api: idGrp e idElm são necessários!"}');

$homepage->display('response.tpl');
?>
