<?php

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
include_once('../common.php');

// classes específicas da homepage
include_once($include_path . 'class_homepage.php');

// este flag eu vou usar mais tarde (em categoria_edit_body.tpl para configurar a action do formulário).
$criarCategoria = false;

// garante que vão aparecer todos os grupos
$_REQUEST['gr'] = 'all';

// verifica se passou página. se houver, vai apresentá-la no form
if (isset($requests['id'])) 
{
	$_idPagina = $requests['id'];
}

// se não foi passada nenhuma categoria, entra em modo de seleção de categorias
if (isset($requests['idCat']))
{
	$_idCategoria = $requests['idCat'];
}

//
// se não passou nenhum mode, entra em modo de seleção de categorias
if (!isset($requests['mode']))
{
	$requests['mode'] = 'slCat';
}

switch ($requests['mode'])
{

	// edição da categoria
	case 'edCat': 
		$template = 'admin/categoria_edit.tpl';
	break;
		
	// deslocar grupo para cima
	case 'upGrp':
		$categoria = new categoria($_idCategoria);
		$categoria->deslocarElementoParaCima($requests['idGrp']);
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;
	
	// deslocar grupo para baixo
	case 'downGrp':
		$categoria = new categoria($_idCategoria);
		$categoria->deslocarElementoParaBaixo($requests['idGrp']);
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;

	// excluir uma categoria da página
	case 'rmGrp':
		$categoria = new categoria($_idCategoria);
		$categoria->excluirElemento($requests['idGrp']);
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;

	// incluir nova categoria na página
	case 'inGrp':
		$categoria = new categoria($_idCategoria);
		$categoria->incluirElemento($requests['grupoSelector']);
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;
				
	// Atualiza a categoria atualmente em edição
	case 'svCat':
		$categoria = new categoria($_idCategoria);
		$categoria->descricaoCategoria = (string) $requests['descricaoCategoria'];
		$categoria->categoriaRestrita = ( isset($requests['categoriaRestrita']) ) ? 1 : 0;
		$categoria->restricaoCategoria = ( isset($requests['restricaoCategoria']) ) ? (string) $requests['restricaoCategoria'] : '' ;
		if ($categoria->atualizar()) 
		{
			prepare_msgAlerta('success', "categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "] salva!");
		} 
		else
		{
			prepare_msgAlerta('warning', "Não foi possível salvar a categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "]!");
		}
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;

	// apresenta um form vazio para a criação de uma nova página. O flag $criarCategoria vai mudar o comportamento do 
	// template.
	case 'nwCat':
		$criarCategoria = true;
		$template = 'admin/categoria_edit.tpl';
	break;

	// criar uma nova Categoria (chamado a partir do form de edição com tag <form> alterada quando $criarCategoria = true) 
	case 'crCat':
		$homepage->assign('requests', $requests);
		$categoria = new categoria(NULL);
		$categoria->descricaoCategoria = (string) $requests['descricaoCategoria'];
		$categoria->categoriaRestrita = ( isset($requests['categoriaRestrita']) ) ? 1 : 0;
		$categoria->restricaoCategoria = ( isset($requests['restricaoCategoria']) ) ? (string) $requests['restricaoCategoria'] : '';
		$_idCategoria = $categoria->inserir();
		if (!$_idCategoria) 
		{
			$homepage->assign('scriptMode', 'slCat');
			prepare_msgAlerta('warning', "Não foi possível criar a categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "]!");
		}
		else
		{	
			$homepage->assign('scriptMode', 'edCat');
			prepare_msgAlerta('success', "Categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "] salva!");
		}
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$template = 'admin/script_reload.tpl';
	break;

	// excluir esta categoria da base - exibe o form de confirmação para voltar mais tarde no modo ExCat
	case 'cfExCat':
		$template = 'admin/delete_confirm.tpl';
	break;

	// excluir uma categoria (já foi exibido o form de confirmação).
	case 'exCat':
		switch ($requests['go'])
		{
			case $lang['sim']:
				$categoria = new categoria($_idCategoria);
				if ($categoria->excluir())
				{
					prepare_msgAlerta('success', "Categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "] excluída!");
					$homepage->assign('script2reload', 'admin/categoria_edit.php');
					$homepage->assign('scriptMode', 'slCat');
				}
				else
				{
					prepare_msgAlerta('warning', "Não foi possível excluir a categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "]!");
					$homepage->assign('script2reload', 'admin/categoria_edit.php');
					$homepage->assign('scriptMode', 'edCat');
				}
				$template = 'admin/script_reload.tpl';
			break;

			case $lang['nao']:
				$homepage->assign('script2reload', 'admin/categoria_edit.php');
				$homepage->assign('scriptMode', 'edCat');
				$template = 'admin/script_reload.tpl';
			break;
		}
	break;

	case 'slCat':
	default:
		$template = 'admin/categoria_select.tpl';
	break;
}

// le os cookies e passa para a página a ser carregada.
$cookedStyles = '';
$colorCookies = cookedStyle::getArray(5);
if ($colorCookies) 
{
	foreach ($colorCookies as $selector => $colorCookie) {
		$cookedStyles .= implode("\n", $colorCookie) . "\n}\n";
	}
}
$homepage->assign('cookedStyles', $cookedStyles);

$homepage->assign('displayImagemTitulo', '1');

switch ($template)
{
	case 'admin/categoria_edit.tpl':
		if (!$criarCategoria) 
		{
			// lê a página desta categoria.
			if (isset($_idPagina)) {
				$pagina = new pagina($_idPagina);
				$homepage->assign('idPagina', $_idPagina);
				$homepage->assign('tituloPagina', $pagina->tituloPagina);
				$homepage->assign('classPagina', $pagina->classPagina);
			} 
			else 
			{
				$homepage->assign('classPagina', 'admin');
				#$homepage->assign('$id_Pagina', 0);
			}

			// lê a categoria
			$categoria = new categoria($_idCategoria);
			$homepage->assign('idCategoria', $_idCategoria);
			$homepage->assign('descricaoCategoria', $categoria->descricaoCategoria);
			$homepage->assign('categoriaRestrita', $categoria->categoriaRestrita);
			$homepage->assign('restricaoCategoria', $categoria->restricaoCategoria);
			$homepage->assign('displaySelectColor', 0);

			$homepage->assign('tituloPaginaAlternativo', $categoria->descricaoCategoria . ' :: Edi&ccedil;&atilde;o');
			$homepage->assign('tituloTabelaAlternativo', $categoria->descricaoCategoria . ' :: Edi&ccedil;&atilde;o');

			// lê os grupos desta categoria
			$categoria->lerElementos();
			$descricoesGrupos[] = '';
			foreach ($categoria->elementos as $grupo)
			{
				$descricoesGrupos[] = $grupo->getArray();
			}
			array_shift($descricoesGrupos);
			$homepage->assign('gruposPresentes', $descricoesGrupos);
			$homepage->assign('gruposAusentes', $categoria->lerNaoElementos());
			$homepage->assign('tiposGrupos', tiposGrupos::getArray());
		}
		else
		{
		// inicializa os campos para criação de uma nova página
			$homepage->assign('$id_Pagina', 0);
			$homepage->assign('tituloPaginaAlternativo', ' :: Cria&ccedil;&atilde;o de Categoria');
			$homepage->assign('tituloTabelaAlternativo', ' :: Nova Categoria :: ');
			$homepage->assign('descricaoCategoria', $lang['hp_categorias_DescricaoCategoria']);
			$homepage->assign('categoriaRestrita', 0);
			$homepage->assign('restricaoCategoria', '');
			$homepage->assign('classPagina', 'admin');
			$homepage->assign('displaySelectColor', 0);
		}
	break;

	case 'admin/categoria_select.tpl':
		if (isset($_idPagina)) {
			$pagina = new pagina($_idPagina);
			$homepage->assign('idPagina', $_idPagina);
			$homepage->assign('tituloPagina', $pagina->tituloPagina);
		}
		$homepage->assign('idPagina', 0);
		$homepage->assign('categorias', categoria::getCategorias());
		$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarCategoria']);
		$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarCategoria']);
		$homepage->assign('classPagina', 'admin');
		$homepage->assign('displaySelectColor', 0);
	break;

	case 'admin/delete_confirm.tpl':
		$categoria = new categoria($_idCategoria);
		$homepage->assign('idCategoria', $_idCategoria);
		$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoCategoria']);
		$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaConfirmarExclusao']);
		$homepage->assign('scriptMode', 'exCat');
		$homepage->assign('script2call', 'admin/categoria_edit.php');
		$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoCategoria']);
		$homepage->assign('deleteConfirmDescricao', $categoria->descricaoCategoria);
		$homepage->assign('classPagina', 'admin');
		$homepage->assign('displaySelectColor', 0);
	break;

	case 'admin/script_reload.tpl':
		if (isset($_idPagina))
		{
			$homepage->assign('id', $_idPagina);
		}
		if (isset($_idCategoria))
		{
			$homepage->assign('idCategoria', $_idCategoria);
		}
	break;

}

$homepage->assign('criarCategoria', $criarCategoria);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->display($template);

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
