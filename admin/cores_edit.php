<?php
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

// abro e inicializo minha página
$pagina = new pagina(ID_COR_PAG);
$homepage->assign('idPagina', $pagina->idPagina);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('classPagina', $pagina->classPagina);

// manda um biscoitinho da sorte para lá, para poder ver as cores.
$homepage->assign('fortuneCookie', 'Não tem nada aqui. passe para a próxima.<br /><b>-- autor anônimo</b>');
$homepage->assign('displayFortune', 1);

// indica a inclusão do form de cores
$homepage->assign('displaySelectColor', 1);

// lê os elementos coloridos e os pares de cores
$homepage->assign('elementosColoridos', elementoColorido::getArray());
$homepage->assign('paresCores', RGBColor::getArray());

// verifica se há cookies de estilo configurados para essa página
$colorCookies = cookedStyle::getArray(ID_COR_PAG);
if ($colorCookies) 
{
    $cookedStyles = ':root {';
    foreach ($colorCookies as $elementoColorido) {
        $cookedStyles .= $elementoColorido['root_var'] . ': ' . $elementoColorido['color'] . '; ';
    }
    $cookedStyles .= '}';
    $homepage->assign('cookedStyles', $cookedStyles);
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

$homepage->assign('descricoesCategorias', $descricoesCategorias);
$homepage->assign('descricoesGrupos', $descricoesGrupos);
$homepage->assign('includePATH', INCLUDE_PATH);

$homepage->display('admin/cores_edit.tpl');

//-- vim: set shiftwidth=4 tabstop=4: 

?>

