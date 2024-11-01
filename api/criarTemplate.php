<?php
/**
 * criarTemplate.php
 * salva um novo elemento-template
 *
 * recebe: dados para o novo registro através do array $requests (um merge de $_GET + $_POST + $_REQUEST)
 * devolve: json resposta dizendo resultado da operação (status) e uma mensagem informativa (message)
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
require_once('auth_force.php');
include_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;
// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp'])) {
	$_idGrupo = $requests['idGrp'];
    $grupo = new Pagina\Grupo($_idGrupo);

    // copia as informações para um novo elemento-template
    $Template = new Pagina\Template(NULL);
    $Template->idGrupo = $requests['idGrp'];
    $Template->posGrupo = $grupo->numeroElementos() + 1;
    $Template->descricaoTemplate = $requests['descricaoTemplate'];
    $Template->nomeTemplate = $requests['nomeTemplate'];
    try {
        $_idElm = $Template->inserir();
        if (!$_idElm)
            $homepage->assign('response', '{"status": "warning", "message": "Não foi possível criar o template \[' . $global_hpDB->real_escape_string($Template->descricaoTemplate) . '\]!"}');
        else
            $homepage->assign('response', '{"status": "success", "message": "Template [' . $global_hpDB->real_escape_string($Template->descricaoTemplate) . '] criado!"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro ao criar elemento Template: ' . $e->getMessage() . '!"}');
    }
} else
    $homepage->assign('response', '{"status": "error", "message": "não posso prosseguir sem um grupo selecionado!"}');

$homepage->display('response.tpl');
?>
