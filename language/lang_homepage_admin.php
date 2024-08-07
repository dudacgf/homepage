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
$lang['hp_paginas_TituloPagina'] = 'Título (html)';
$lang['hp_paginas_Placeholder_TituloPagina'] = 'Título da página';
$lang['hp_paginas_TituloTabela'] = 'Título (tabela)';
$lang['hp_paginas_Placeholder_TituloTabela'] = 'Título da tabela';
$lang['hp_paginas_classPagina'] = 'Classe';
$lang['hp_paginas_exibirdotdot'] = 'Exibir: ';
$lang['hp_paginas_displayGoogle'] = 'form do Google';
$lang['hp_paginas_displayFindaMap'] = 'form do Find a Map';
$lang['hp_paginas_displayFortune'] = 'biscoitinho da sorte';
$lang['hp_paginas_displayImagemTitulo'] = 'Imagem no Título';
$lang['hp_paginas_displaySelectColor'] = 'Formulário de cores';

// atributos de uma categoria (coluna)
$lang['hp_categorias_DescricaoCategoria'] = 'Título';
$lang['hp_categorias_Placeholder_DescricaoCategoria'] = 'Título da categoria';
$lang['hp_categorias_CategoriaRestrita'] = 'Categoria restrita';
$lang['hp_categorias_RestricaoCategoria'] = 'Restrição';
$lang['hp_categorias_Placeholder_RestricaoCategoria'] = 'Indique a restrição da categoria (qualquer string)';
$lang['hp_categorias_Label_Restricao'] = 'Restrita';

// atributos de um grupo
$lang['hp_grupos_DescricaoGrupo'] = 'Nome';
$lang['hp_grupos_Placeholder_DescricaoGrupo'] = 'Nome do Grupo';
$lang['hp_grupos_TipoGrupo'] = 'Tipo';
$lang['hp_grupos_GrupoRestrito'] = 'Grupo restrito';
$lang['hp_grupos_RestricaoGrupo'] = 'Restrição';
$lang['hp_grupos_Placeholder_RestricaoGrupo'] = 'Indique a restrição do grupo (qualquer string)';
$lang['hp_grupos_Label_Restricao'] = 'Restrito';

// 
$lang['hp_elementos_Descricao'] = 'nome do Elemento';

// atributos de um elemento tipo link
$lang['hp_links_FormTitulo'] = 'Links';
$lang['hp_links_idLink'] = 'Id deste link';
$lang['hp_links_idGrupo'] = 'Grupo do link' ;
$lang['hp_links_PosGrupo'] = 'Posição do link' ;
$lang['hp_links_linkURL'] = 'URL';
$lang['hp_links_Placeholder_linkURL'] = 'URL do link';
$lang['hp_links_descricaoLink'] = 'Descrição';
$lang['hp_links_Placeholder_descricaoLink'] = 'Descrição do link (label do link)';
$lang['hp_links_dicaLink'] = 'Dica';
$lang['hp_links_Placeholder_dicaLink'] = 'Dica para o link (tooltip)';
$lang['hp_links_localLink'] = 'Link local';
$lang['hp_links_urlElementoSSL'] = 'Link seguro (https)';
$lang['hp_links_urlElementoSVN'] = 'Link svn+ssh';
$lang['hp_links_targetLink'] = 'Target (frame)';

// atributos de um elemento Form
$lang['hp_forms_nomeForm'] = 'Nome';
$lang['hp_forms_Placeholder_nomeForm'] = 'Nome (campo name=)';
$lang['hp_forms_acao'] = 'Ação';
$lang['hp_forms_Placeholder_acao'] = 'Ação (campo action=)';
$lang['hp_forms_nomeCampo'] = 'Campo de busca';
$lang['hp_forms_Placeholder_nomeCampo'] = 'Campo de busca (e.g. q in ?q=)';
$lang['hp_forms_tamanhoCampo'] = 'Tamanho';
$lang['hp_forms_Placeholder_tamanhoCampo'] = 'Tamanho do campo (na coluna)';
$lang['hp_forms_descricaoForm'] = 'descricao';
$lang['hp_forms_Placeholder_descricaoForm'] = 'descricao do formulário';

// atributos de um elemento tipo Separador
$lang['hp_separadores_descricaoSeparador'] = 'Descricao';
$lang['hp_separadores_Placeholder_descricaoSeparador'] = 'Texto do Separador (pode ser vazio)';
$lang['hp_separadores_breakBefore'] = 'Quebrar linha antes do separador';

// atributos de um elemento tipo Imagem
$lang['hp_imagens_descricaoImagem'] = 'Descricao';
$lang['hp_imagens_Placeholder_descricaoImagem'] = 'Descricao da Imagem';
$lang['hp_imagens_urlImagem'] = 'url';
$lang['hp_imagens_Placeholder_urlImagem'] = 'url da Imagem';
$lang['hp_imagens_localLink'] = 'link local';

// atributos de um elemento tipo Template
$lang['hp_templates_descricaoTemplate'] = 'Descricao';
$lang['hp_templates_Placeholder_descricaoTemplate'] = 'Descricao do template';
$lang['hp_templates_nomeTemplate'] = 'Arquivo';
$lang['hp_templates_Placeholder_nomeTemplate'] = 'Nome do arquivo template (relativo à homepage. sempre local)';

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
$lang['NumPaginas'] = 'Páginas existentes';
$lang['NumCategorias'] = 'Categorias cadastradas';
$lang['NumGrupos'] = 'Grupos disponíveis';
$lang['NumLinks'] = 'Links cadastrados';
$lang['NumSeparadores'] = 'Separadores de links';
$lang['NumImagens'] = 'Imagens armazenadas';
$lang['NumForms'] = 'Forms cadastrados';
$lang['NumTemplates'] = 'Templates';
$lang['NumFortunes'] = 'Biscoitinhos da sorte';

// pagina install
$lang['tituloPaginaInstall'] = 'Homepage post-install info';
$lang['tituloTabelaInstall'] = 'root and admin .HTACCESSes';
?>


