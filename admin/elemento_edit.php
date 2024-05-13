	<?php

	//
	// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
	// Carregar apenas uma vez.
	define('HOMEPAGE_PATH', getcwd() . '/../');
	define('RELATIVE_PATH', './../');
	include_once(HOMEPAGE_PATH . 'common.php');

	// se n�o foi passado nenhum grupo, morre.
	if (isset($requests['idGrp']))
	{
		$_idGrupo = $requests['idGrp'];
	}
	else
	{
		die("n�o posso prosseguir sem um grupo selecionado!");
	}

	// l� o grupo deste elemento
	$grupo = new grupo($_idGrupo);
	$homepage->assign('grupo', $grupo->getArray());

	// se tenho o id do elemento a editar/excluir/salvar, l� e passa para o template
	if (isset($requests['idElm']) && $requests['idElm'] != '0')
	{
		$_idElm = $requests['idElm'];
		$elemento = $grupo->elementoDeCodigo($_idElm);
		$homepage->assign('elemento', $elemento->getArray());
	}

	// se n�o passou nenhum mode, entra em modo de sele��o de elementos
	if (!isset($requests['mode']))
	{
		$requests['mode'] = 'slElm';
	}

	// se pediu para editar um elemento, verifica-se qual o tipo de elemento e ajusta-se o mode
	if ($requests['mode'] == 'edElm') 
	{
		if (isset($elemento))
		{
			switch ($elemento->tipoElemento)
			{
				case ELEMENTO_LINK:
					$requests['mode'] = 'edLnk';
				break;

				case ELEMENTO_FORM:
					$requests['mode'] = 'edFrm';
				break;

				case ELEMENTO_SEPARADOR:
					$requests['mode'] = 'edSrp';
				break;

				case ELEMENTO_IMAGEM:
					$requests['mode'] = 'edImg';
				break;

				case ELEMENTO_RSSFEED:
					$requests['mode'] = 'edRss';
				break;

				case ELEMENTO_TEMPLATE:
					$requests['mode'] = 'edTpt';
				break;

				default:
					$requests['mode'] = 'edLnk';
				break;

			}
		}
	}

	// para saber o que vou fazer...
	$criarElemento = false;

	switch ($requests['mode'])
	{

		// confirmar exclus�o do elemento - volta mais tarde no modo ExElm
		case 'cfExElm': 
			$template = 'admin/delete_confirm.tpl';
		break;

		// form de exclus�o j� foi exibido. verifica resposta do usu�rio
		case 'exElm':
			switch ($requests['go'])
			{
				case $lang['sim']:
					if ($elemento->excluir())
					{
						$homepage->assign('msgAlerta', "Elemento [$elemento->descricaoElemento] exclu�do com sucesso!");
					}
					else
					{
						$homepage->assign('msgAlerta', "N�o foi poss�vel excluir o elemento [$elemento->descricaoElemento]!");
					}
				break;

				case $lang['nao']:
				break;
			}
			$template = 'admin/window_close.tpl';
		break;

		/*------------------------------------------------------------------------+
		|                                                                         |
		|  Ger�ncia de elementos do tipo 'Link'.								  |
		|  fun��es implementadas: 												  |
		| 	nwLnk - Apresenta um formul�rio para inclus�o de um novo link		  |
		|   crLnk - Salva um link na base de dados, finalizando sua inclus�o	  |
		|   edLnk - Apresenta um formul�rio para edi��o de um link j� existente	  |
		|   svLnk - Atualizar um link na base de dados ap�s a edi��o			  |
		|                                                                         |
		|------------------------------------------------------------------------*/
		// novo Link
		case 'nwLnk':
			$criarElemento = true;
			$homepage->assign('elemento', array(
				'idGrupo' => $grupo->idGrupo,
				'descricaoLink' => $lang['hp_links_descricaoLink'],
				'linkURL' => $lang['hp_links_linkURL'],
				'dicaLink' => $lang['hp_links_dicaLink'],
				'localLink' => 0,
				'urlElementoSSL' => 0,
				'urlElementoSVN' => 0,
				'targetLink' => ''));
			$template = 'admin/link_edit.tpl';
		break;

		// criar Link
		case 'crLnk':
			$link = new wLink(NULL);
			$link->descricaoLink = $requests['descricaoLink'];
			$link->linkURL = $requests['linkURL'];
			$link->dicaLink = $requests['dicaLink'];
			$link->idGrupo = $requests['idGrp'];
			$link->posGrupo = $grupo->numeroElementos() + 1;
			$link->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
			$link->urlElementoSSL = ( isset($requests['urlElementoSSL']) ) ? 1 : 0 ;
			$link->urlElementoSVN = ( isset($requests['urlElementoSVN']) ) ? 1 : 0 ;
			$link->targetLink = $requests['targetLink'];
			$_idElm = $link->inserir();
			if (!$_idElm)
			{
				$homepage->assign('msgAlerta', "N�o foi poss�vel criar o link [$link->descricaoLink]!");
			}
			else
			{
				$homepage->assign('msgAlerta', "Link [" . $this->hpDB->real_escape_string ($link->descricaoLink) . "] criado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	// edi��o do Link
	case 'edLnk':
		$link = new wLink($_idElm);	
		$homepage->assign('elemento', $link->getArray());
		$template = 'admin/link_edit.tpl';
	break;

	// salva o link editado
	case 'svLnk':
		$link = new wLink($_idElm);
		$link->descricaoLink = $requests['descricaoLink'];
		$link->linkURL = $requests['linkURL'];
		$link->dicaLink = $requests['dicaLink'];
		$link->idGrupo = $requests['idGrp'];
		$link->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
		$link->urlElementoSSL = ( isset($requests['urlElementoSSL']) ) ? 1 : 0 ;
		$link->urlElementoSVN = ( isset($requests['urlElementoSVN']) ) ? 1 : 0 ;
		$link->targetLink = $requests['targetLink'];
		if (!$link->atualizar())
		{
			$homepage->assign('msgAlerta', $global_hpDB->real_escape_string("N�o foi poss�vel atualizar o link [$link->descricaoLink]!") );
		}
		else
		{
			$homepage->assign('msgAlerta', "Link [" . $global_hpDB->real_escape_string ($link->descricaoLink) . "] atualizado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;
	
	/*------------------------------------------------------------------------+
	|                                                                         |
	|  Ger�ncia de elementos do tipo 'Form'.								  |
	|  fun��es implementadas: 												  |
	| 	nwFrm - Apresenta um formul�rio para inclus�o de um novo form		  |
	|   crFrm - Salva um form na base de dados, finalizando sua inclus�o	  |
	|   edFrm - Apresenta um formul�rio para edi��o de um form j� existente	  |
	|   svFrm - Atualizar um form na base de dados ap�s a edi��o			  |
	|                                                                         |
	|------------------------------------------------------------------------*/
	// novo Form
	case 'nwFrm':
		$criarElemento = true;
		$homepage->assign('elemento', array(
			'idGrupo' => $grupo->idGrupo,
			'nomeForm' => $lang['hp_forms_nomeForm'],
			'acao' => $lang['hp_forms_acao'],
			'nomeCampo' => $lang['hp_forms_nomeCampo'],
			'tamanhoCampo' => $lang['hp_forms_tamanhoCampo'],
			'descricaoForm' => $lang['hp_forms_descricaoForm']));
		$template = 'admin/form_edit.tpl';
	break;

	// criar Form
	case 'crFrm':
		$form = new wForm(NULL);
		$form->idGrupo = $requests['idGrp'];
		$form->posGrupo = $grupo->numeroElementos() + 1;
		$form->nomeForm = $requests['nomeForm'];
		$form->acao = $requests['acao'];
		$form->tamanhoCampo = $requests['tamanhoCampo'];
		$form->nomeCampo = $requests['nomeCampo'];
		$form->descricaoForm = $requests['descricaoForm'];
		$_idElm = $form->inserir();
		if (!$_idElm)
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel criar o form [$form->descricaoForm]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "Form [$form->descricaoForm] criado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	// edi��o do Form
	case 'edFrm':
		$form = new wForm($_idElm);	
		$homepage->assign('elemento', $form->getArray());
		$template = 'admin/form_edit.tpl';
	break;

	// salva o form editado
	case 'svFrm':
		$form = new wForm($_idElm);
		$form->idGrupo = $requests['idGrp'];
		$form->nomeForm = $requests['nomeForm'];
		$form->acao = $requests['acao'];
		$form->tamanhoCampo = $requests['tamanhoCampo'];
		$form->nomeCampo = $requests['nomeCampo'];
		$form->descricaoForm = $requests['descricaoForm'];
		if (!$form->atualizar())
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel atualizar o form [$form->descricaoForm!]");
		}
		else
		{
			$homepage->assign('msgAlerta', "Form [$form->descricaoForm] atualizado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;
	
	/*------------------------------------------------------------------------------+
	|		                                                                        |
	|  Ger�ncia de elementos do tipo 'Separador'.							  		|
	|  fun��es implementadas: 												  		|
	| 	nwSrp - Apresenta um formul�rio para inclus�o de um novo Separador	  		|
	|   crSrp - Salva um separadorna base de dados, finalizando sua inclus�o  		|
	|   edSrp - Apresenta um formul�rio para edi��o de um separador j� existente	|
	|   svSrp - Atualizar um separador na base de dados ap�s a edi��o			  	|
	|                                                                         		|
	+------------------------------------------------------------------------------*/
	// novo Separador
	case 'nwSrp':
		$criarElemento = true;
		$homepage->assign('elemento', array(
			'idGrupo' => $grupo->idGrupo,
			'descricaoSeparador' => $lang['hp_separadores_descricaoSeparador'],
			'breakBefore' => $lang['hp_separadores_breakBefore']));
		$template = 'admin/separador_edit.tpl';
	break;

	// criar Separador
	case 'crSrp':
		$separador = new wSeparador(NULL);
		$separador->idGrupo = $requests['idGrp'];
		$separador->posGrupo = $grupo->numeroElementos() + 1;
		$separador->descricaoSeparador = $requests['descricaoSeparador'];
		$separador->breakBefore = ( isset($requests['breakBefore']) ) ? 1 : 0 ;
		$_idElm = $separador->inserir();
		if (!$_idElm)
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel criar o separador [$separador->descricaoSeparador]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "Separador [$separador->descricaoSeparador] criado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	// edi��o do Separador
	case 'edSrp':
		$separador = new wSeparador($_idElm);	
		$homepage->assign('elemento', $separador->getArray());
		$template = 'admin/separador_edit.tpl';
	break;

	// salva o separador editado
	case 'svSrp':
		$separador = new wSeparador($_idElm);	
		$separador->idGrupo = $requests['idGrp'];
		$separador->descricaoSeparador = $requests['descricaoSeparador'];
		$separador->breakBefore = ( isset($requests['breakBefore']) ) ? 1 : 0 ;
		if (!$separador->atualizar())
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel atualizar o separador [$separador->descricaoSeparador]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "Separador [$separador->descricaoSeparador] atualizado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	/*------------------------------------------------------------------------------+
	|		                                                                        |
	|  Ger�ncia de elementos do tipo 'Imagem'.							  			|
	|  fun��es implementadas: 												  		|
	| 	nwImg - Apresenta um formul�rio para inclus�o de uma nova Imagem			|
	|   crImg - Salva uma imagem na base de dados, finalizando sua inclus�o  		|
	|   edImg - Apresenta um formul�rio para edi��o de uma imagem j� existente		|
	|   svImg - Atualizar uma imagem na base de dados ap�s a edi��o			  		|
	|                                                                         		|
	+------------------------------------------------------------------------------*/
	// novo Imagem
	case 'nwImg':
		$criarElemento = true;
		$homepage->assign('elemento', array(
			'idGrupo' => $grupo->idGrupo,
			'descricaoImagem' => $lang['hp_imagens_descricaoImagem'],
			'urlImagem' => $lang['hp_imagens_urlImagem'],
			'localLink' => $lang['hp_imagens_localLink']));
		$template = 'admin/imagem_edit.tpl';
	break;

	// criar Imagem
	case 'crImg':
		$imagem = new wImagem(NULL);
		$imagem->idGrupo = $requests['idGrp'];
		$imagem->posGrupo = $grupo->numeroElementos() + 1;
		$imagem->descricaoImagem = $requests['descricaoImagem'];
		$imagem->urlImagem = $requests['urlImagem'];
		$imagem->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
		$_idElm = $imagem->inserir();
		if (!$_idElm)
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel criar a imagem [$imagem->descricaoImagem]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "Imagem [$imagem->descricaoImagem] criado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	// edi��o da Imagem
	case 'edImg':
		$imagem = new wImagem($_idElm);	
		$homepage->assign('elemento', $imagem->getArray());
		$template = 'admin/imagem_edit.tpl';
	break;

	// salva a imagem editada
	case 'svImg':
		$imagem = new wImagem($_idElm);	
		$imagem->idGrupo = $requests['idGrp'];
		$imagem->descricaoImagem = $requests['descricaoImagem'];
		$imagem->urlImagem = $requests['urlImagem'];
		$imagem->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
		if (!$imagem->atualizar())
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel atualizar a imagem [$imagem->descricaoImagem]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "Imagem [$imagem->descricaoImagem] atualizado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	/*------------------------------------------------------------------------------+
	|		                                                                        |
	|  Ger�ncia de elementos do tipo 'RssFeed'.							  			|
	|  fun��es implementadas: 												  		|
	| 	nwRss - Apresenta um formul�rio para inclus�o de um novo RssFeed			|
	|   crRss - Salva um rssfeed na base de dados, finalizando sua inclus�o  		|
	|   edRss - Apresenta um formul�rio para edi��o de um rssfeed j� existente		|
	|   svRss - Atualizar um rssfeed na base de dados ap�s a edi��o			  		|
	|                                                                         		|
	+------------------------------------------------------------------------------*/
	// novo RssFeed
	case 'nwRss':
		$criarElemento = true;
		$homepage->assign('elemento', array(
			'idGrupo' => $grupo->idGrupo,
			'rssURL' => $lang['hp_rssfeeds_rssURL'],
			'rssItemNum' => $lang['hp_rssfeeds_rssItemNum']));
		$template = 'admin/rssfeed_edit.tpl';
	break;

	// criar RssFeed
	case 'crRss':
		$rssfeed = new wRssFeed(NULL);
		$rssfeed->idGrupo = $requests['idGrp'];
		$rssfeed->posGrupo = $grupo->numeroElementos() + 1;
		$rssfeed->rssURL = $requests['rssURL'];
		$rssfeed->rssItemNum = $requests['rssItemNum'];
		$_idElm = $rssfeed->inserir();
		if (!$_idElm)
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel criar o rssfeed [$rssfeed->rssURL]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "RssFeed [$rssfeed->rssURL] criado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	// edi��o do RssFeed
	case 'edRss':
		$rssfeed = new wRssFeed($_idElm);	
		$homepage->assign('elemento', $rssfeed->getArray());
		$template = 'admin/rssfeed_edit.tpl';
	break;

	// salva o rssfeed editada
	case 'svRss':
		$rssfeed = new wRssFeed($_idElm);	
		$rssfeed->idGrupo = $requests['idGrp'];
		$rssfeed->rssURL = $requests['rssURL'];
		$rssfeed->rssItemNum = $requests['rssItemNum'];
		if (!$rssfeed->atualizar())
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel atualizar o rssfeed [$rssfeed->rssURL]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "RssFeed [$rssfeed->rssURL] atualizado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	/*------------------------------------------------------------------------------+
	|		                                                                        |
	|  Ger�ncia de elementos do tipo 'Template'.							  		|
	|  fun��es implementadas: 												  		|
	| 	nwTpt - Apresenta um formul�rio para inclus�o de um novo Template			|
	|   crTpt - Salva um Template na base de dados, finalizando sua inclus�o  		|
	|   edTpt - Apresenta um formul�rio para edi��o de um Template j� existente		|
	|   svTpt - Atualizar um Template na base de dados ap�s a edi��o			  	|
	|                                                                         		|
	+------------------------------------------------------------------------------*/
	// novo Template
	case 'nwTpt':
		$criarElemento = true;
		$homepage->assign('elemento', array(
			'idGrupo' => $grupo->idGrupo,
			'descricaoTemplate' => $lang['hp_templates_descricaoTemplate'],
			'nomeTemplate' => $lang['hp_templates_nomeTemplate']));
		$template = 'admin/template_edit.tpl';
	break;

	// criar Template
	case 'crTpt':
		$Template = new wTemplate(NULL);
		$Template->idGrupo = $requests['idGrp'];
		$Template->posGrupo = $grupo->numeroElementos() + 1;
		$Template->descricaoTemplate = $requests['descricaoTemplate'];
		$Template->nomeTemplate = $requests['nomeTemplate'];
		$_idElm = $Template->inserir();
		if (!$_idElm)
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel criar o Template [$Template->descricaoTemplate]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "Template [$Template->descricaoTemplate] criado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	// edi��o do Template
	case 'edTpt':
		$Template = new wTemplate($_idElm);	
		$homepage->assign('elemento', $Template->getArray());
		$template = 'admin/template_edit.tpl';
	break;

	// salva o Template editada
	case 'svTpt':
		$Template = new wTemplate($_idElm);	
		$Template->idGrupo = $requests['idGrp'];
		$Template->descricaoTemplate = $requests['descricaoTemplate'];
		$Template->urlTemplate = $requests['urlTemplate'];
		$Template->localLink = ( isset($requests['localLink']) ) ? 1 : 0 ;
		if (!$Template->atualizar())
		{
			$homepage->assign('msgAlerta', "N�o foi poss�vel atualizar o Template [$Template->descricaoTemplate]!");
		}
		else
		{
			$homepage->assign('msgAlerta', "Template [$Template->descricaoTemplate] atualizado com sucesso!");
		}
		$template = 'admin/window_close.tpl';
	break;

	// cancela uma edi��o
	default:
		$template = 'admin/window_close.tpl';
	break;
}
	
$homepage->assign('displayImagemTitulo', '1');

switch ($template)
{
	case 'admin/link_edit.tpl':
		if ($criarElemento)
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarLink']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novoLink'] . ' ::');
		}
		else
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaCriarLink']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoLink . ' ::');
		}
	break;

	case 'admin/form_edit.tpl':
		if ($criarElemento)
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarForm']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novoForm'] . ' ::');
		}
		else
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaCriarForm']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoForm . ' ::');
		}
	break;
	
	case 'admin/separador_edit.tpl':
		if ($criarElemento)
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarSeparador']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novoSeparador'] . ' ::');
		}
		else
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaCriarSeparador']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoSeparador. ' ::');
		}
	break;
	
	case 'admin/imagem_edit.tpl':
		if ($criarElemento)
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarImagem']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novaImagem'] . ' ::');
		}
		else
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaCriarImagem']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoImagem . ' ::');
		}
	break;

	case 'admin/rssfeed_edit.tpl':
		if ($criarElemento)
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarRssFeed']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novoRssFeed'] . ' ::');
		}
		else
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaCriarRssFeed']);
			$homepage->assign('tituloTabelaAlternativo', ':: Editar Rss Feed ::');
		}
	break;

	case 'admin/template_edit.tpl':
		if ($criarElemento)
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarTemplate']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novoTemplate'] . ' ::');
		}
		else
		{
			$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaCriarTemplate']);
			$homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoElemento . '::');
		}
	break;

	case 'admin/delete_confirm.tpl':
		$homepage->assign('idGrupo', $_idGrupo);
		$homepage->assign('idElm', $_idElm);
		$homepage->assign('tituloTabelaAlternativo', $lang['tituloTabelaConfirmarExclusao']);
		$homepage->assign('scriptMode', 'exElm');
		$homepage->assign('script2call', 'admin/elemento_edit.php');
		$homepage->assign('classPagina', 'admin');

		switch ($elemento->tipoElemento)
		{
			case ELEMENTO_LINK:
				$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoLink']);
				$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoLink']);
				$homepage->assign('deleteConfirmDescricao', $elemento->descricaoLink);
			break;

			case ELEMENTO_FORM:
				$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoForm']);
				$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoForm']);
				$homepage->assign('deleteConfirmDescricao', $elemento->descricaoForm);
			break;

			case ELEMENTO_SEPARADOR:
				$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoSeparador']);
				$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoSeparador']);
				$homepage->assign('deleteConfirmDescricao', $elemento->descricaoSeparador);
			break;

			case ELEMENTO_IMAGEM:
				$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoImagem']);
				$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoImagem']);
				$homepage->assign('deleteConfirmDescricao', $elemento->descricaoImagem);
			break;
			
			case ELEMENTO_RSSFEED:
				$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoRssFeed']);
				$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoRssFeed']);
				$homepage->assign('deleteConfirmDescricao', $elemento->rssURL);
			break;
			
			case ELEMENTO_TEMPLATE:
				$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaConfirmarExclusaoTemplate']);
				$homepage->assign('deleteConfirmTituloTabela', $lang['confirmarExclusaoTemplate']);
				$homepage->assign('deleteConfirmDescricao', $elemento->descricaoTemplate);
			break;
		}

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

$homepage->assign('relativePATH', RELATIVE_PATH);
$homepage->assign('criarElemento', $criarElemento);
$homepage->assign('imagesPATH', $images_path);
$homepage->assign('classPagina', 'admin');
$homepage->display($template);

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 

?>
