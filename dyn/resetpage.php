<?php
// resetpage
// - apaga os cookies de cor para a página atual. Retorna OK ou NOK dependendo do sucesso da operação
//

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

require_once($include_path . 'class_database.php');
require_once($include_path . 'class_estilos.php');

// localização do xml com detalhes da conexão e o número da conexão a ser utilizada...
$connection_info_xml_path = $config_path . 'connections.xml';

// obtém a chave da página a partir da request url
if (!isset($_REQUEST['id'])) 
{
	echo 'NOK';
	exit;
}
else
{
	$idPagina = urldecode($_REQUEST['id']);
}

// global que manterá a conexão à base de dados única para todos os objetos instanciados.
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
