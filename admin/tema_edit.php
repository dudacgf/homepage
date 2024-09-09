<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Pagina as Pagina;

// este flag eu vou usar no template
$criarTema = false;

// pega o id do tema, se existente
if (isset($requests['id']))
    $_idTema = $requests['id'];
elseif (isset($requests['idTema']))
    $_idTema = $requests['idTema'];

// Obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);

// se tem alguma coisa estranha, cai no default (slTema)
if ( !isset($requests['mode']) || (isset($_idTema) && $_idTema == '') )
{
    $requests['mode'] = 'slTema';
}

switch ($requests['mode'])
{

    // edição do Tema
    case 'edTema': 
        $template = 'admin/tema_edit.tpl';
    break;
        
    // salvar o Tema já existente 
    case 'svTema':
        $tema = new Temas\Temas($_idTema);
        $tema->nome = (string) $_REQUEST['nome'];
        $tema->comentario = (string) $_REQUEST['comentario'];
        if ($tema->atualizar()) 
            prepararToast('success', "Tema [" . $global_hpDB->real_escape_string($tema->nome) . "] atualizado com sucesso!");
        else
            prepararToast('warning', "Não foi possível atualizar o tema [" . $global_hpDB->real_escape_string($tema->nome) . "]!");
        $homepage->assign('idTema', $_idTema);
        $homepage->assign('script2reload', 'admin/tema_edit.php');
        $homepage->assign('scriptMode', 'edTema');
        $template = 'admin/script_reload.tpl';
    break;

    // apresenta um form vazio para a criação de um novo tema
    case 'nwTema':
        $criarTema = true;
        $template = 'admin/tema_edit.tpl';
    break;

    // salvar um novo tema (chamado a partir do form de edição com tag <form> alterada quando $criarTema = true) 
    case 'crTema':
        $tema = new Temas\Temas(NULL);
        $tema->nome = (string) $_REQUEST['nome'];
        $tema->comentario = (string) $_REQUEST['comentario'];
        $_idTema = $tema->inserir();
        if (!$_idTema) 
        {
            $homepage->assign('scriptMode', 'slTema');
            prepararToast('warning', "Não foi possível criar o tema [" . $global_hpDB->real_escape_string($tema->nome) . "]");
        }
        else
        {
            $homepage->assign('scriptMode', 'edTema');
            prepararToast('success', "Tema [" . $global_hpDB->real_escape_string($tema->nome) . "] criado com sucesso!");
        }
        $homepage->assign('idTema', $_idTema);
        $homepage->assign('script2reload', 'admin/tema_edit.php');
        $template = 'admin/script_reload.tpl';
    break;

    // excluir um Tema (já foi exibido o form de confirmação).
    case 'exTema':
            $tema = new Temas\Temas($_idTema);
            if ($tema->excluir())
            {
                prepararToast('success', "Tema [" . $global_hpDB->real_escape_string($tema->nome) . "] excluído com sucesso!");
                $homepage->assign('scriptMode', 'slTema');
                $homepage->assign('script2reload', 'admin/tema_select.php');
            }
            else
            {
                prepararToast('warning', "Não foi possível excluir o tema [" . $global_hpDB->real_escape_string($tema->nome) . "]!");
                $homepage->assign('scriptMode', 'edTema');
                $homepage->assign('script2reload', 'admin/tema_edit.php');
            }
        $template = 'admin/script_reload.tpl';
    break;

    case 'slTema':
    default:
        $homepage->assign('script2reload', 'admin/tema_select.php');
        $template = 'admin/script_reload.tpl';
    break;
}

if ($template == 'admin/tema_edit.tpl') {
    // obtém a lista de temas disponiveis
    $homepage->assign('criarTema', $criarTema);

    if ($criarTema) {
        // inicializa os campos para criação de um novo tema
        $homepage->assign('rootVars', '');
        $homepage->assign('tituloPaginaAlternativo', ':: Criação de tema');
        $homepage->assign('tituloTabelaAlternativo', ':: Novo tema :: ');
        $homepage->assign('tema', array('nome' => '', 'comentario' => ''));
        $homepage->assign('classPagina', $admPag->classPagina);
        $homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
        $homepage->assign('temas', Temas\Temas::getArray());
    }
    else {
        // lê os elementos coloridos e os pares de cores
        $homepage->assign('variaveisRoot', Temas\VariaveisRoot::obterTodasDeTipo('color'));
        $homepage->assign('pcPantone', Temas\PaletasdeCor::getArray('Pantone'));
        $homepage->assign('pcMaterial', Temas\PaletasdeCor::getArray('Material'));

        // le icones 
        $svg_hue = file_get_contents($images_path . 'hue.svg');
        $homepage->assign('svg_hue', $svg_hue);
        $svg_google = file_get_contents($images_path . 'google.svg');
        $homepage->assign('svg_google', $svg_google);
        $svg_pantone = file_get_contents($images_path . 'pantone.svg');
        $homepage->assign('svg_pantone', $svg_pantone);
        $svg_palette = file_get_contents($images_path . 'palette.svg');
        $homepage->assign('svg_palette', $svg_palette);

        // inicializa os campos para a edição de um tema já existente
        // verifica se há cookies de tema configurados para essa página
        $rootVars = '';
        $RVPs = Temas\RootVarsPagina::getArray(ID_COR_PAG);
        if ($RVPs) {
            $rootVars = ':root {';
            foreach ($RVPs as $rvp) {
                $rootVars .= $rvp['rootvar'] . ': ' . $rvp['cor'] . '; ';
            }
            $rootVars .= '}';
            $homepage->assign('rootVars', $rootVars);
        }

        $tema = new Temas\Temas($_idTema);
        $homepage->assign('rootVars', $rootVars);
        $homepage->assign('tituloPaginaAlternativo', $tema->nome . ' :: Edição');
        $homepage->assign('tituloTabelaAlternativo', $tema->nome . ' :: Edição');
        $homepage->assign('tema', array('nome' => $tema->nome, 'comentario' => $tema->comentario));
        $homepage->assign('idTema', $_idTema);
        $homepage->assign('classPagina', $admPag->classPagina);
        $homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
    }

    // obtém os items do menu
    include($admin_path . 'ler_menu.php');
}

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('idTema', ID_TEMA_PAG);
$homepage->display($template);
?>
