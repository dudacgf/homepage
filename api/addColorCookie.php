<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
// add cookie 
// - cria um cookie de cor para a página atual
//

require_once('../common.php');

// obtém as chaves da página, do elementocolorido e do valorCor a partir da request url
if (!isset($_REQUEST['idPagina']) || !isset($_REQUEST['root_var']) || !isset($_REQUEST['color'])) 
	$homepage->assign('response', '{"status": "error", "message": "Faltou informação na chamada a addColorCookie. Preciso: idPagina, root_var e color"}');
else {
	$idPagina = urldecode($_REQUEST['idPagina']);
	$root_var = urldecode($_REQUEST['root_var']);
	$color = urldecode($_REQUEST['color']);

    $cookie = new cookedStyle();
    try {
        $el = elementoColorido::getElementoColorido($root_var);
        if ($cookie->inserirCookedStyle($idPagina, $root_var, $color))
            $homepage->assign('response', '{"status": "success", "message": "Cor de ' . $el['descricaoElemento'] . ' alterada para ' . $color . '"}');
        else
            $homepage->assign('response', '{"status": "error", "message": "Não foi possível alterar a cor de ' . $el['descricaoElemento'] . '"}');
    } catch (Exception) {
        try {
            if ($cookie->atualizarCookedStyle($idPagina, $root_var, $color))
                $homepage->assign('response', '{"status": "success", "message": "Cor de ' . $el['descricaoElemento'] . ' alterada para ' . $color . '"}');
            else
                $homepage->assign('response', '{"status": "error", "message": "Não foi possível alterar a cor de ' . $el['descricaoElemento'] . '"}');
        } catch (Exception $e) {
            $homepage->assign('response', '{"status": "error", "message": "Erro ao alterar a cor de ' . $el['descricaoElemento'] . ': ' . $e->getMessage() . '"}');
        }
    }
}
$homepage->display('response.tpl');
?>
