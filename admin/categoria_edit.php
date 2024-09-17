<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// este flag eu vou usar mais tarde (em categoria_edit_body.tpl para configurar a action do formulário).
$criarCategoria = false;

// garante que vão aparecer todos os grupos
$_REQUEST['gr'] = 'all';

if (isset($requests['idCat'])) 
    $_idCategoria = $requests['idCat'];

switch ($requests['mode'])
{
    // edição da categoria
    case 'edCat': 
        $homepage->assign('idCategoria', $_idCategoria);
        $template = 'admin/categoria_edit.tpl';
    break;
                
    // Atualiza a categoria atualmente em edição
    case 'svCat':
        $categoria = new Pagina\Categoria($_idCategoria);
        $categoria->descricaoCategoria = (string) $requests['descricaoCategoria'];
        $categoria->categoriaRestrita = (isset($requests['categoriaRestrita']) ? 1 : 0);
        $categoria->restricaoCategoria = (isset($requests['restricaoCategoria']) ? (string) $requests['restricaoCategoria'] : '');
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
        $homepage->assign('idCategoria', $_idCategoria);
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
        $homepage->assign('idCategoria', $_idCategoria);
        $template = 'admin/script_reload.tpl';
    break;

    // excluir uma categoria
    case 'exCat':
        $categoria = new Pagina\Categoria($_idCategoria);
        if ($categoria->excluir())
        {
            prepararToast('success', "Categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "] excluída!");
            $homepage->assign('script2reload', 'admin/categoria_select.php');
        }
        else
        {
            prepararToast('warning', "Não foi possível excluir a categoria [" . $global_hpDB->real_escape_string($categoria->descricaoCategoria) . "]!");
            $homepage->assign('script2reload', 'admin/categoria_edit.php');
            $homepage->assign('script2reload', 'admin/categoria_edit.php');
            $homepage->assign('scriptMode', 'edCat');
        }
        $template = 'admin/script_reload.tpl';
    break;

    case 'slCat':
    default:
        $homepage->assign('script2reload', 'admin/categoria_select.php');
        $template = 'admin/script_reload.tpl';
    break;
}


$admPag = new Pagina\Pagina(ID_ADM_PAG);
if ($template == 'admin/categoria_edit.tpl') {

    $homepage->assign('temaPagina', $admPag->temaPagina);

    if (!$criarCategoria) 
    {
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
        $homepage->assign('displaySelectColor', 0);
    }
}

// obtém os items do menu
include($admin_path . 'ler_menu.php');
$homepage->assign('rootVars', '');
$homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
$homepage->assign('criarCategoria', $criarCategoria);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display($template);
?>
