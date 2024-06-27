<?php
//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

// classes espec�ficas da homepage
include_once($include_path . 'class_homepage.php');

// este flag eu vou usar mais tarde (em page_edit_body.tpl para configurar a action do formul�rio).
$criarPagina = false;

// garante que v�o aparecer todas as categorias.
$_REQUEST['gr'] = 'all';

// se n�o foi passado nenhuma p�gina, entre em modo de sele��o de p�gina
if (isset($requests['id'])) 
{
	$_idPagina = $requests['id'];
}

//
// se tem alguma coisa estranha, cai no default (slPag)
if ( !isset($requests['mode']) || (isset($_idPagina) && $_idPagina == '') )
{
	$requests['mode'] = 'slPag';
}

switch ($requests['mode'])
{

	// edi��o da p�gina
	case 'edPag': 
		$template = 'admin/page_edit.tpl';
	break;
		
	// deslocar categoria para cima
	case 'upCat':
		$pagina = new pagina($_idPagina);
		$pagina->deslocarElementoParaCima($_REQUEST['idCat']);
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$homepage->assign('scriptMode', 'edPag');
		$template = 'admin/script_reload.tpl';
	break;
	
	// deslocar categoria para baixo
	case 'downCat':
		$pagina = new pagina($_idPagina);
		$pagina->deslocarElementoParaBaixo($_REQUEST['idCat']);
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$homepage->assign('scriptMode', 'edPag');
		$template = 'admin/script_reload.tpl';
	break;

	// excluir uma categoria da p�gina
	case 'rmCat':
		$pagina = new pagina($_idPagina);
		$pagina->excluirElemento($_REQUEST['idCat']);
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$homepage->assign('scriptMode', 'edPag');
		$template = 'admin/script_reload.tpl';
	break;

	// incluir nova categoria na p�gina
	case 'inCat':
		$pagina = new pagina($_idPagina);
		$pagina->incluirElemento($_REQUEST['categoriaSelector']);
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$homepage->assign('scriptMode', 'edPag');
		$template = 'admin/script_reload.tpl';
	break;
				
	// salvar a p�gina (um dos bot�es do form foi clicado)
	case 'svPag':
		$pagina = new pagina($_idPagina);
		$pagina->tituloPagina = (string) $_REQUEST['tituloPagina'];
		$pagina->tituloTabela = (string) $_REQUEST['tituloTabela'];
		$pagina->classPagina = (string) $_REQUEST['classPagina'];
		$pagina->displayGoogle = ( isset($_REQUEST['displayGoogle']) ) ? 1 : 0;
		$pagina->displayFindaMap = ( isset($_REQUEST['displayFindaMap']) ) ? 1 : 0;
		$pagina->displayFortune = ( isset($_REQUEST['displayFortune']) ) ? 1 : 0;
		$pagina->displayImagemTitulo = ( isset($_REQUEST['displayImagemTitulo']) ) ? 1 : 0;
		$pagina->displaySelectColor = ( isset($_REQUEST['displaySelectColor']) ) ? 1 : 0;
		if ($pagina->atualizar()) 
		{
			$homepage->assign('msgAlerta', "P�gina [$pagina->tituloPagina] atualizada com sucesso!");
		}
		else
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel atualizar a p�gina [$pagina->tituloPagina]!");
		}
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$homepage->assign('scriptMode', 'edPag');
		$template = 'admin/script_reload.tpl';
	break;

	// apresenta um form vazio para a cria��o de uma nova p�gina. O flag $criarPagina vai mudar o comportamento do 
	// template.
	case 'nwPag':
		$criarPagina = true;
		$template = 'admin/page_edit.tpl';
	break;

	// criar uma nova p�gina (chamado a partir do form de edi��o com tag <form> alterada quando $criarPagina = true) 
	case 'crPag':
		$pagina = new pagina(NULL);
		$pagina->tituloPagina = (string) $_REQUEST['tituloPagina'];
		$pagina->tituloTabela = (string) $_REQUEST['tituloTabela'];
		$pagina->classPagina = (string) $_REQUEST['classPagina'];
		$pagina->displayGoogle = ( isset($_REQUEST['displayGoogle']) ) ? 1 : 0;
		$pagina->displayFindaMap = ( isset($_REQUEST['displayFindaMap']) ) ? 1 : 0;
		$pagina->displayFortune = ( isset($_REQUEST['displayFortune']) ) ? 1 : 0;
		$pagina->displayImagemTitulo = ( isset($_REQUEST['displayImagemTitulo']) ) ? 1 : 0;
		$pagina->displaySelectColor = ( isset($_REQUEST['displaySelectColor']) ) ? 1 : 0;
		$_idPagina = $pagina->inserir();
		if (!$_idPagina) 
		{
			$homepage->assign('scriptMode', 'slPag');
			$homepage->assign('msgAlerta', "N�o foi poss�vel criar a p�gina [$pagina->tituloPagina]");
		}
		else
		{
			$homepage->assign('scriptMode', 'edPag');
			$homepage->assign('msgAlerta', "P�gina [$pagina->tituloPagina] criada com sucesso!");
		}
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$template = 'admin/script_reload.tpl';
	break;

	// excluir esta p�gina da base - exibe o form de confirma��o para voltar mais tarde no modo ExPag
	case 'cfExPag':
		$template = 'admin/delete_confirm.tpl';
	break;

	// excluir uma p�gina (j� foi exibido o form de confirma��o).
	case 'exPag':
		switch ($requests['go'])
		{
			case $lang['sim']:
				$pagina = new pagina($_idPagina);
				if ($pagina->excluir())
				{
					$homepage->assign('msgAlerta', "P�gina [$pagina->tituloPagina] exclu�da com sucesso!");
					$homepage->assign('scriptMode', 'slPag');
				}
				else
				{
					$homepage->assign('msgAlerta', "N�o foi poss�vel excluir a p�gina [$pagina->tituloPagina]!");
					$homepage->assign('scriptMode', 'edPag');
				}
			break;

			case $lang['nao']:
				$homepage->assign('scriptMode', 'edPag');
			break;
		}
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$template = 'admin/script_reload.tpl';
	break;

	case 'slPag':
	default:
		$template = 'admin/page_select.tpl';
	break;
}

//
// Inicializo vari�veis e passo, dependendo do template que vou carregar...
$homepage->assign('displayImagemTitulo', '1');

switch ($template)
{
	case 'admin/page_edit.tpl':
		include(HOMEPAGE_PATH . 'admin/criar_exemplo.php');
		$homepage->assign('descricoesCategorias', $categoriaExemplo);
		$homepage->assign('descricoesGrupos', $descricoesGrupos);
		$homepage->assign('criarPagina', $criarPagina);
		
                /* obt�m a lista */
                $homepage->assign( 'classNames', cssEstilos::getClassNames( ) );

		if (!$criarPagina) 
		{
			$homepage->assign('cookedStyles', '');

			// se a p�gina j� existir, carrega e exibe
			$pagina = new pagina($_idPagina);
			$homepage->assign('tituloPaginaAlternativo', $pagina->tituloPagina . ' :: Edi&ccedil;&atilde;o');
			$homepage->assign('tituloTabelaAlternativo', $pagina->tituloTabela . ' :: Edi&ccedil;&atilde;o');
			$homepage->assign('idPagina', $_idPagina);
			$homepage->assign('tituloPagina', $pagina->tituloPagina);
			$homepage->assign('tituloTabela', $pagina->tituloTabela);
			$homepage->assign('classPagina', $pagina->classPagina);
			$homepage->assign('displayGoogle', $pagina->displayGoogle);
			$homepage->assign('displayFindaMap', $pagina->displayFindaMap);
			$homepage->assign('displayFortune', $pagina->displayFortune);
			$homepage->assign('displayImagemTitulo', $pagina->displayImagemTitulo);
			$homepage->assign('displaySelectColor', $pagina->displaySelectColor);
			$pagina->lerElementos();
			$descricoesCategorias[] = '';
			foreach ($pagina->elementos as $categoria)
			{
				$descricoesCategorias[] = $categoria->getArray();
			}
			array_shift($descricoesCategorias);
			$homepage->assign('categoriasPresentes', $descricoesCategorias);
			$homepage->assign('categoriasAusentes', $pagina->lerNaoElementos());
		}
		else
		{
			$homepage->assign('cookedStyles', '');

			// inicializa os campos para cria��o de uma nova p�gina
			$homepage->assign('tituloPaginaAlternativo', ' :: Cria&ccedil;&atilde;o de p&aacute;gina');
			$homepage->assign('tituloTabelaAlternativo', ' :: Nova p&aacute;gina :: ');
			$homepage->assign('tituloPagina', $lang['hp_paginas_TituloPagina']);
			$homepage->assign('tituloTabela', $lang['hp_paginas_TituloTabela']);
			$homepage->assign('classPagina', 'admin');
			$homepage->assign('displayGoogle', 1);
			$homepage->assign('displayFindaMap', 1);
			$homepage->assign('displayFortune', 1);
			$homepage->assign('displayImagemTitulo', 1);
			$homepage->assign('displaySelectColor', 1);
		}
	break;

	case 'admin/page_select.tpl':
		// le os cookies e passa para a p�gina a ser carregada.
		$homepage->assign('cookedStyles', '');

		$homepage->assign('paginas', pagina::getPaginas());
		$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarPagina']);
		$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarPagina']);
		$homepage->assign('classPagina', 'admin');
		$homepage->assign('displaySelectColor', 0);
	break;

	case 'admin/delete_confirm.tpl':
		$pagina = new pagina($_idPagina);
		$homepage->assign('cookedStyles', '');
		$homepage->assign('idPagina', $_idPagina);
		$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusao']);
		$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaConfirmarExclusao']);
		$homepage->assign('scriptMode', 'exPag');
		$homepage->assign('script2call', 'admin/page_edit.php');
		$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoPagina']);
		$homepage->assign('deleteConfirmDescricao', $pagina->tituloPagina . ' :: ' . $pagina->tituloTabela);
		$homepage->assign('classPagina', 'admin');
		$homepage->assign('displaySelectColor', 0);
	break;

	case 'admin/script_reload.tpl':
		if (isset($_idPagina)) 
		{
			$homepage->assign('idPagina', $_idPagina);
		}
	break;
}

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->display($template);

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
