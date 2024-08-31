<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
// addVisita
// - recebe um idElemento e grava uma nova visita
//
// best effort. não verifico status nem devolvo nada
//
require_once('../common.php');

openlog("homepage", LOG_PID, LOG_LOCAL0);

// verifica se recebeu o idElemento
if (!isset($_REQUEST['idElm'])) 
	//$homepage->assign('response', '{"status": "error", "message": "Faltou informação na chamada a addVisita. Preciso: idElm"}');
	throw new Exception("Faltou informação na chamada a addVisita. Preciso: idElm");
else {
    $visita = new Visita();
    try {
        $visita->idElemento = $requests['idElm'];
        $_lastId = $visita->inserir();
        syslog(LOG_INFO, "inserida visita a " . $requests['idElm'] . " com idVisita: $_lastId");

    } catch (Exception $e) {
	    throw new Exception("Erro ao inserir visita: $e->getMessage()");
    }
}

closelog();

?>
