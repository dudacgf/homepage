<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
// resetPagina
// - elimina todos os cookiestyles de uma página
//

require_once('../common.php');

// obtém a chave da página a partir da request url
if (!isset($_REQUEST['idPagina'])) 
	$homepage->assign('response', '{"status": "error", "message": "Faltou informação na chamada a addColorCookie. Preciso: idPagina"}');
else {
	$idPagina = urldecode($_REQUEST['idPagina']);

    $cookie = new cookedStyle();
    try {
        if ($cookie->restaurarPagina($idPagina))
            $homepage->assign('response', '{"status": "success", "message": "Cores da página restauradas"}');
        else
            $homepage->assign('response', '{"status": "error", "message": "Não foi possível restaurar as cores da página"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao restaurar as cores da página: ' . $e->getMessage() . '"}');
    }
}
$homepage->display('response.tpl');
?>
