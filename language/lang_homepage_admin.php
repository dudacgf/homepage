<?php
/*
 * Created on 02/04/2006
 *
 * contém os strings que serão usados nos diálogos de criação dos elementos das páginas.
 * 
 * (c) ecgf - abr/2006
 */

//
//
// Atributos de cada tipo de objeto - utilizados nos diálogos de edição/criação.
//
//

// atributos da página
$lang['hp_paginas_FormTitulo'] = 'Páginas';
$lang['hp_paginas_idPagina'] = 'Id desta página';
$lang['hp_paginas_TituloPagina'] = 'Título da página';
$lang['hp_paginas_TituloTabela'] = 'Título da tabela';
$lang['hp_paginas_classPagina'] = 'Classe da Página';
$lang['hp_paginas_exibirdotdot'] = 'Exibir: ';
$lang['hp_paginas_displayGoogle'] = 'form do Google';
$lang['hp_paginas_displayFindaMap'] = 'form do Find a Map';
$lang['hp_paginas_displayFortune'] = 'biscoitinho da sorte';
$lang['hp_paginas_displayImagemTitulo'] = 'Imagem no Título';
$lang['hp_paginas_displaySelectColor'] = 'Formulário de cores';

// atributos de uma categoria (coluna)
$lang['hp_categorias_DescricaoCategoria'] = 'Título da categoria (coluna)';
$lang['hp_categorias_CategoriaRestrita'] = 'Marque este quadro caso a categoria seja restrita';
$lang['hp_categorias_RestricaoCategoria'] = 'Indique a restrição';
$lang['hp_categorias_Label_Restricao'] = 'Categoria restrita';

// atributos de um grupo
$lang['hp_grupos_DescricaoGrupo'] = 'Nome do Grupo';
$lang['hp_grupos_TipoGrupo'] = 'Tipo de Grupo';
$lang['hp_grupos_GrupoRestrito'] = 'Marque este quadro caso o grupo seja restrito';
$lang['hp_grupos_RestricaoGrupo'] = 'Indique a restrição';
$lang['hp_grupos_Label_Restricao'] = 'Grupo restrito';

// 
$lang['hp_elementos_Descricao'] = 'nome do Elemento';

// atributos de um elemento tipo link
$lang['hp_links_FormTitulo'] = 'Links';
$lang['hp_links_idLink'] = 'Id deste link';
$lang['hp_links_idGrupo'] = 'Grupo do link' ;
$lang['hp_links_PosGrupo'] = 'Posição do link' ;
$lang['hp_links_linkURL'] = 'URL do link';
$lang['hp_links_descricaoLink'] = 'Descrição do link';
$lang['hp_links_dicaLink'] = 'Dica para o link';
$lang['hp_links_localLink'] = 'Link local';
$lang['hp_links_urlElementoSSL'] = 'Link seguro (https)';
$lang['hp_links_urlElementoSVN'] = 'Link svn+ssh';
$lang['hp_links_targetLink'] = 'Target (frame)';

// atributos de um elemento Form
$lang['hp_forms_nomeForm'] = 'Nome (campo name=)';
$lang['hp_forms_acao'] = 'Ação (campo action=)';
$lang['hp_forms_nomeCampo'] = 'Campo de busca';
$lang['hp_forms_tamanhoCampo'] = 'Tamanho';
$lang['hp_forms_descricaoForm'] = 'descricao';

// atributos de um elemento tipo Separador
$lang['hp_separadores_descricaoSeparador'] = 'Descricao do separador';
$lang['hp_separadores_breakBefore'] = 'Quebrar linha antes do separador';

// atributos de um elemento tipo Imagem
$lang['hp_imagens_descricaoImagem'] = 'Descricao da Imagem';
$lang['hp_imagens_urlImagem'] = 'url da Imagem';
$lang['hp_imagens_localLink'] = 'link local';

// atributos de um elemento tipo RssFeed
$lang['hp_rssfeeds_rssURL'] = 'Url para o Rss';
$lang['hp_rssfeeds_rssItemNum'] = 'Número de ordem do item a resgatar';

// atributos de um elemento tipo Template
$lang['hp_templates_descricaoTemplate'] = 'Descricao do template';
$lang['hp_templates_nomeTemplate'] = 'Nome do arquivo template';

//
//
// Constantes utilizadas nos vários diálogos de edição/criação/confirmação/exclusão.
//
//

// Utilizados nos botões e links para criação de objetos
$lang['novaPagina'] = 'Nova página';
$lang['novaCategoria'] = 'Nova categoria';
$lang['novoGrupo'] = 'Novo grupo';
$lang['novosElementos'] = 'Novos Elementos';
$lang['novoLink'] = 'Novo link';
$lang['novoForm'] = 'Novo formulário';
$lang['novaImagem'] = 'Nova imagem';
$lang['novoSeparador'] = 'Novo separador';
$lang['novoRssFeed'] = 'Novo Rss Feed';
$lang['novoTemplate'] = 'Novo template';

// Utilizado em diálogos de confirmação
$lang['tituloTabelaConfirmarExclusao'] = ':: Leia com atenção ::';

// para o diálogo de seleção da página
$lang['tituloPaginaSelecionarPagina'] = 'Edição de página';
$lang['tituloTabelaSelecionarPagina'] = ':: Lista de páginas';
$lang['selecionarPagina'] = 'Por favor, selecione a página a editar:';

// para o diálogo de confirmação exclusão de página
$lang['confirmarExclusaoPagina'] = 'Tem certeza que deseja excluir a página ';
$lang['tituloPaginaConfirmarExclusao'] = ':: Exclusão de página ::';

// para o diálogo de seleção da categoria
$lang['tituloPaginaSelecionarCategoria'] = 'Edição de categoria';
$lang['tituloTabelaSelecionarCategoria'] = ':: Lista de categorias';
$lang['selecionarCategoria'] = 'Por favor, selecione a categoria a editar:';

// para o diálogo de confirmação de exclusão de categoria
$lang['confirmarExclusaoCategoria'] = 'Tem certeza que deseja excluir a categoria ';
$lang['tituloPaginaConfirmarExclusaoCategoria'] = ':: Exclusão de categoria ::';

// para o diálogo de seleção de grupo
$lang['tituloPaginaSelecionarGrupo'] = 'Edição de grupo';
$lang['tituloTabelaSelecionarGrupo'] = ':: Lista de grupos';
$lang['selecionarGrupo'] = 'Por favor, selecione o grupo a editar:';

// para o diálogo de confirmação de exclusão de grupo
$lang['confirmarExclusaoGrupo'] = 'Tem certeza que deseja excluir o grupo ';
$lang['tituloPaginaConfirmarExclusaoGrupo'] = ':: Exclusão de grupo ::';

// para o diálogo de edição e criação de links
$lang['tituloPaginaEditarLink'] = ':: Edição de Link ::';
$lang['tituloPaginaCriarLink'] = ':: Criação de Link ::';

// para o diálogo de confirmação de exclusão de link
$lang['confirmarExclusaoLink'] = 'Tem certeza que deseja excluir o link ';
$lang['tituloPaginaConfirmarExclusaoLink'] = ':: Exclusão de Link ::';

// para o diálogo de edição e criação de forms
$lang['tituloPaginaEditarForm'] = ':: Edição de Form ::'; 
$lang['tituloPaginaCriarForm'] = ':: Criação de Form ::';

// para o diálogo de confirmação de exclusão de form
$lang['confirmarExclusaoForm'] = 'Tem certeza que deseja excluir o Form ';
$lang['tituloPaginaConfirmarExclusaoForm'] = ':: Exclusão de Form ::';

// para o diálogo de edição e criação de separadores
$lang['tituloPaginaEditarSeparador'] = ':: Edição de Separador ::'; 
$lang['tituloPaginaCriarSeparador'] = ':: Criação de Separador ::';

// para o diálogo de confirmação de exclusão de separador
$lang['confirmarExclusaoSeparador'] = 'Tem certeza que deseja excluir o Separador ';
$lang['tituloPaginaConfirmarExclusaoSeparador'] = ':: Exclusão de Separador ::';

// para o diálogo de edição e criação de imagens
$lang['tituloPaginaEditarImagem'] = ':: Edição de Imagem ::'; 
$lang['tituloPaginaCriarImagem'] = ':: Criação de Imagem ::';

// para o diálogo de confirmação de exclusão de separador
$lang['confirmarExclusaoImagem'] = 'Tem certeza que deseja excluir a Imagem ';
$lang['tituloPaginaConfirmarExclusaoImagem'] = ':: Exclusão de Imagem ::';

// para o diálogo de edição e criação de rssfeeds
$lang['tituloPaginaEditarRssFeed'] = ':: Edição de Rss Feed ::'; 
$lang['tituloPaginaCriarRssFeed'] = ':: Criação de Rss Feed ::';

// para o diálogo de confirmação de exclusão de separador
$lang['confirmarExclusaoRssFeed'] = 'Tem certeza que deseja excluir o Rss Feed ';
$lang['tituloPaginaConfirmarExclusaoRssFeed'] = ':: Exclusão de Rss Feed ::';

// para o diálogo de edição e criação de templates
$lang['tituloPaginaEditarTemplate'] = ':: Edição de Template ::'; 
$lang['tituloPaginaCriarTemplate'] = ':: Criação de Template ::';

// para o diálogo de confirmação de exclusão de separador
$lang['confirmarExclusaoTemplate'] = 'Tem certeza que deseja excluir o Template ';
$lang['tituloPaginaConfirmarExclusaoTemplate'] = ':: Exclusão de Template ::';

// botões
$lang['incluir'] = 'Incluir';
$lang['gravar'] = 'Gravar';
$lang['excluir'] = 'Excluir';
$lang['cancelar'] = 'Cancelar';
$lang['confirmar'] = 'Confirmar';
$lang['exibir'] = 'Exibir página';
$lang['voltar'] = 'Voltar';
$lang['sim'] = 'Sim';
$lang['nao'] = 'Não';

// Utilizados nas tabelas de elementos na edição de páginas, categorias e grupos
$lang['subir'] = 'Subir';
$lang['descer'] = 'Descer';
$lang['categoriaRestrita'] = 'Categoria Restrita';
$lang['grupoRestrito'] = 'Grupo Restrito';
$lang['tipoGrupo'] = 'Tipo de Grupo';
$lang['tipoElemento'] = 'Tipo de Elemento';
$lang['configuracao'] = 'Configuração';
$lang['config_new_link'] = 'Novo Link';
$lang['config_edit_link'] = 'Editar Link';
$lang['config_new_form'] = 'Novo Form';
$lang['config_edit_form'] = 'Editar Form';
$lang['config_new_imagem'] = 'Nova Imagem';
$lang['config_edit_imagem'] = 'Editar Imagem';
$lang['config_new_template'] = 'Novo Template';
$lang['config_edit_template'] = 'Editar Template';
$lang['config_new_rss'] = 'Novo RSS';
$lang['config_edit_rss'] = 'Editar RSS';

$lang['paginas'] = 'Páginas';
$lang['categorias'] = 'Categorias da Página';
$lang['grupos'] = 'Grupos da Categoria';
$lang['elementos'] = 'Elementos do Grupo';

$lang['semTitulo'] = ':: sem título ::';
$lang['paginavazia'] = ':: Página sem categorias ::';
$lang['categoriavazia'] = ':: Categoria sem grupos ::';
$lang['grupovazio'] = ':: Grupo vazio ::';

// pagina de estatísticas
$lang['paginaEstatisticasTituloPagina'] = 'Administração de páginas - Estatísticas';
$lang['paginaEstatisticasTituloTabela'] = ':: Estatísticas ::';
$lang['NumPaginas'] = 'Número de páginas existentes';
$lang['NumCategorias'] = 'Número de categorias cadastradas';
$lang['NumGrupos'] = 'Número de grupos disponíveis';
$lang['NumLinks'] = 'Número de links cadastrados';
$lang['NumSeparadores'] = 'Número de separadores de links';
$lang['NumImagens'] = 'Número de imagens armazenadas';
$lang['NumForms'] = 'Número de forms cadastrados';
$lang['NumRssFeeds'] = 'Número de feeds';
$lang['NumTemplates'] = 'Número de Templates';
$lang['NumFortunes'] = 'Número de biscoitinhos da sorte';

// pagina install
$lang['tituloPaginaInstall'] = 'Homepage post-install info';
$lang['tituloTabelaInstall'] = 'root and admin .HTACCESSes';
?>


