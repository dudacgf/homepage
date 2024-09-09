<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: max-age: 10' );
require_once('common.php');

use Shiresco\Homepage\Fortunes as Fortunes;
use Shiresco\Homepage\Pagina as Pagina;

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
if (isset($requests['gr']))
{
    $homepage->assign('gr', $requests['gr']);
}

$homepage->display('index.tpl');
?>

