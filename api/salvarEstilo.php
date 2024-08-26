<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: max-age: 10' );
include_once('../common.php');

// verifica se foram passadas as informações necessárias
if (isset($requests['idPagina']) and isset($requests['nomeEstilo']) and isset($requests['comentarioEstilo'])) {
	$_idPagina = $requests['idPagina'];
    $_nomeEstilo = $requests['nomeEstilo'];
    $_comentarioEstilo = $requests['comentarioEstilo'];
    $_paresDeCores = json_decode($requests['paresDeCores']);

    // le os cookies e monta o conteúdo to arquivo .css
    $cookedStyles = ':root {' . PHP_EOL;
    foreach ($_paresDeCores as $root_var => $cor)
        $cookedStyles .= '    --theme-' . $root_var . ': ' . $cor . '; ' . PHP_EOL;
    $cookedStyles .= '}' . PHP_EOL;
    
    try {
        $_cssEstilo = new cssEstilos(null);
        $_cssEstilo->nomeEstilo = $_nomeEstilo;
        $_cssEstilo->comentarioEstilo = $_comentarioEstilo;
        if ($_cssEstilo->inserir()) {
            $_estiloPath = HOMEPAGE_PATH . 'estilos/' . $_nomeEstilo . '.css';
            file_put_contents($_estiloPath, $cookedStyles);
            $homepage->assign('response', '{"status": "success", "message": "Estilo [' . $global_hpDB->real_escape_string($_nomeEstilo) . '] criado."}');
        } else
            $homepage->assign('response', '{"status": "error", "message": "Não foi possível criar o novo estilo"}');
    } catch (Exception $e) {
        $homepage->assign('response', '{"status": "error", "message": "Erro: ' . $e->getMessage() . '"}');
    }
}
else
	$homepage->assign('response', '{"status": "error", "message": "Faltam informações para salvar o estilo. Preciso de idPagina, nomeEstilo e comentarioEstilo"}');

$homepage->display('response.tpl');
?>
