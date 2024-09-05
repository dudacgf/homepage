<?php
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// este flag eu vou usar mais tarde (em grupo_edit_body.tpl para configurar a action do formulário).
$criarGrupo = false;

// garante que vão aparecer todos os grupos
$_REQUEST['gr'] = 'all';

// verifica se passou grupo
if (isset($requests['idGrp']))
    $_idGrupo = $requests['idGrp'];

switch ($requests['mode'])
{
    // edição do grupo
    case 'edGrp': 
        $template = 'admin/grupo_edit.tpl';
    break;
        
    // Atualiza o grupo atualmente em edição
    case 'svGrp':
        $grupo = new Pagina\Grupo($_idGrupo);
        $grupo->descricaoGrupo = (string) $requests['descricaoGrupo'];
        $grupo->idTipoGrupo = (string) $requests['idTipoGrupo'];
        $grupo->grupoRestrito = ( isset($requests['grupoRestrito']) ) ? 1 : 0;
        $grupo->restricaoGrupo = ( isset($requests['restricaoGrupo']) ) ? (string) $requests['restricaoGrupo'] : '';
        try {
            if ($grupo->atualizar()) 
                prepararToast("success", "Grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "] atualizado!");
            else
                prepararToast('warning', "Não foi possível atualizar o grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "]!");
        } catch (Exception $e) {
            prepararToast('error', "Error ao atualizar o grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "]! "  . $e->message);
        }
        $homepage->assign('idGrupo', $_idGrupo);
        $homepage->assign('script2reload', 'admin/grupo_edit.php');
        $homepage->assign('scriptMode', 'edGrp');
        $template = 'admin/script_reload.tpl';
    break;

    // apresenta um form vazio para a criação de uma novo grupo. O flag $criarGrupo vai mudar o comportamento do 
    // template.
    case 'nwGrp':
        $criarGrupo = true;
        $template = 'admin/grupo_edit.tpl';
    break;

    // criar uma nova Grupo (chamado a partir do form de edição com tag <form> alterada quando $criarGrupo = true) 
    case 'crGrp':
        $homepage->assign('requests', $requests);
        $grupo = new Pagina\Grupo(NULL);
        $grupo->descricaoGrupo = (string) $requests['descricaoGrupo'];
        $grupo->idTipoGrupo = (string) $requests['idTipoGrupo'];
        $grupo->grupoRestrito = ( isset($requests['grupoRestrita']) ) ? 1 : 0;
        $grupo->restricaoGrupo = ( isset($requests['restricaoGrupo']) ) ? (string) $requests['restricaoGrupo'] : '';
        $_idGrupo = $grupo->inserir();
        if (!$_idGrupo) 
        {
            prepararToast('warning', "Não foi possível criar o grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "]!");
            $homepage->assign('scriptMode', 'slGrp');
        }
        else
        {
            prepararToast('success', "Grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "] criado!");
            $homepage->assign('scriptMode', 'edGrp');
        }
        $homepage->assign('idGrupo', $_idGrupo);
        $homepage->assign('script2reload', 'admin/grupo_edit.php');
        $template = 'admin/script_reload.tpl';
    break;

    // excluir um grupo
    case 'excluirGrupo':
        $grupo = new Pagina\Grupo($_idGrupo);
        if ($grupo->excluir())
        {
            prepararToast('success', "Grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "] excluído!");
            $homepage->assign('script2reload', 'admin/grupo_select.php');
        }
        else
        {
            prepararToast('warning', "Não foi possível excluir o grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "]!");
            $homepage->assign('script2reload', 'admin/grupo_edit.php');
            $homepage->assign('scriptMode', 'edGrp');
        }
        $template = 'admin/script_reload.tpl';
    break;

    case 'slGrp':
    default:
        $homepage->assign('script2reload', 'admin/grupo_select.php');
        $template = 'admin/script_reload.tpl';
    break;
}
    
// obtém a página administrativa
$admPag = new Pagina\Pagina(ID_ADM_PAG);

if ($template == 'admin/grupo_edit.tpl') {
    $homepage->assign('classPagina', $admPag->classPagina);
    $homepage->assign('tiposGrupos', Pagina\TiposGrupos::getArray());

    if (!$criarGrupo) {
        // lê o grupo
        $grupo = new Pagina\Grupo($_idGrupo);
        $homepage->assign('grupo', $grupo->getArray());

        $homepage->assign('tituloPaginaAlternativo', $grupo->descricaoGrupo . ' :: Edi&ccedil;&atilde;o');
        $homepage->assign('tituloTabelaAlternativo', $grupo->descricaoGrupo . ' :: Edi&ccedil;&atilde;o');

        // lê os elementos deste grupo
        $grupo->lerElementos();
        $elementos[] = '';
        foreach ($grupo->elementos as $elemento)
        {
            $elementos[] = $elemento->getArray();
        }
        array_shift($elementos);
        $homepage->assign('elementos', $elementos);
        $homepage->assign('tiposElementos', Pagina\TiposElementos::getArray());
    } else {
        // inicializa os campos para criação de uma nova página
        $homepage->assign('grupo', array(
                'descricaoGrupo' => '',
                'idTipoGrupo' => 0,
                'grupoRestrito' => 0,
                'restricaoGrupo' => ''));
                
        $homepage->assign('tituloPaginaAlternativo', ' :: Cria&ccedil;&atilde;o de Grupo');
        $homepage->assign('tituloTabelaAlternativo', ' :: Novo Grupo :: ');
    }
}

// obtém os items do menu
include($admin_path . 'ler_menu.php');

// le os cookies e passa para a página a ser carregada.
$homepage->assign('rootVars', '');
$homepage->assign('displayImagemTitulo', $admPag->displayImagemTitulo);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('criarGrupo', $criarGrupo);
$homepage->display($template);
?>
