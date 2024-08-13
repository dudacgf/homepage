<?php
include_once('../common.php');

if (isset($requests['idPagina'])) {
    $admPag = new pagina(ID_ADM_PAG);

    $template = 'admin/save_estilo.tpl';
    $homepage->assign('displayImagemTitulo', '0');
    $homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSalvarEstilo']);
    $homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['tituloTabelaSalvarEstilo'] . ' ::');

    $homepage->assign('includePATH', INCLUDE_PATH);
    $homepage->assign('idPagina', $requests['idPagina']);
    $homepage->assign('imagesPATH', $images_path);
    $homepage->assign('classPagina', $admPag->classPagina);
    $html_template = $homepage->fetch($template);
    $homepage->assign('response', '{"status": "success", "message": "' . $global_hpDB->real_escape_string($html_template) . '"}');
} else
    $homepage->assign('response', '{"status": "error", "message": Preciso do id da página"}');
$homepage->display('response.tpl');
?>
