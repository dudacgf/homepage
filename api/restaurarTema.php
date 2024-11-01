<?php
/*
 * restauraPagina
 * elimina todas as alterações de tema da página atual
 *
 * recebe, via $_REQUEST: idTema
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;

// obtém a chave da página a partir da request url
if (!isset($_REQUEST['idTema'])) 
	$homepage->assign('response', '{"status": "error", "message": "Faltou informação na chamada a restauraPagina. Preciso: idTema"}');
else {
	$idTema = urldecode($_REQUEST['idTema']);

    try {
        if (Temas\TemaCSS::restaurarPagina($idTema))
            $homepage->assign('response', '{"status": "success", "message": "Cores do tema restauradas"}');
        else
            $homepage->assign('response', '{"status": "error", "message": "Não foi possível restaurar as cores do tema "}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao restaurar as cores do tema: ' . $e->getMessage() . '"}');
    }
}
$homepage->display('response.tpl');
?>
