<?php
require_once('auth_force.php');
require_once('../common.php');
use Shiresco\Homepage\Temas as Temas;

// abro e inicializo minha página
$pagina = new pagina(ID_COR_PAG);
$homepage->assign('idPagina', $pagina->idPagina);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('classPagina', $pagina->classPagina);

// manda um biscoitinho da sorte para lá, para poder ver as cores.
$homepage->assign('fortune', array('textoFortune' => 'Não tem nada aqui. passe para a próxima.',
                                   'autorFortune' => 'autor anônimo'));
$homepage->assign('displayFortune', 1);

// indica a inclusão do form de cores
$homepage->assign('displaySelectColor', 1);

// lê os elementos coloridos e os pares de cores
$homepage->assign('variaveisRoot', Temas\VariaveisRoot::obterTodasDeTipo('color'));
$homepage->assign('pcPantone', Temas\PaletasdeCor::getArray('Pantone'));
$homepage->assign('pcMaterial', Temas\PaletasdeCor::getArray('Material'));

// verifica se há cookies de tema configurados para essa página
$RVPs = Temas\RootVarsPagina::getArray(ID_COR_PAG);
if ($RVPs) {
    $rootVars = ':root {';
    foreach ($RVPs as $rvp) {
        $rootVars .= $rvp['rootvar'] . ': ' . $rvp['cor'] . '; ';
    }
    $rootVars .= '}';
    $homepage->assign('rootVars', $rootVars);
}

// Pego a pagina de exemplo para ter todos os tipos de elemento.
include(HOMEPAGE_PATH . 'admin/criar_exemplo.php');

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

$homepage->assign('displayImagemTitulo', '1');

// adiciona o exemplo ao grupo dos que vão para a página.
$descricoesCategorias = array_merge($categoriaExemplo, $descricoesCategorias);

// obtém os items do menu
include($admin_path . 'ler_menu.php');

// le icones 
$svg_hue = file_get_contents($images_path . 'hue.svg');
$homepage->assign('svg_hue', $svg_hue);
$svg_google = file_get_contents($images_path . 'google.svg');
$homepage->assign('svg_google', $svg_google);
$svg_pantone = file_get_contents($images_path . 'pantone.svg');
$homepage->assign('svg_pantone', $svg_pantone);
$svg_palette = file_get_contents($images_path . 'palette.svg');
$homepage->assign('svg_palette', $svg_palette);

$homepage->assign('descricoesCategorias', $descricoesCategorias);
$homepage->assign('descricoesGrupos', $descricoesGrupos);
$homepage->assign('includePATH', INCLUDE_PATH);

$homepage->display('admin/cores_edit.tpl');

//-- vim: set shiftwidth=4 tabstop=4: 

?>

