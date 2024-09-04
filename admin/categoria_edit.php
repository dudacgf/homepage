<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

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

// se tem alguma coisa estranha, cai no default (slCat)
if ( !isset($requests['mode']) || (isset($_idCategoria) && $_idCategoria == '') )
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
	case 'descenderGrupo':
		$categoria = new Pagina\Categoria($_idCategoria);
		$categoria->deslocarElementoParaCima($requests['idGrp']);
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;
	
	// deslocar grupo para baixo
	case 'downGrp':
		$categoria = new Pagina\Categoria($_idCategoria);
		$categoria->deslocarElementoParaBaixo($requests['idGrp']);
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;

	// excluir uma categoria da página
	case 'rmGrp':
		$categoria = new Pagina\Categoria($_idCategoria);
		$categoria->excluirElemento($requests['idGrp']);
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;

	// incluir nova categoria na página
	case 'incluirGrupo':
		$categoria = new Pagina\Categoria($_idCategoria);
		$categoria->incluirElemento($requests['grupoSelector']);
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$homepage->assign('scriptMode', 'edCat');
		$template = 'admin/script_reload.tpl';
	break;
				
	// Atualiza a categoria atualmente em edição
	case 'svCat':
		$categoria = new Pagina\Categoria($_idCategoria);
		$categoria->descricaoCategoria = (string) $requests['descricaoCategoria'];
		$categoria->categoriaRestrita = ( isset($requests['categoriaRestrita']) ) ? 1 : 0;
		$categoria->restricaoCategoria = ( isset($requests['restricaoCategoria']) ) ? (string) $requests['restricaoCategoria'] : '' ;
		if ($categoria->atualizar()) 
		{
			prepararToast('success', "categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "] salva!");
		} 
		else
		{
			prepararToast('warning', "Não foi possível salvar a categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "]!");
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
		$categoria = new Pagina\Categoria(NULL);
		$categoria->descricaoCategoria = (string) $requests['descricaoCategoria'];
		$categoria->categoriaRestrita = ( isset($requests['categoriaRestrita']) ) ? 1 : 0;
		$categoria->restricaoCategoria = ( isset($requests['restricaoCategoria']) ) ? (string) $requests['restricaoCategoria'] : '';
		$_idCategoria = $categoria->inserir();
		if (!$_idCategoria) 
		{
			$homepage->assign('scriptMode', 'slCat');
			prepararToast('warning', "Não foi possível criar a categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "]!");
		}
		else
		{	
			$homepage->assign('scriptMode', 'edCat');
			prepararToast('success', "Categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "] salva!");
		}
		$homepage->assign('script2reload', 'admin/categoria_edit.php');
		$template = 'admin/script_reload.tpl';
	break;

	// excluir uma categoria
	case 'exCat':
        $categoria = new Pagina\Categoria($_idCategoria);
        if ($categoria->excluir())
        {
            prepararToast('success', "Categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "] excluída!");
            $homepage->assign('script2reload', 'admin/categoria_edit.php');
            $homepage->assign('scriptMode', 'slCat');
        }
        else
        {
            prepararToast('warning', "Não foi possível excluir a categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "]!");
            $homepage->assign('script2reload', 'admin/categoria_edit.php');
            $homepage->assign('scriptMode', 'edCat');
        }
        $template = 'admin/script_reload.tpl';
	break;

	case 'slCat':
	default:
		$template = 'admin/categoria_select.tpl';
	break;
}

// le os cookies e passa para a página a ser carregada.
$homepage->assign('cookedStyles', '');

$homepage->assign('displayImagemTitulo', '1');

// obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);

switch ($template)
{
	case 'admin/categoria_edit.tpl':
		if (!$criarCategoria) 
		{
			// lê a página desta categoria.
			if (isset($_idPagina)) {
				$pagina = new Pagina\Pagina($_idPagina);
				$homepage->assign('idPagina', $_idPagina);
				$homepage->assign('tituloPagina', $pagina->tituloPagina);
				$homepage->assign('classPagina', $pagina->classPagina);
			} 
			else 
				$homepage->assign('classPagina', $admPag->classPagina);

			// lê a categoria
			$categoria = new Pagina\Categoria($_idCategoria);
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
			$homepage->assign('tiposGrupos', Pagina\TiposGrupos::getArray());
		}
		else
		{
		// inicializa os campos para criação de uma nova página
			$homepage->assign('$id_Pagina', 0);
			$homepage->assign('tituloPaginaAlternativo', ' :: Cria&ccedil;&atilde;o de Categoria');
			$homepage->assign('tituloTabelaAlternativo', ' :: Nova Categoria :: ');
			$homepage->assign('descricaoCategoria', '');
			$homepage->assign('categoriaRestrita', 0);
			$homepage->assign('restricaoCategoria', '');
			$homepage->assign('classPagina', $admPag->classPagina);
			$homepage->assign('displaySelectColor', 0);
		}
	break;

	case 'admin/categoria_select.tpl':
		if (isset($_idPagina)) {
			$pagina = new Pagina\Pagina($_idPagina);
			$homepage->assign('idPagina', $_idPagina);
			$homepage->assign('tituloPagina', $pagina->tituloPagina);
		}
		$homepage->assign('idPagina', 0);
		$homepage->assign('categorias', Pagina\Categoria::getCategorias());
		$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarCategoria']);
		$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarCategoria']);
		$homepage->assign('classPagina', $admPag->classPagina);
		$homepage->assign('displaySelectColor', 0);
	break;

	case 'admin/delete_confirm.tpl':
		$categoria = new Pagina\Categoria($_idCategoria);
		$homepage->assign('idCategoria', $_idCategoria);
		$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoCategoria']);
		$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaConfirmarExclusao']);
		$homepage->assign('scriptMode', 'exCat');
		$homepage->assign('script2call', 'admin/categoria_edit.php');
		$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoCategoria']);
		$homepage->assign('deleteConfirmDescricao', $categoria->descricaoCategoria);
		$homepage->assign('classPagina', $admPag->classPagina);
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

// obtém os items do menu
include($admin_path . 'ler_menu.php');

$homepage->assign('criarCategoria', $criarCategoria);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('imagesPATH', $images_path);
$homepage->display($template);

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
