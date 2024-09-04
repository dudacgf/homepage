<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

if (isset($requests['id']) and isset($requests['idCat'])) 
    try {
        $pagina = new Pagina\Pagina($requests['id']);
        $categoria = new Pagina\Categoria($requests['idCat']);
        if ($pagina->deslocarElementoParaCima($_REQUEST['idCat']))
             $homepage->assign('response', '{"status": "success", "message": "Categoria [' . $categoria->descricaoCategoria . '] deslocada para cima"}');
        else
             $homepage->assign('response', '{"status": "warning", "message": "Não foi possível deslocar a categoria [' . $categoria->descricaoCategoria . '] para cima"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao deslocar categoria [' . $categoria->descricaoCategoria . '] para cima: ' . $e->getMessage() . '"}');
    }
else 
    $homepage->assign('response', '{"status": "error", "message": "Faltam informações na chamada a essa api: igPagina e idCat são necessários!"}');
$homepage->display('response.tpl');
?>
