<?php
/*
 * salvarTema
 * atualiza informações sobre um tema no database e, se bem sucedido, grava 
 * arquivo com as rootvars do tema combinadas com as modificações executadas
 * via tema_edit
 *
 * recebe:
 * id, nome e comentário do tema
 *
 * retorna:
 * uma mensagem contendo o resultado da operação
 * grava o novo arquivo de tema com as modificações 
 *
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;

// verifica se foram passadas as informações necessárias
if (isset($requests['idTema']) and isset($requests['nomeTema']) and isset($requests['comentarioTema'])) {
	$_idTema = $requests['idTema'];
    $_nomeTema = $requests['nomeTema'];
    $_comentarioTema = $requests['comentarioTema'];

    try {
        $_tema = new Temas\Temas($_idTema);
        $_tema->nome = $_nomeTema;
        $_tema->comentario = $_comentarioTema;

        if ($_tema->atualizar()) {
            if (Temas\TemaCSS::salvar($_idTema)) 
                $homepage->assign('response', '{"status": "success", "message": "Tema [' . $global_hpDB->real_escape_string($_nomeTema) . '] salvo."}');
            else
                $homepage->assign('response', '{"status": "success", "message": "Erro ao salvar tema [' . $global_hpDB->real_escape_string($_nomeTema) . ']"}');
        } else
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível salvar o tema"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro: ' . $e->getMessage() . '"}');
    }
}
else
	$homepage->assign('response', '{"status": "error", "message": "Faltam informações para salvar o tema. Preciso de idTema, nomeTema e comentarioTema"}');

$homepage->display('response.tpl');

?>
