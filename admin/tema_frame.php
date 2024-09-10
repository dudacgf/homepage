<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: max-age: 10' );
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Fortunes as Fortunes;
use Shiresco\Homepage\Pagina as Pagina;

// qual o tema que foi solicitado?
if (isset($requests['idTema']))
    $_idTema = $requests['idTema'];
else
    $_idTema = 1;

// carrega o tema
$tema = new Temas\Temas($_idTema);
$homepage->assign('tema', $tema);

// obtenho atributos da página de exibição do tema (admin/tema_frame.tpl)
$pagina = new Pagina\Pagina(ID_TEMA_PAG);
$homepage->assign('idTema', $_idTema);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('classPagina', $tema->nome);
$homepage->assign('displayImagemTitulo', $pagina->displayImagemTitulo);

// se esta página apresentar fortune, obtém uma...
if ($pagina->displayFortune != 0)
    $homepage->assign("fortune", array('autorFortune' => 'o administrador',
                         'textoFortune' => 'sem fortunes. uma pausa para seus próprios augúrios.'));

// dados para popular o form de cores
$homepage->assign('variaveisRoot', Temas\VariaveisRoot::obterTodasDeTipo('color'));
$homepage->assign('pcPantone', Temas\PaletasdeCor::getArray('Pantone'));
$homepage->assign('pcMaterial', Temas\PaletasdeCor::getArray('Material'));
$svg_hue = file_get_contents($images_path . 'hue.svg');
$homepage->assign('svg_hue', $svg_hue);
$svg_google = file_get_contents($images_path . 'google.svg');
$homepage->assign('svg_google', $svg_google);
$svg_pantone = file_get_contents($images_path . 'pantone.svg');
$homepage->assign('svg_pantone', $svg_pantone);
$svg_palette = file_get_contents($images_path . 'palette.svg');
$homepage->assign('svg_palette', $svg_palette);

// alterações de cor ainda não salvas
$temaRootVars = Temas\TemaRootVars::getArray($_idTema);
if ($temaRootVars) {
    $homepage->assign('AAtrv', $temaRootVars);
    $rv = ':root { ';
    foreach ($temaRootVars as $rootvar)
        $rv .= $rootvar['rootvar'] . ': ' . $rootvar['cor'] . ';';
    $rv .= '}';
    $homepage->assign('rootVars', $rv); 
} else
    $homepage->assign('rootVars', '');

// Leio as categorias da página e percorro-as, incluíndo-as no template
$pagina->lerElementos();
foreach ($pagina->elementos as $categ) {

    $descricoesCategorias[] = array('index' => $categ->posPagina, 'categoria' => $categ->descricaoCategoria);
    
    // Leio os grupos desta categoria e percorro-os, incluíndo-os no template
    $categ->lerElementos();
    unset($grupos);
    foreach ($categ->elementos as $grupo) 
    {

    	// Leio os elementos deste grupo e percorro-os, incluíndo-os no template
    	$grupo->lerElementos();
    	unset($elementos);
    	foreach($grupo->elementos as $elemento) 
    	{
    		$elementos[] = $elemento->getArray();
    	}		

    	$grupos[] = array(
    					'grupo' => $grupo->descricaoGrupo,
    					'idtipoGrupo' => $grupo->idTipoGrupo,
    					'elementos' => $elementos);

    }
    
    $descricoesGrupos[] = array(
    						'index' => $grupo->posCategoria, 
    						'idGrupo' => $grupo->idGrupo,
    						'grupos' => $grupos 
    						);

}
$homepage->assign('descricoesCategorias', (isset($descricoesCategorias) ? $descricoesCategorias: []));
$homepage->assign('descricoesGrupos', (isset($descricoesGrupos)? $descricoesGrupos: []));

// elementos enviados ao template
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('displayFortune', '1');
$homepage->display('admin/tema_frame.tpl');
?>

