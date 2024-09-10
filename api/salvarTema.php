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
    $_paresDeCores = json_decode($requests['paresDeCores']);

    try {
        $_tema = new Temas\Temas($_idTema);
        $_tema->nome = $_nomeTema;
        $_tema->comentario = $_comentarioTema;

        if ($_tema->atualizar()) {
            combina_cssfile_com_rootvars($_tema);
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

//
// combina as variáveis de cor no arquivo css do tema com as modificadas via tema_edit
// grava o resultado no arquivo css do tema
function combina_cssfile_com_rootvars($_tema) {
    $_temaPath = HOMEPAGE_PATH . 'temas/' . $_tema->nome . '.css';
        
    $file_contents = file_get_contents($_temaPath);

    // separa as linhas em um array
    $tema_linhas = explode(PHP_EOL, $file_contents);

    // remove todas as linhas que não contenham a palavra 'cor' 
    foreach ($tema_linhas as $linha) 
        if (str_contains($linha, '--cor')) {
            $pc = explode(':', trim($linha));
            $pares[$pc[0]] = $pc[1];
        }

    // lê as cores alteradas durante a edição do tema
    $trv = Temas\TemaRootVars::getArray($_tema->id);
    foreach ($trv as $linha) 
        $paresRV[$linha['rootvar']] = $linha['cor'] . ';';

    // combina os arrays, substituindo os pares originais pelos alterados via tema_edit 
    $pares = array_merge($pares, $paresRV);

    // prepara o conteúdo do arquivo tema
    $tema_file_contents = '/*----------------------------------------------------------' . PHP_EOL . PHP_EOL;
    $tema_file_contents .= '   ' . $_tema->nome . '.css' . PHP_EOL . PHP_EOL;
    $tema_file_contents .= '  (c) ecgf - 2006' . PHP_EOL . PHP_EOL;
    $tema_file_contents .= '  ver colorbase.css, que determina as cores da página' . PHP_EOL . PHP_EOL;
    $tema_file_contents .= '  Estilo ' . $_tema->nome . ' - ' . $_tema->comentario . PHP_EOL . PHP_EOL;
    $tema_file_contents .= '------------------------------------------------------------*/' . PHP_EOL;
    $tema_file_contents .= ':root {' . PHP_EOL;
    foreach ($pares as $rootvar => $cor)
        $tema_file_contents .= '    ' . $rootvar . ': ' . $cor . PHP_EOL;
    $tema_file_contents .= '}' . PHP_EOL;

    file_put_contents($_temaPath, $tema_file_contents);
}
?>
