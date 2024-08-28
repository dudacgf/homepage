<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
require_once('../common.php');

if (isset($requests['id']) and isset($requests['idCat'])) 
    try {
        $pagina = new pagina($requests['id']);
        $categoria = new categoria($requests['idCat']);
        if ($pagina->incluirElemento($_REQUEST['idCat']))
            $homepage->assign('response', '{"status": "success", "message": "Categoria [' . $categoria->descricaoCategoria . '] incluída na página"}');
        else
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível incluir a categoria [' . $categoria->descricaoCategoria . '] na página"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao incluir categoria [' . $categoria->descricaoCategoria . '] na página: ' . $e->getMessage() . '"}');
    }
else 
    $homepage->assign('response', '{"status": "error", "message": "Faltam informações na chamada a essa api: igPagina e idCat são necessários!"}');
$homepage->display('response.tpl');
?>

