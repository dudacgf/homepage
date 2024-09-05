<?php
//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Visita as Visita;
use Shiresco\Homepage\Fortunes as Fortunes;
use Shiresco\Homepage\Pagina as Pagina;

// obtém as estatísticas na base e as repassa ao template
$homepage->assign('numPaginas', Pagina\Pagina::getCount());
$homepage->assign('numCategorias', Pagina\Categoria::getCount());
$homepage->assign('numGrupos', Pagina\Grupo::getCount());
$homepage->assign('numLinks', Pagina\Link::getCount());
$homepage->assign('numForms', Pagina\Form::getCount());
$homepage->assign('numSeparadores', Pagina\Separador::getCount());
$homepage->assign('numImagens', Pagina\Imagem::getCount());
$homepage->assign('numTemplates', Pagina\Template::getCount());
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

// obtém a página administrativa
$pagina = new Pagina\Pagina(ID_ADM_PAG);

// obtém os items do menu
include($admin_path . 'ler_menu.php');

// elementos enviados ao template
$homepage->assign('classPagina', $pagina->classPagina);
$homepage->assign('tituloPaginaAlternativo', $lang['paginaEstatisticasTituloPagina']);
$homepage->assign('tituloTabelaAlternativo', $lang['paginaEstatisticasTituloTabela']);
$homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->assign('displaySelectColor', 0);
$homepage->display('admin/index.tpl');
?>
