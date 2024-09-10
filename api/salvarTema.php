<?php
/*
 * salvarTema
 * insere ou atualiza informações sobre um tema no database e, se bem sucedido, 
 * grava arquivo com as rootvars do tema
 *
 * recebe:
 * nome e comentário sobre o tema, array de rootvars com as cores para cada variável
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
    $_paresDeCores = json_decode($requests['paresDeCores']);

    // le os cookies e monta o conteúdo to arquivo .css
    $rootVars = ':root {' . PHP_EOL;
    foreach ($_paresDeCores as $root_var => $cor)
        $rootVars .= '    --cor-' . $root_var . ': ' . $cor . '; ' . PHP_EOL;
    $rootVars .= '}' . PHP_EOL;
    
    try {
        $_tema = new Temas\Temas(null);
        $_tema->nome = $_nomeTema;
        $_tema->comentario = $_comentarioTema;

        if (Temas\Temas::temaExiste($_nomeTema)) 
            $result = $_tema->atualizar();
        else 
            $result = $_tema->inserir();

        if ($result) {
            $_temaPath = HOMEPAGE_PATH . 'temas/' . $_nomeTema . '.css';
            file_put_contents($_temaPath, $rootVars);
            $homepage->assign('response', '{"status": "success", "message": "Tema [' . $global_hpDB->real_escape_string($_nomeTema) . '] salvo."}');
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
