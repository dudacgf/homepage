<?php
// del cookie 
// - elimina o cookiestyle de uma página
//

require_once('../common.php');

// obtém as chaves da página e da cor a partir da request url
if (!isset($_REQUEST['idPagina']) || !isset($_REQUEST['root_var'])) 
	$homepage->assign('response', '{"status": "error", "message": "Faltou informação na chamada a addColorCookie. Preciso: idPagina, root_var"}');
else {
	$idPagina = urldecode($_REQUEST['idPagina']);
	$root_var = urldecode($_REQUEST['root_var']);

    $cookie = new cookedStyle();
    try {
        $el = elementoColorido::getElementoColorido($root_var);
        if ($cookie->eliminarCookedStyle($idPagina, $root_var))
            $homepage->assign('response', '{"status": "success", "message": "Cor de ' . $el['descricaoElemento'] . ' restaurada"}');
        else
            $homepage->assign('response', '{"status": "error", "message": "Não foi possível restaurar a cor de ' . $el['descricaoElemento'] . '"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao restaurar a cor de ' . $el['descricaoElemento'] . ': ' . $e->getMessage() . '"}');
    }
}
$homepage->display('response.tpl');
?>
