<?php
//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

// este flag eu vou usar mais tarde (em grupo_edit_body.tpl para configurar a action do formulário).
$criarGrupo = false;

// garante que vão aparecer todos os grupos
$_REQUEST['gr'] = 'all';

// verifica se passou página. se houver, vai apresentá-la no form
if (isset($requests['id']) && $requests['id'] != '') 
    $_idPagina = $requests['id'];

// verifica se passou categoria. se houver, vai apresentá-la no form
if (isset($requests['idCat']) && $requests['idCat'] != '') 
    $_idCategoria = $requests['idCat'];

// localEcho($requests, "Requests:");

// verifica se passou grupo
if (isset($requests['idGrp']))
    $_idGrupo = $requests['idGrp'];

//
// se não passou nenhum mode, entra em modo de seleção de grupos
if (!isset($requests['mode']))
    $requests['mode'] = 'slGrp';

switch ($requests['mode'])
{
    // pediu para voltar - apresenta a página de estatísticas
    case 'stats':
        $homepage->assign('script2reload', 'admin/estatisticas.php');
        $template = 'admin/script_reload.tpl';
    break;

    // edição do grupo
    case 'edGrp': 
        $template = 'admin/grupo_edit.tpl';
    break;
        
    // deslocar elemento para cima
    case 'upElm':
        $grupo = new grupo($_idGrupo);
        $grupo->deslocarElementoParaCima($requests['idElm']);
        $homepage->assign('script2reload', 'admin/grupo_edit.php');
        $homepage->assign('scriptMode', 'edGrp');
        $template = 'admin/script_reload.tpl';
    break;
    
    // deslocar elemento para baixo
    case 'downElm':
        $grupo = new grupo($_idGrupo);
        $grupo->deslocarElementoParaBaixo($requests['idElm']);
        $homepage->assign('script2reload', 'admin/grupo_edit.php');
        $homepage->assign('scriptMode', 'edGrp');
        $template = 'admin/script_reload.tpl';
    break;

    // excluir um elemento do grupo
    case 'rmElm':
        $grupo = new grupo($_idGrupo);
        $grupo->excluirElemento($requests['idGrp'], $requests['posGrp']);
        $homepage->assign('script2reload', 'admin/grupo_edit.php');
        $homepage->assign('scriptMode', 'edGrp');
        $template = 'admin/script_reload.tpl';
    break;

    // Atualiza o grupo atualmente em edição
    case 'svGrp':
        $grupo = new grupo($_idGrupo);
        $grupo->descricaoGrupo = (string) $requests['descricaoGrupo'];
        $grupo->idTipoGrupo = (string) $requests['idTipoGrupo'];
        $grupo->grupoRestrito = ( isset($requests['grupoRestrito']) ) ? 1 : 0;
        $grupo->restricaoGrupo = ( isset($requests['restricaoGrupo']) ) ? (string) $requests['restricaoGrupo'] : '';
        try {
            if ($grupo->atualizar()) 
                prepare_msgAlerta("success", "Grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "] atualizado!");
            else
                prepare_msgAlerta('warning', "Não foi possível atualizar o grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "]!");
        } catch (Exception $e) {
            prepare_msgAlerta('error', "Error ao atualizar o grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "]! "  . $e->message);
        }
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
        $grupo = new grupo(NULL);
        $grupo->descricaoGrupo = (string) $requests['descricaoGrupo'];
        $grupo->idTipoGrupo = (string) $requests['idTipoGrupo'];
        $grupo->grupoRestrito = ( isset($requests['grupoRestrita']) ) ? 1 : 0;
        $grupo->restricaoGrupo = ( isset($requests['restricaoGrupo']) ) ? (string) $requests['restricaoGrupo'] : '';
        $_idGrupo = $grupo->inserir();
        if (!$_idGrupo) 
        {
            prepare_msgAlerta('warning', "Não foi possível criar o grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "]!");
            $homepage->assign('scriptMode', 'slGrp');
        }
        else
        {
            prepare_msgAlerta('success', "Grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "] criado!");
            $homepage->assign('scriptMode', 'edGrp');
        }
        $homepage->assign('script2reload', 'admin/grupo_edit.php');
        $template = 'admin/script_reload.tpl';
    break;

    // excluir esta grupo da base - exibe o form de confirmação para voltar mais tarde no modo ExGrp
    case 'cfExGrp':
        $template = 'admin/delete_confirm.tpl';
    break;

    // excluir um grupo (já foi exibido o form de confirmação).
    case 'exGrp':
        switch ($requests['go'])
        {
            case $lang['sim']:
                $grupo = new grupo($_idGrupo);
                if ($grupo->excluir())
                {
                    prepare_msgAlerta('success', "Grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "] excluído!");
                    $homepage->assign('scriptMode', 'slGrp');
                }
                else
                {
                    prepare_msgAlerta('warning', "Não foi possível excluir o grupo [" . $global_hpDB->real_escape_string($grupo->descricaoGrupo) . "]!");
                    $homepage->assign('scriptMode', 'edGrp');
                }
            break;

            case $lang['nao']:
                $homepage->assign('scriptMode', 'edGrp');
            break;
        }
        $homepage->assign('script2reload', 'admin/grupo_edit.php');
        $template = 'admin/script_reload.tpl';
    break;

    case 'slGrp':
    default:
        $template = 'admin/grupo_select.tpl';
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

switch ($template)
{
    case 'admin/grupo_edit.tpl':
        if (!$criarGrupo) 
        {
            // lê a página deste grupo.
            if (isset($_idPagina)) 
            {
                $pagina = new pagina($_idPagina);
                $homepage->assign('pagina', $pagina->getArray());
            } 
            else 
            {
                $homepage->assign('classPagina', 'admin');
            }

            // lê a categoria deste grupo.
            if (isset($_idCategoria)) 
            {
                $categoria = new categoria($_idCategoria);
                $homepage->assign('categoria', $categoria->getArray());
            }

            // lê o grupo
            $grupo = new grupo($_idGrupo);
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
            $homepage->assign('tiposElementos', tiposElementos::getArray());
            $homepage->assign('tiposGrupos', tiposGrupos::getArray());
        }
        else
        {
        // inicializa os campos para criação de uma nova página
            $homepage->assign('grupo', array(
                    'descricaoGrupo' => $lang['hp_grupos_DescricaoGrupo'],
                    'idTipoGrupo' => 0,
                    'grupoRestrito' => 0,
                    'restricaoGrupo' => ''));
                    
            $homepage->assign('tituloPaginaAlternativo', ' :: Cria&ccedil;&atilde;o de Grupo');
            $homepage->assign('tituloTabelaAlternativo', ' :: Novo Grupo :: ');
            $homepage->assign('tiposGrupos', tiposGrupos::getArray());
            $homepage->assign('classPagina', 'admin');
        }
    break;

    case 'admin/grupo_select.tpl':
        $homepage->assign('grupos', grupo::getGrupos());
        $homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaSelecionarGrupo']);
        $homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaSelecionarGrupo']);
        $homepage->assign('classPagina', 'admin');
    break;

    case 'admin/delete_confirm.tpl':
        $grupo = new grupo($_idGrupo);
        $homepage->assign('idGrupo', $_idGrupo);
        $homepage->assign('descricaoGrupo', $grupo->descricaoGrupo);
        $homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoGrupo']);
        $homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaConfirmarExclusao']);
        $homepage->assign('scriptMode', 'exGrp');
        $homepage->assign('script2call', 'admin/grupo_edit.php');
        $homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoGrupo']);
        $homepage->assign('deleteConfirmDescricao', $grupo->descricaoGrupo);
        $homepage->assign('classPagina', 'admin');
    break;

    case 'admin/script_reload.tpl':
        if (isset($_idPagina))
        {
            $homepage->assign('id', $_idCategoria);
        }
        if (isset($_idCategoria))
        {
            $homepage->assign('idCategoria', $_idCategoria);
        }
        if (isset($_idGrupo))
        {
            $homepage->assign('idGrupo', $_idGrupo);
        }
    break;

}

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('criarGrupo', $criarGrupo);
$homepage->assign('imagesPATH', $images_path);
$homepage->display($template);

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
