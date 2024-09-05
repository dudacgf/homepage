<?php

//
// cria uma categoria, seus grupos e links para exibir como exemplo.
//
	
// cria o primeiro grupo, flat e o chama de aberto.
$elementos[] = array( 
	'idElemento' => 110000,
	'tipoElemento' => 3,
	'descricaoSeparador' => 'separador em grupo aberto',
    'breakBefore' => 0);
$elementos[] = array( 
	'idElemento' => 100000,
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'link em grupo aberto',
	'dicaLink' => 'dica deste link',
	'urlElementoSSL' =>  0,
	'urlElementoSVN' => 0,
	'localLink' => 0,
	'targetLink' => '');
$elementos[] = array( 
	'idElemento' => 100001,
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'outro link em grupo aberto',
	'dicaLink' => 'dica deste link',
	'urlElementoSSL' =>  0,
	'urlElementoSVN' => 0,
	'localLink' => 0,
	'targetLink' => '');
$grupos[] = array(
	'grupo' => 'Grupo aberto',
	'idtipoGrupo' => 1,
	'elementos' => $elementos);

// cria o segundo grupo, clickable e o chama de clicável
unset($elementos);
$elementos[] = array( 
	'idElemento' => 110001,
	'tipoElemento' => 3,
	'descricaoSeparador' => '',
    'breakBefore' => 1);
$elementos[] = array( 
	'idElemento' => 100002,
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'link em grupo clicável',
	'dicaLink' => 'dica deste link',
	'urlElementoSSL' =>  0,
	'urlElementoSVN' => 0,
	'localLink' => 0,
	'targetLink' => '');
$elementos[] = array( 
	'idElemento' => 110001,
	'tipoElemento' => 3,
	'descricaoSeparador' => 'separador em grupo clicável',
    'breakBefore' => 1);
$elementos[] = array( 
	'idElemento' => 100003,
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'outro link em grupo clicável',
	'dicaLink' => 'dica deste link',
	'urlElementoSSL' =>  0,
	'urlElementoSVN' => 0,
	'localLink' => 0,
	'targetLink' => '');
$grupos[] = array(
	'grupo' => 'Grupo clic&aacute;vel',
	'idtipoGrupo' => 2,
	'elementos' => $elementos);

// cria o terceiro grupo, expandable e o chama de expansível
unset($elementos); 
$elementos[] = array( 
	'idElemento' => 110001,
	'tipoElemento' => 3,
	'descricaoSeparador' => 'separador em grupo expansível',
    'breakBefore' => 1);
$elementos[] = array( 
	'idElemento' => 100004,
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'link em grupo expansível',
	'dicaLink' => 'dica deste link',
	'urlElementoSSL' =>  0,
	'urlElementoSVN' => 0,
	'localLink' => 0,
	'targetLink' => '');
$elementos[] = array( 
	'idElemento' => 100005,
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'outro link em grupo expansível',
	'dicaLink' => 'dica deste link',
	'urlElementoSSL' =>  0,
	'urlElementoSVN' => 0,
	'localLink' => 0,
	'targetLink' => '');
$elementos[] = array( 
	'idElemento' => 110002,
	'tipoElemento' => 3,
	'descricaoSeparador' => '',
    'breakBefore' => 1);
$elementos[] = array(
    'idElemento' => 100006,
    'tipoElemento' => 2,
    'nomeForm' => 'idSample',
    'descricaoForm' => 'Formulário em grupo expansível',
    'acao' => 'javascript: void(0);',
    'nomeCampo' => 'campoSample',
    'tamanhoCampo' => '20');
$grupos[] = array(
	'grupo' => 'Grupo expans&iacute;vel',
	'idtipoGrupo' => 3,
	'elementos' => $elementos);

// cria o quarto grupo, flat. inclui nele o template de um botão e um form
unset($elementos); 
$elementos[] = array( 
	'idElemento' => 100007,
	'tipoElemento' => 6,
	'descricaoTemplate' => 'botão',
	'nomeTemplate' => 'admin/sample_button.tpl');
$elementos[] = array(
    'idElemento' => 100008,
    'tipoElemento' => 2,
    'nomeForm' => 'idSample',
    'descricaoForm' => 'Formulário em grupo aberto',
    'acao' => 'javascript: void(0);',
    'nomeCampo' => 'campoSample',
    'tamanhoCampo' => '20');
$grupos[] = array(
	'grupo' => 'Grupo Com template',
	'idtipoGrupo' => 1,
	'elementos' => $elementos);

// cria o array com os grupos, que será passado para o template
$descricoesGrupos[] = array( 
	'index' => 0,
	'idGrupo' => 0,
	'grupos' => $grupos);

// cria a categoria, que será passada para o template
$categoriaExemplo[] = array(
	'index' => 0,
	'categoria' => 'Exemplo');

?>
