<?php
//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Visita as Visita;
use Shiresco\Homepage\Fortunes as Fortunes;

// obtém as estatísticas na base e as repassa ao template
$homepage->assign('numPaginas', pagina::getCount());
$homepage->assign('numCategorias', categoria::getCount());
$homepage->assign('numGrupos', grupo::getCount());
$homepage->assign('numLinks', wLink::getCount());
$homepage->assign('numForms', wForm::getCount());
$homepage->assign('numSeparadores', wSeparador::getCount());
$homepage->assign('numImagens', wImagem::getCount());
$homepage->assign('numTemplates', wTemplate::getCount());
$homepage->assign('numFortunes', Fortunes\Fortune::getCount());

// estatísticas de visita
$homepage->assign('totalLinks7dias', Visita\Visita::totalLinks(7));
$homepage->assign('listaLinks7dias', Visita\Visita::lerContagem(7, 6));
$homepage->assign('totalLinks1mes', Visita\Visita::totalLinks(30));
$homepage->assign('listaLinks1mes', Visita\Visita::lerContagem(30, 6));

// verifica se há cookies de tema configurados para essa página
$RVPs = Temas\RootVarsPagina::getArray(ID_ADM_PAG);
if ($RVPs) {
    $rootVars = ':root {';
    foreach ($RVPs as $rvp) {
        $rootVars .= $rvp['root_var'] . ': ' . $rvp['color'] . '; ';
    }
    $rootVars .= '}';
    $homepage->assign('rootVars', $rootVars);
}

// propriedades gerais da página
$homepage->assign('displayImagemTitulo', '1');

// obtém a página administrativa
$pagina = new pagina(ID_ADM_PAG);

// obtém os items do menu
include($admin_path . 'ler_menu.php');

// elementos enviados ao template
$homepage->assign('classPagina', $pagina->classPagina);
$homepage->assign('tituloPaginaAlternativo', $lang['paginaEstatisticasTituloPagina']);
$homepage->assign('tituloTabelaAlternativo', $lang['paginaEstatisticasTituloTabela']);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->assign('displaySelectColor', 0);
$homepage->display('admin/index.tpl');
?>
