<?php
//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
require_once(HOMEPAGE_PATH . 'common.php');

// este flag eu vou usar mais tarde (em grupo_edit_body.tpl para configurar a action do formul�rio).
$criarGrupo = false;

// garante que v�o aparecer todos os grupos
$_REQUEST['gr'] = 'all';

// verifica se passou p�gina. se houver, vai apresent�-la no form
if (isset($requests['id']) && $requests['id'] != '') 
{
	$_idPagina = $requests['id'];
}

// verifica se passou categoria. se houver, vai apresent�-la no form
if (isset($requests['idCat']) && $requests['idCat'] != '') 
{
	$_idCategoria = $requests['idCat'];
}

// localEcho($requests, "Requests:");

// verifica se passou grupo
if (isset($requests['idGrp']))
{
	$_idGrupo = $requests['idGrp'];
}

//
// se n�o passou nenhum mode, entra em modo de sele��o de grupos
if (!isset($requests['mode']))
{
	$requests['mode'] = 'slGrp';
}

switch ($requests['mode'])
{
	// pediu para voltar - apresenta a p�gina de estat�sticas
	case 'stats':
		$homepage->assign('script2reload', 'admin/estatisticas.php');
		$template = 'admin/script_reload.tpl';
	break;

	// edi��o do grupo
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

	// Atualiza o grupo atualmente em edi��o
	case 'svGrp':
		$grupo = new grupo($_idGrupo);
		$grupo->descricaoGrupo = (string) $requests['descricaoGrupo'];
		$grupo->idTipoGrupo = (string) $requests['idTipoGrupo'];
		$grupo->grupoRestrito = ( isset($requests['grupoRestrito']) ) ? 1 : 0;
		$grupo->restricaoGrupo = ( isset($requests['restricaoGrupo']) ) ? (string) $requests['restricaoGrupo'] : '';
		if ($grupo->atualizar()) 
		{
			$homepage->assign('msgAlerta', "Grupo [$grupo->descricaoGrupo] atualizado com sucesso!");
		}
		else
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel atualizar o grupo [$grupo->descricaoGrupo]!");
		}
		$homepage->assign('script2reload', 'admin/grupo_edit.php');
		$homepage->assign('scriptMode', 'edGrp');
		$template = 'admin/script_reload.tpl';
	break;

	// apresenta um form vazio para a cria��o de uma novo grupo. O flag $criarGrupo vai mudar o comportamento do 
	// template.
	case 'nwGrp':
		$criarGrupo = true;
		$template = 'admin/grupo_edit.tpl';
	break;

	// criar uma nova Grupo (chamado a partir do form de edi��o com tag <form> alterada quando $criarGrupo = true) 
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
			$homepage->assign('msgAlerta', "N�o foi poss�vel criar o grupo [$grupo->descricaoGrupo]!");
			$homepage->assign('scriptMode', 'slGrp');
		}
		else
		{
			$homepage->assign('msgAlerta', "Grupo [$grupo->descricaoGrupo] criado com sucesso!");
			$homepage->assign('scriptMode', 'edGrp');
		}
		$homepage->assign('script2reload', 'admin/grupo_edit.php');
		$template = 'admin/script_reload.tpl';
	break;

	// excluir esta grupo da base - exibe o form de confirma��o para voltar mais tarde no modo ExGrp
	case 'cfExGrp':
		$template = 'admin/delete_confirm.tpl';
	break;

	// excluir um grupo (j� foi exibido o form de confirma��o).
	case 'exGrp':
		switch ($requests['go'])
		{
			case $lang['sim']:
				$grupo = new grupo($_idGrupo);
				if ($grupo->excluir())
				{
					$homepage->assign('msgAlerta', "Grupo [$grupo->descricaoGrupo] exclu�do com sucesso!");
					$homepage->assign('scriptMode', 'slGrp');
				}
				else
				{
					$homepage->assign('msgAlerta', "N�o foi poss�vel excluir o grupo [$grupo->descricaoGrupo]!");
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
	
// le os cookies e passa para a p�gina a ser carregada.
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
			// l� a p�gina deste grupo.
			if (isset($_idPagina)) 
			{
				$pagina = new pagina($_idPagina);
				$homepage->assign('pagina', $pagina->getArray());
			} 
			else 
			{
				$homepage->assign('classPagina', 'admin');
			}

			// l� a categoria deste grupo.
			if (isset($_idCategoria)) 
			{
				$categoria = new categoria($_idCategoria);
				$homepage->assign('categoria', $categoria->getArray());
			}

			// l� o grupo
			$grupo = new grupo($_idGrupo);
			$homepage->assign('grupo', $grupo->getArray());

			$homepage->assign('tituloPaginaAlternativo', $grupo->descricaoGrupo . ' :: Edi&ccedil;&atilde;o');
			$homepage->assign('tituloTabelaAlternativo', $grupo->descricaoGrupo . ' :: Edi&ccedil;&atilde;o');

			// l� os elementos deste grupo
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
		// inicializa os campos para cria��o de uma nova p�gina
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
