<?php
/*
 * restauraPagina
 * elimina todas as alterações de tema da página atual
 *
 * recebe, via $_REQUEST: idPagina
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;

// obtém a chave da página a partir da request url
if (!isset($_REQUEST['idPagina'])) 
	$homepage->assign('response', '{"status": "error", "message": "Faltou informação na chamada a restauraPagina. Preciso: idPagina"}');
else {
	$idPagina = urldecode($_REQUEST['idPagina']);

    $rvp = new Temas\RootVarsPagina();
    try {
        if ($rvp->restaurarPagina($idPagina))
            $homepage->assign('response', '{"status": "success", "message": "Cores da página restauradas"}');
        else
            $homepage->assign('response', '{"status": "error", "message": "Não foi possível restaurar as cores da página"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao restaurar as cores da página: ' . $e->getMessage() . '"}');
    }
}
$homepage->display('response.tpl');
?>
