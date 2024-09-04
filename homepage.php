<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: max-age: 10' );
require_once('common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Fortunes as Fortunes;
use Shiresco\Homepage\Pagina as Pagina;

// 
// verifica se houve pedido de upload...
if (isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != '') 
{
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
        prepararToast('success', "Arquivo $uploadfile carregado com sucesso");
    else 
        prepararToast('warning', "Erro ao carregar arquivo $uploadfile");
}

// instancia a página informada a partir do id e coloca o título na página...
if (isset($requests['idPagina']))
    $_idPagina = $requests['idPagina'];
elseif (isset($requests['id']))
    $_idPagina = $requests['id'];
else
    $_idPagina = 1;

// abro e inicializo minha página
$pagina = new Pagina\Pagina($_idPagina);
$homepage->assign('idPagina', $_idPagina);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('classPagina', $pagina->classPagina);
$homepage->assign('displayFortune', $pagina->displayFortune);
$homepage->assign('displayImagemTitulo', $pagina->displayImagemTitulo);
$homepage->assign('displaySelectColor', $pagina->displaySelectColor);

// se esta página apresentar fortune, obtém uma...
if ($pagina->displayFortune != 0)
    $homepage->assign("fortune", Fortunes\Fortune::obterFortune());

// exibe o form de seleção de cores se foi requisitado na url ou se a página está configurada para exibí-lo
if ( (isset($requests['selectcolor']) && $requests['selectcolor'] == 'sim') || ($pagina->displaySelectColor == 1)) {
    $homepage->assign('displaySelectColor', 1);
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
} else
    $homepage->assign('displaySelectColor', 0);

// verifica se há cookies de tema configurados para essa página
$RVPs = Temas\RootVarsPagina::getArray($_idPagina);
if ($RVPs) {
    $rootVars = ':root {';
    foreach ($RVPs as $rvp) {
        $rootVars .= $rvp['rootvar'] . ': ' . $rvp['cor'] . '; ';
    }
    $rootVars .= '}';
    $homepage->assign('rootVars', $rootVars);
}

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
$homepage->assign('descricoesCategorias', $descricoesCategorias);
$homepage->assign('descricoesGrupos', $descricoesGrupos);

// elementos enviados ao template
$homepage->assign('includePATH', INCLUDE_PATH);
if (isset($requests['gr']))
{
    $homepage->assign('gr', $requests['gr']);
}

$homepage->display('index.tpl');
?>

