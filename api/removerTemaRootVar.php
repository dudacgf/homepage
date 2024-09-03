<?php
/* 
 * removeTemaRootVar
 * remove uma alteração de tema na página atual
 *
 * recebe, via $_REQUEST, idPagina e root_var
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;

// obtém as chaves da página e da cor a partir da request url
if (!isset($_REQUEST['idPagina']) || !isset($_REQUEST['root_var'])) 
	$homepage->assign('response', '{"status": "error", "message": "Faltou informação na chamada a addColorCookie. Preciso: idPagina, root_var"}');
else {
	$idPagina = urldecode($_REQUEST['idPagina']);
	$root_var = urldecode($_REQUEST['root_var']);

    $rvp = new Temas\RootVarsPagina();
    try {
        $rv = Temas\VariaveisRoot::obterPorNome($root_var);
        if ($rvp->eliminar($idPagina, $root_var))
            $homepage->assign('response', '{"status": "success", "message": "Cor de [' . $rv['descricao'] . '] restaurada"}');
        else
            $homepage->assign('response', '{"status": "error", "message": "Não foi possível restaurar a cor de [' . $rv['descricao'] . ']"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao restaurar a cor de [' . $rv['descricao'] . ']: ' . $e->getMessage() . '"}');
    }
}
$homepage->display('response.tpl');
?>
