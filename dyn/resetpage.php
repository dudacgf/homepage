<?php

// resetpage
// - apaga os cookies de cor para a p�gina atual. Retorna OK ou NOK dependendo do sucesso da opera��o
//

//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
define('HOMEPAGE_PATH', './../');
define('RELATIVE_PATH', './../');

echo "estive aqui...";
include_once(RELATIVE_PATH . 'includes/class_database.php');
include_once(RELATIVE_PATH . 'includes/class_estilos.php');

// localiza��o do xml com detalhes da conex�o e o n�mero da conex�o a ser utilizada...
$connection_info_xml_path = RELATIVE_PATH . 'configs/connections.xml';

// obt�m a chave da p�gina a partir da request url
if (!isset($_REQUEST['id'])) 
{
	echo 'NOK';
	exit;
}
else
{
	$idPagina = urldecode($_REQUEST['id']);
}

// global que manter� a conex�o � base de dados �nica para todos os objetos instanciados.
$global_hpDB = new database($connection_info_xml_path, 1);

// pega o idPagina
$cookie = new cookedStyle();
if ($cookie->restaurarPagina($idPagina))
{
	echo 'OK';
}
else
{
	echo 'NOK';
}

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
