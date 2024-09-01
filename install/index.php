<?php
require_once('common.php');
include($language_path . "lang_homepage_admin.php");

// obtém a página administrativa
$admPag = new pagina(ID_ADM_PAG);
$homepage->assign('classPagina', $admPag->classPagina);

// verifica em que passo estamos:
if (!isset($requests['passo']))
    $passo = 1;
else
    $passo = $requests['passo'];

// 
// Passos:
define('PASSO0_APRESENTACAO', 0);
define('PASSO1_CONFIGURACAO', 1);
define('PASSO2_GRAVARCONFIG', 2);
define('PASSO3_MOSTRARCONFIG', 3);
define('PASSO4_CARREGARDB', 4);
define('PASSO5_CONFIGPAGINA', 5);
switch($passo) {
    case 0: // 0 - apresentação
        $homepage->assign('tituloPaginaAlternativo', $lang['hp_installTPagApresetacao']);
        $homepage->assign('tituloTabelaAlternativo', $lang['hp_installTTblApresentacao'));
        $homepage->assign('textoBotao', $lang['hp_botaoProximo']);
        $homepage->assign('textoApresentacao', $lang['hp_installTextoApresentacao']);
        $homepage->assign('proximoPasso', PASSO1_CONFIGURACAO);
        $template = 'admin/install/passo_0_apresentacao.tpl';
    break;

    case 1: // 1 - obtem parametros de configuracao
        $homepage->assign('tituloPaginaAlternativo', $lang['hp_installTPagConfig']);
        $homepage->assign('tituloTabelaAlternativo', $lang['hp_installTTblConfig']);
        $homepage->assign('textoBotao', $lang['hp_botaoProximo']);
        $homepage->assign('proximoPasso', PASSO2_GRAVARCONFIG);
        $template = 'admin/install/passo_1_configuracao.tpl';
    break;

    case 2: // 2 - configura conexão ao db e cria os .htaccess
        try {
            assert(isset($requests['db_name'], $lang['hp_installErrorDBNameFaltando']));
            assert(isset($requests['db_host'], $lang['hp_installErrorDBHostFaltando']));
            assert(isset($requests['db_user'], $lang['hp_installErrorDBUserFaltando']));
            assert(isset($requests['db_pw'], $lang['hp_installErrorDBPwFaltando']));
            assert(isset($requests['admin_user'], $lang['hp_installErrorAdminUserFaltando']));
            assert(isset($requests['admin_pw'], $lang['hp_installErrorAdminPWFaltando']));
        } catch (Exception $e) {
            // usuário não preencheu todos os campos do form
            prepararToast('warning', $lang['hp_installErrorConfiguracao: ' + $e->getMessage());

            // volta ao mesmo passo
            $homepage->assign('tituloPaginaAlternativo', $lang['hp_installTPagConfig']);
            $homepage->assign('tituloTabelaAlternativo', $lang['hp_installTTblConfig']);
            $homepage->assign('textoBotao', $lang['hp_botaoProximo']);
            $homepage->assign('proximoPasso', PASSO2_GRAVARCONFIG);
            $template = 'admin/install/passo_1_configuracao.tpl';

            break;
        }

        // grava os arquivos
        include(HOMEPAGE_PATH . 'install/write_config_files.php');
        write_config_files();

        // conteúdo dos arquivos gravados
        $config_connection = file_get_contents(HOMEPAGE_PATH . 'configs/connections.xml');
        $homepage->assign('config_connection', $config_connection);
        $root_ht = file_get_contents(HOMEPAGE_PATH . '/.htaccess');
        $homepage->assign('root_ht', $root_ht);
        $admin_ht = file_get_contents(HOMEPAGE_PATH . '/admin/.htaccess');
        $homepage->assign('admin_ht', $admin_ht);
        $api_ht = file_get_contents(HOMEPAGE_PATH . '/api/.htaccess');
        $homepage->assign('api_ht', $api_ht);
        $connections_xml = file_get_contents(HOMEPAGE_PATH . '/configs/connections_xml');
        $homepage->assign('connections_xml', $connections_xml);

        // prepara o próximo passo
        $homepage->assign('tituloPaginaAlternativo', $lang['hp_installTPagShowConfig']);
        $homepage->assign('tituloTabelaAlternativo', $lang['hp_installTTblShowConfig']);
        $homepage->assign('textoBotao', $lang['hp_botaoProximo']);
        $homepage->assign('proximoPasso', PASSO3_MOSTRARCONFIG);
        $template = 'admin/install/passo_3_show_files.tpl';
    break;

    case 3: // 3 - exibe conteúdo dos arquivos criados
        $homepage->assign('tituloPaginaAlternativo', $lang['hp_installTPagDBLoad']);
        $homepage->assign('tituloTabelaAlternativo', $lang['hp_installTTblDBLoad'));
        $homepage->assign('textoBotao', $lang['hp_botaoProximo']);
        $homepage->assign('proximoPasso', PASSO4_CARREGARDB);
        $template = 'admin/install/passo_4_carregar_db.tpl';
    break;

    case 4: // 4 - carga inicial no db e obtem parametros da pagina default
        include(HOMEPAGE_PATH . 'install/database_load.php');
        $result = executa_db_carga_inicial();

        // prepara o próximo passo
        if ($result) {
            $homepage->assign('tituloPaginaAlternativo', $lang['hp_installTPagDBLoad']);
            $homepage->assign('tituloTabelaAlternativo', $lang['hp_installTTblDBLoad'));
            $homepage->assign('textoBotao', $lang['hp_botaoProximo']);
            $homepage->assign('proximoPasso', PASSO5_CONFIGPAGINA);
            $template = 'admin/install/passo_5_config_pagina.tpl';
        } else { // erro na carga do database. volta ao passo 1 (obtém configuração)
            $homepage->assign('tituloPaginaAlternativo', $lang['hp_installTPagConfig']);
            $homepage->assign('tituloTabelaAlternativo', $lang['hp_installTTblConfig']);
            $homepage->assign('textoBotao', $lang['hp_botaoProximo']);
            $homepage->assign('proximoPasso', PASSO2_GRAVARCONFIG);
            $template = 'admin/install/passo_1_configuracao.tpl';
        }
    break;

    case 5: // 5 - cria configuracao default, restringe acesso ao folder install e vai para a página default
        include(HOMEPAGE_PATH . 'install/write_config_files.php');
        write_default_config();
        write_install_restriction();

        $request['id'] = 1;
        $requests['gr'] = 'all';
        include(HOMEPAGE_PATH . 'homepage.php');
        exit;
    break;
}


// verifica se há cookies de estilo configurados para essa página
$colorCookies = cookedStyle::getArray($_idPagina);
if ($colorCookies) 
{
    $cookedStyles = ':root {';
    foreach ($colorCookies as $elementoColorido) {
        $cookedStyles .= $elementoColorido['root_var'] . ': ' . $elementoColorido['color'] . '; ';
    }
    $cookedStyles .= '}';
    $homepage->assign('cookedStyles', $cookedStyles);
}

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->display($template);
?>
