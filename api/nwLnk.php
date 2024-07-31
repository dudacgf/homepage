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

// obtém a página administrativa para poder usar classPagina mais tarde
$admPag = new pagina(ID_ADM_PAG);

// organiza o que vai passar para o template
$criarElemento = true;
$homepage->assign('elemento', array(
    'idGrupo' => $grupo->idGrupo,
    'descricaoLink' => $lang['hp_links_descricaoLink'],
    'linkURL' => $lang['hp_links_linkURL'],
    'dicaLink' => $lang['hp_links_dicaLink'],
    'localLink' => 0,
    'urlElementoSSL' => 0,
    'urlElementoSVN' => 0,
    'targetLink' => ''));
$template = 'admin/link_edit.tpl';
$homepage->assign('displayImagemTitulo', '0');
$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarLink']);
$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novoLink'] . ' ::');

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
