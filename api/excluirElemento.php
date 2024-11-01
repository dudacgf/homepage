<?php
/*
 *  excluirElemento.php 
 *  exclui um elemento de um grupo
 *  
 *  recebe - id do grupo e do elemento a ser excluído
 *  devolve - json response contendo status e mensagem informativa
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
include_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// se não tenho o id do elemento a excluir, retorna erro
if (isset($requests['idElm']) && $requests['idElm'] == '0')
    $homepage->assign('response', '{"status": "error", "message": "Não foi passado o elemento a excluir!"}');
else {
    $_idElm = $requests['idElm'];
    $elemento = new Pagina\ElementoFactory($_idElm);
    if (!$elemento) 
        $homepage->assign('response', '{"status": "error", "message": "Não encontrei o elemento de idElemento [' . $_idElm . ']!"}');

    Try {
        $descricaoElemento = $elemento->descricaoElemento();
        if ($elemento->excluir())
            $homepage->assign('response', '{"status": "success",
                                             "message": "Elemento [' . $global_hpDB->real_escape_string($descricaoElemento) . '] excluído!"}');
        else
            $homepage->assign('response', '{"status": "warning",
                  "message": "Não foi possível excluir o elemento [' . $global_hpDB->real_escape_string($descricaoElemento) . ']!"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao excluir o elemento: ' . $e->getMessage() . '!"}');
    }
}

$homepage->display('response.tpl');
?>
