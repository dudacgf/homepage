<?php
/**
 * adicionaVisita
 * recebe um idElemento e grava uma nova visita
 *
 * best effort. não verifico status nem devolvo nada
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

use Shiresco\Homepage\Visita as Visita;

// verifica se recebeu o idElemento
if (!isset($_REQUEST['idElm'])) 
	throw new Exception("Faltou informação na chamada a addVisita. Preciso: idElm");
else {
    $visita = new Visita\Visita();
    try {
        $visita->idElemento = $requests['idElm'];
        $_lastId = $visita->inserir();
    } catch (Exception $e) {
	    throw new Exception("Erro ao inserir visita: $e->getMessage()");
    }
}
?>
