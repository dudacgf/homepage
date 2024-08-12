<?php
require_once('../common.php');

if (isset($requests['id']) and isset($requests['idCat'])) 
    try {
        $pagina = new pagina($requests['id']);
        $categoria = new categoria($requests['idCat']);
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
