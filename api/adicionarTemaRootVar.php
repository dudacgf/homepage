<?php
/* 
 * adicionarRootVarAPagina 
 * insere uma alteração de tema na página atual
 *
 * recebe, via $_REQUEST: idPagina, root_var e color
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;

if (!isset($_REQUEST['idPagina']) || !isset($_REQUEST['root_var']) || !isset($_REQUEST['color'])) 
	$homepage->assign('response', '{"status": "error", "message": "Faltou informação na chamada a adicionarRootVarPagina. Preciso: idPagina, root_var e color"}');
else {
	$idPagina = urldecode($_REQUEST['idPagina']);
	$root_var = urldecode($_REQUEST['root_var']);
	$color = urldecode($_REQUEST['color']);

    $rvp = new Temas\RootVarsPagina();
    try {
        $rv = Temas\VariaveisRoot::obterPorNome($root_var);
        if ($rvp->inserir($idPagina, $root_var, $color))
            $homepage->assign('response', '{"status": "success", "message": "Cor de [' . $rv['descricao'] . '] alterada para ' . $color . '"}');
        else
            $homepage->assign('response', '{"status": "error", "message": "Não foi possível alterar a cor de [' . $rv['descricao'] . ']"}');
    } catch (Exception) {
        try {
            if ($rvp->atualizar($idPagina, $root_var, $color))
                $homepage->assign('response', '{"status": "success", "message": "Cor de [' . $rv['descricao'] . '] alterada para ' . $color . '"}');
            else
                $homepage->assign('response', '{"status": "error", "message": "Não foi possível alterar a cor de [' . $rv['descricao'] . ']"}');
        } catch (Exception $e) {
            $homepage->assign('response', '{"status": "error", "message": "Erro ao alterar a cor de [' . $rv['descricao'] . ']: ' . $e->getMessage() . '"}');
        }
    }
}
$homepage->display('response.tpl');
?>
