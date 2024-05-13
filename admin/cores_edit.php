<?php
	

// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
if (!defined('RELATIVE_PATH'))
{
	define('RELATIVE_PATH', './../');
}
define('HOMEPAGE_PATH', './../');
include_once(RELATIVE_PATH . 'common.php');

// A p�gina de exemplo de cores tem idPagina = 7
$_idPagina = 7;

// abro e inicializo minha p�gina
$pagina = new pagina($_idPagina);
$homepage->assign('idPagina', $pagina->idPagina);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('classPagina', $pagina->classPagina);

// manda um biscoitinho da sorte para l�, para poder ver as cores.
$homepage->assign('fortuneCookie', 'Não tem nada aqui. passe para  pr&oacute;ima.<br /><b>-- autor anônimo</b>');
$homepage->assign('displayFortune', 1);

// indica a inclus�o do form de cores
$homepage->assign('displaySelectColor', 1);

// l� os elementos coloridos e os pares de cores
$homepage->assign('elementosColoridos', elementoColorido::getArray());
$homepage->assign('paresCores', RGBColor::getArray());

// le os cookies e passa para a p�gina a ser carregada.
$cookedStyles = '';
$colorCookies = cookedStyle::getArray($_idPagina);
if ($colorCookies) 
{
	foreach ($colorCookies as $selector => $colorCookie) {
		$cookedStyles .= implode("\n", $colorCookie) . "\n}\n";
	}
}
$homepage->assign('cookedStyles', $cookedStyles);

// Pego a pagina de exemplo para ter todos os tipos de elemento.
include(RELATIVE_PATH . 'admin/criar_exemplo.php');

// Leio as categorias da p�gina e percorro-as, inclu�ndo-as no template
$pagina->lerElementos();
foreach ($pagina->elementos as $categ) {

	$descricoesCategorias[] = array('index' => $categ->posPagina, 'categoria' => $categ->descricaoCategoria);
	
	// Leio os grupos desta categoria e percorro-os, inclu�ndo-os no template
	$categ->lerElementos();
	unset($grupos);
	foreach ($categ->elementos as $grupo) 
	{

		// Leio os elementos deste grupo e percorro-os, inclu�ndo-os no template
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

// adiciona o exemplo ao grupo dos que v�o para a p�gina.
$descricoesCategorias = array_merge($categoriaExemplo, $descricoesCategorias);

$homepage->assign('descricoesCategorias', $descricoesCategorias);
$homepage->assign('descricoesGrupos', $descricoesGrupos);
$homepage->assign('relativePATH', RELATIVE_PATH);

$homepage->display('admin/cores_edit.tpl');

//-- vim: set shiftwidth=4 tabstop=4: 

?>

