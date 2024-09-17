<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Pagina as Pagina;

// este flag eu vou usar mais tarde (em page_edit_body.tpl para configurar a action do formulário).
$criarPagina = false;

// garante que vão aparecer todas as categorias.
$_REQUEST['gr'] = 'all';

// se não foi passado nenhuma página, entre em modo de seleção de página
if (isset($requests['idPagina'])) 
    $_idPagina = $requests['idPagina'];
elseif (isset($requests['id'])) 
    $_idPagina = $requests['id'];

// Obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);

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
        
    // salvar a página (um dos botões do form foi clicado)
    case 'svPag':
        $pagina = new Pagina\Pagina($_idPagina);
        $pagina->tituloPagina = (string) $_REQUEST['tituloPagina'];
        $pagina->tituloTabela = (string) $_REQUEST['tituloTabela'];
        $pagina->temaPagina = (string) $_REQUEST['temaPagina'];
        $pagina->displayFortune = ( isset($_REQUEST['displayFortune']) ) ? 1 : 0;
        $pagina->displayImagemTitulo = ( isset($_REQUEST['displayImagemTitulo']) ) ? 1 : 0;
        $pagina->displaySelectColor = ( isset($_REQUEST['displaySelectColor']) ) ? 1 : 0;
        if ($pagina->atualizar()) 
            prepararToast('success', "Página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "] atualizada com sucesso!");
        else
            prepararToast('warning', "Não foi possível atualizar a página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "]!");
        $homepage->assign('idPagina', $_idPagina);
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
        $pagina = new Pagina\Pagina(NULL);
        $pagina->tituloPagina = (string) $_REQUEST['tituloPagina'];
        $pagina->tituloTabela = (string) $_REQUEST['tituloTabela'];
        $pagina->temaPagina = (string) $_REQUEST['temaPagina'];
        $pagina->displayFortune = ( isset($_REQUEST['displayFortune']) ) ? 1 : 0;
        $pagina->displayImagemTitulo = ( isset($_REQUEST['displayImagemTitulo']) ) ? 1 : 0;
        $pagina->displaySelectColor = ( isset($_REQUEST['displaySelectColor']) ) ? 1 : 0;
        $_idPagina = $pagina->inserir();
        if (!$_idPagina) 
        {
            $homepage->assign('scriptMode', 'slPag');
            prepararToast('warning', "Não foi possível criar a página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "]");
        }
        else
        {
            $homepage->assign('scriptMode', 'edPag');
            prepararToast('success', "Página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "] criada com sucesso!");
        }
        $homepage->assign('idPagina', $_idPagina);
        $homepage->assign('script2reload', 'admin/page_edit.php');
        $template = 'admin/script_reload.tpl';
    break;

    // excluir uma página (já foi exibido o form de confirmação).
    case 'exPag':
            $pagina = new Pagina\Pagina($_idPagina);
            if ($pagina->excluir())
            {
                prepararToast('success', "Página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "] excluída com sucesso!");
                $homepage->assign('scriptMode', 'slPag');
            }
            else
            {
                prepararToast('warning', "Não foi possível excluir a página [" . $global_hpDB->real_escape_string($pagina->tituloPagina) . "]!");
                $homepage->assign('scriptMode', 'edPag');
            }
        $homepage->assign('script2reload', 'admin/page_select.php');
        $template = 'admin/script_reload.tpl';
    break;

    case 'slPag':
    default:
        $homepage->assign('script2reload', 'admin/page_select.php');
        $template = 'admin/script_reload.tpl';
    break;
}

if ($template == 'admin/page_edit.tpl') {
    // obtém a lista de temas disponiveis
    $homepage->assign('classNames', Temas\Temas::obterNomes() );
    $homepage->assign('criarPagina', $criarPagina);
    $homepage->assign('rootVars', '');

    if ($criarPagina) {
        // inicializa os campos para criação de uma nova página
        $homepage->assign('tituloPaginaAlternativo', ' :: Cria&ccedil;&atilde;o de p&aacute;gina');
        $homepage->assign('tituloTabelaAlternativo', ' :: Nova p&aacute;gina :: ');
        $homepage->assign('tituloPagina', '');
        $homepage->assign('tituloTabela', '');
        $homepage->assign('temaPagina', $admPag->temaPagina);
        $homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
        $homepage->assign('displayFortune', 1);
        $homepage->assign('displaySelectColor', 1);
    }
    else {
        // inicializa os campos para a edição de uma página já existente
        $pagina = new Pagina\Pagina($_idPagina);
        $homepage->assign('tituloPaginaAlternativo', $pagina->tituloPagina . ' :: Edi&ccedil;&atilde;o');
        $homepage->assign('tituloTabelaAlternativo', $pagina->tituloTabela . ' :: Edi&ccedil;&atilde;o');
        $homepage->assign('idPagina', $_idPagina);
        $homepage->assign('tituloPagina', $pagina->tituloPagina);
        $homepage->assign('tituloTabela', $pagina->tituloTabela);
        $homepage->assign('temaPagina', $pagina->temaPagina);
        $homepage->assign('displayFortune', $pagina->displayFortune);
        $homepage->assign('displayImagemTitulo', $pagina->displayImagemTitulo);
        $homepage->assign('displaySelectColor', $pagina->displaySelectColor);
        $pagina->lerElementos();
        $descricoesCategorias[] = '';
        foreach ($pagina->elementos as $categoria)
            $descricoesCategorias[] = $categoria->getArray();
        array_shift($descricoesCategorias);
        $homepage->assign('categoriasPresentes', $descricoesCategorias);
        $homepage->assign('categoriasAusentes', $pagina->lerNaoElementos());
    }

    // obtém os items do menu
    include($admin_path . 'ler_menu.php');
}

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display($template);
?>
