<?php
//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

// classes específicas da homepage
include_once($include_path . 'class_homepage.php');

// este flag eu vou usar mais tarde (em page_edit_body.tpl para configurar a action do formulário).
$criarPagina = false;

// garante que vão aparecer todas as categorias.
$_REQUEST['gr'] = 'all';

// se não foi passado nenhuma página, entre em modo de seleção de página
if (isset($requests['id'])) 
{
	$_idPagina = $requests['id'];
}

// Obtém a página administrativa
$admPag = new pagina(ID_ADM_PAG);

//
// se tem alguma coisa estranha, cai no default (slPag)
if ( !isset($requests['mode']) || (isset($_idPagina) && $_idPagina == '') )
{
	$requests['mode'] = 'slPag';
}

switch ($requests['mode'])
{

	// edição da página
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

	// excluir uma categoria da página
	case 'rmCat':
		$pagina = new pagina($_idPagina);
		$pagina->excluirElemento($_REQUEST['idCat']);
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$homepage->assign('scriptMode', 'edPag');
		$template = 'admin/script_reload.tpl';
	break;

	// incluir nova categoria na página
	case 'inCat':
		$pagina = new pagina($_idPagina);
		$pagina->incluirElemento($_REQUEST['categoriaSelector']);
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$homepage->assign('scriptMode', 'edPag');
		$template = 'admin/script_reload.tpl';
	break;
				
	// salvar a página (um dos botões do form foi clicado)
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
			prepare_msgAlerta('success', "Página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "] atualizada com sucesso!");
		else
			prepare_msgAlerta('warning', "Não foi possível atualizar a página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "]!");
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$homepage->assign('scriptMode', 'edPag');
		$template = 'admin/script_reload.tpl';
	break;

	// apresenta um form vazio para a criação de uma nova página. O flag $criarPagina vai mudar o comportamento do 
	// template.
	case 'nwPag':
		$criarPagina = true;
		$template = 'admin/page_edit.tpl';
	break;

	// criar uma nova página (chamado a partir do form de edição com tag <form> alterada quando $criarPagina = true) 
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
			prepare_msgAlerta('warning', "Não foi possível criar a página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "]");
		}
		else
		{
			$homepage->assign('scriptMode', 'edPag');
			prepare_msgAlerta('success', "Página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "] criada com sucesso!");
		}
		$homepage->assign('script2reload', 'admin/page_edit.php');
		$template = 'admin/script_reload.tpl';
	break;

	// excluir esta página da base - exibe o form de confirmação para voltar mais tarde no modo ExPag
	case 'cfExPag':
		$template = 'admin/delete_confirm.tpl';
	break;

	// excluir uma página (já foi exibido o form de confirmação).
	case 'exPag':
		switch ($requests['go'])
		{
			case $lang['sim']:
				$pagina = new pagina($_idPagina);
				if ($pagina->excluir())
				{
					prepare_msgAlerta('success', "Página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "] excluída com sucesso!");
					$homepage->assign('scriptMode', 'slPag');
				}
				else
				{
					prepare_msgAlerta('warning', "Não foi possível excluir a página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "]!");
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
// Inicializo variáveis e passo, dependendo do template que vou carregar...
$homepage->assign('displayImagemTitulo', '0');

// A página de administração tem idPagina = 5. vou usar para pegar a classe de estilos da página 
$pagina = new pagina(5);

switch ($template)
{
	case 'admin/page_edit.tpl':
		include(HOMEPAGE_PATH . 'admin/criar_exemplo.php');
		$homepage->assign('descricoesCategorias', $categoriaExemplo);
		$homepage->assign('descricoesGrupos', $descricoesGrupos);
		$homepage->assign('criarPagina', $criarPagina);
		
        /* obtém a lista */
        $homepage->assign( 'classNames', cssEstilos::getClassNames( ) );

		if (!$criarPagina) 
		{
			$homepage->assign('cookedStyles', '');

			// se a página já existir, carrega e exibe
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

			// inicializa os campos para criação de uma nova página
			$homepage->assign('tituloPaginaAlternativo', ' :: Cria&ccedil;&atilde;o de p&aacute;gina');
			$homepage->assign('tituloTabelaAlternativo', ' :: Nova p&aacute;gina :: ');
			$homepage->assign('tituloPagina', '');
			$homepage->assign('tituloTabela', '');
			$homepage->assign('classPagina', $admPag->classPagina);
			$homepage->assign('displayGoogle', 1);
			$homepage->assign('displayFindaMap', 1);
			$homepage->assign('displayFortune', 1);
			$homepage->assign('displayImagemTitulo', 1);
			$homepage->assign('displaySelectColor', 1);
		}
	break;

	case 'admin/page_select.tpl':
		// le os cookies e passa para a página a ser carregada.
		$homepage->assign('cookedStyles', '');

		$homepage->assign('paginas', pagina::getPaginas());
		$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarPagina']);
		$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarPagina']);
		$homepage->assign('classPagina', $admPag->classPagina);
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
		$homepage->assign('classPagina', $pagina->classPagina);
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
