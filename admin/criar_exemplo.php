<?php

//
// cria uma categoria, seus grupos e links para exibir como exemplo.
//
	
// cria o primeiro grupo, flat e o chama de aberto.
$elementos[] = array( 
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'link em grupo aberto',
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
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'link em grupo clic&aacute;vel',
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
	'tipoElemento' => 1,
	'linkURL' => htmlentities("javascript: return false;"),
	'descricaoLink' => 'link em grupo expans&iacute;vel',
	'dicaLink' => 'dica deste link',
	'urlElementoSSL' =>  0,
	'urlElementoSVN' => 0,
	'localLink' => 0,
	'targetLink' => '');
$grupos[] = array(
	'grupo' => 'Grupo expans&iacute;vel',
	'idtipoGrupo' => 3,
	'elementos' => $elementos);

// cria o quarto grupo, flat. inclui nele o template de um botão.
unset($elementos); 
$elementos[] = array( 
	'tipoElemento' => 6,
	'descricaoTemplate' => 'bot&atilde;o',
	'nomeTemplate' => 'admin/sample_button.tpl');
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
