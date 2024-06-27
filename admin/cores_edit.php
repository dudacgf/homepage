<?php
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

// A página de exemplo de cores tem idPagina = 7
$_idPagina = 7;

// abro e inicializo minha página
$pagina = new pagina($_idPagina);
$homepage->assign('idPagina', $pagina->idPagina);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('classPagina', $pagina->classPagina);

// manda um biscoitinho da sorte para lá, para poder ver as cores.
$homepage->assign('fortuneCookie', 'NÃ£o tem nada aqui. passe para  pr&oacute;ima.<br /><b>-- autor anÃ´nimo</b>');
$homepage->assign('displayFortune', 1);

// indica a inclusão do form de cores
$homepage->assign('displaySelectColor', 1);

// lê os elementos coloridos e os pares de cores
$homepage->assign('elementosColoridos', elementoColorido::getArray());
$homepage->assign('paresCores', RGBColor::getArray());

// le os cookies e passa para a página a ser carregada.
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

$homepage->assign('descricoesCategorias', $descricoesCategorias);
$homepage->assign('descricoesGrupos', $descricoesGrupos);
$homepage->assign('includePATH', INCLUDE_PATH);

$homepage->display('admin/cores_edit.tpl');

//-- vim: set shiftwidth=4 tabstop=4: 

?>

