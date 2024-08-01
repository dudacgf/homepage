<?php
include_once('../common.php');

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp']))
{
    $_idGrupo = $requests['idGrp'];
}
else
{
    throw new Exception("não posso prosseguir sem um grupo selecionado!");
}

// lê o grupo deste elemento
$grupo = new grupo($_idGrupo);
$homepage->assign('grupo', $grupo->getArray());

// obtém a página administrativa
$admPag = new pagina(ID_ADM_PAG);

// organiza o que vai passar para o template
$criarElemento = true;
$homepage->assign('elemento', array(
    'idGrupo' => $grupo->idGrupo,
    'descricaoSeparador' => '',
    'breakBefore' => $lang['hp_separadores_breakBefore']));
$template = 'admin/separador_edit.tpl';
$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaCriarSeparador']);
$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novoSeparador'] . ' ::');

// le os cookies e passa para a página a ser carregada.
$cookedStyles = '';
$colorCookies = cookedStyle::getArray(5);
if ($colorCookies) 
{
    foreach ($colorCookies as $selector => $colorCookie) {
        $cookedStyles .= implode("\n", $colorCookie) . "\n}\n";
    }
}
$homepage->assign('cookedStyles', $cookedStyles);

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('criarElemento', $criarElemento);
$homepage->assign('imagesPATH', $images_path);
$homepage->assign('classPagina', $admPag->classPagina);
$html_template = $homepage->fetch($template);
$homepage->assign('response', '{"status": "success", "message": "' . $global_hpDB->real_escape_string($html_template) . '"}');
$homepage->display('response.tpl');
?>
