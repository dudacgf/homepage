<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Pagina as Pagina;

// este flag eu vou usar no template
$criarTema = false;

// pega o id do tema, se existente
if (isset($requests['idTema']))
    $_idTema = $requests['idTema'];
else 
    $_idTema = 1;

// Obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);

// se tem alguma coisa estranha, cai no default (slTema)
if ( !isset($requests['mode']) || (isset($_idTema) && $_idTema == '') )
{
    $requests['mode'] = 'slTema';
}

switch ($requests['mode'])
{

    // apresenta um form vazio para a criação de um novo tema
    case 'nwTema':
        $criarTema = true;

        $homepage->assign('rootVars', '');
        $homepage->assign('tituloPaginaAlternativo', ':: Criação de tema');
        $homepage->assign('tituloTabelaAlternativo', ':: Novo tema :: ');
        $homepage->assign('tema', array('nome' => '', 'comentario' => ''));
        $homepage->assign('classPagina', $admPag->classPagina);
        $homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
        $homepage->assign('temas', Temas\Temas::getArray());

        // obtém os items do menu
        include($admin_path . 'ler_menu.php');

        $template = 'admin/tema_edit.tpl';
    break;

    // edição do Tema
    case 'edTema': 
        // lê os elementos coloridos e os pares de cores
        $homepage->assign('variaveisRoot', Temas\VariaveisRoot::obterTodasDeTipo('color'));
        $paresCores[] = array('nomePaleta' => 'Pantone', 'paleta' => Temas\PaletasdeCor::getArray('Pantone'), 'size' => '180', 'fa_icon' => 'fa-swatchbook');
        $paresCores[] = array('nomePaleta' => 'Material', 'paleta' => Temas\PaletasdeCor::getArray('Material'), 'size' => '210', 'fa_icon' => 'fa-brands fa-google');
        $paresCores[] = array('nomePaleta' => 'RAL', 'paleta' => Temas\PaletasdeCor::getArray('RAL'), 'size' => '210', 'fa_icon' => 'fa-r');
        $homepage->assign('paresCores', $paresCores);

        $tema = new Temas\Temas($_idTema);
        $homepage->assign('rootVars', '');
        $homepage->assign('tituloPaginaAlternativo', $tema->nome . ' :: Edição');
        $homepage->assign('tituloTabelaAlternativo', $tema->nome . ' :: Edição');
        $homepage->assign('tema', array('nome' => $tema->nome, 'comentario' => $tema->comentario));
        $homepage->assign('classPagina', $admPag->classPagina);
        $homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);

        // obtém os items do menu
        include($admin_path . 'ler_menu.php');

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
        $homepage->assign('script2reload', 'admin/tema_edit.php?idTema=' . $_idTema);
        $homepage->assign('scriptMode', 'edTema');
        $template = 'admin/script_reload.tpl';
    break;

    // salvar um novo tema (chamado a partir do form de edição com tag <form> alterada quando $criarTema = true) 
    case 'crTema':
        $tema = new Temas\Temas(NULL);
        $tema->nome = (string) $_REQUEST['nome'];
        $tema->comentario = (string) $_REQUEST['comentario'];
        $_idTema = $tema->inserir();
        if (!$_idTema) 
        {
            prepararToast('warning', "Não foi possível criar o tema [" . $global_hpDB->real_escape_string($tema->nome) . "]");
            $homepage->assign('scriptMode', 'slTema');
        }
        else
        {
            if (isset($_REQUEST['temaBase'])) {
                $temaBaseCssFile = HOMEPAGE_PATH . 'temas/' . $_REQUEST['temaBase'] . '.css';
                $temaCssFile = HOMEPAGE_PATH . 'temas/' . $tema->nome . '.css';
                if (!copy($temaBaseCssFile, $temaCssFile))
                    prepararToast('error', 'Conseguir gravar o tema no banco de dados mas deu erro na criação do arquivo .css');
                else {
                    prepararToast('success', "Tema [" . $global_hpDB->real_escape_string($tema->nome) . "] criado com sucesso!");
                    $homepage->assign('idTema', $_idTema);
                    $homepage->assign('tema', $tema);
                }
            } else
                prepararToast('warning', 'Você não definiu qual o tema base para a criação do novo tema');
            $homepage->assign('scriptMode', 'edTema');
        }
        $homepage->assign('idTema', $_idTema);
        $homepage->assign('script2reload', 'admin/tema_edit.php?idtema=' . $_idTema);
        $template = 'admin/script_reload.tpl';
    break;

    // excluir um Tema (já foi exibido o form de confirmação).
    case 'exTema':
            $tema = new Temas\Temas($_idTema);
            if ($tema->excluir())
            {
                $temaCssFile = HOMEPAGE_PATH . 'temas/' . $tema->nome . '.css';
                if (!unlink($temaCssFile)) 
                    prepararToast('error', 'Entrada sobre o tema apagada na base de dados, mas não foi possível excluir o arquivo .css');
                else
                    prepararToast('success', "Tema [" . $global_hpDB->real_escape_string($tema->nome) . "] excluído com sucesso!");
                $homepage->assign('scriptMode', 'slTema');
                $homepage->assign('script2reload', 'admin/tema_select.php');
            }
            else
            {
                prepararToast('warning', "Não foi possível excluir o tema [" . $global_hpDB->real_escape_string($tema->nome) . "] na base de dados!");
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

if (!$criarTema)
    $homepage->assign('idTema', $_idTema);
$homepage->assign('criarTema', $criarTema);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->display($template);
?>
