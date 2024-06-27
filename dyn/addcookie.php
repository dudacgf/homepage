<?php
// add cookie 
// - cria um cookie de cor para a página atual e retorna uma string apontando os elementos da página que 
//   deverão ter sua cor imediatamente modificada.
//

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

require_once($include_path . 'class_database.php');
require_once($include_path . 'class_estilos.php');

// localização do xml com detalhes da conexão e o número da conexão a ser utilizada...
$connection_info_xml_path = $config_path . 'connections.xml';

// global que manterá a conexão à base de dados única para todos os objetos instanciados.
$global_hpDB = new database($connection_info_xml_path, 1);

// obtém as chaves da página, do elementocolorido e do valorCor a partir da request url
if (!isset($_REQUEST['id']) || !isset($_REQUEST['el']) || !isset($_REQUEST['c'])) 
{
	echo 'NOK'; flush();
	exit;
}
else
{
	$idPagina = urldecode($_REQUEST['id']);
	$idElementoColorido = urldecode($_REQUEST['el']);
	$valorCor = urldecode($_REQUEST['c']);
}

$idPar = RGBColor::getIdPar($valorCor);
if (!$idPar) {
	echo 'NOK'; flush();
	exit;
}

$cookie = new cookedStyle();
if ($cookie->inserirCookedStyle($idPagina, $idElementoColorido, $idPar))
{
	$elementoColorido = new elementoColorido($idElementoColorido);
	if ($elementoColorido->idElementoColorido = $idElementoColorido) {
		echo $elementoColorido->descricaoElemento . "|" . $elementoColorido->atributoCorElemento . "|" .
		     $elementoColorido->criterioBuscaElemento . "|" . $elementoColorido->termoBuscaElemento . "|" .
			 $elementoColorido->cookieElemento . "|" . $valorCor; flush();
	}
	else
	{
		echo 'NOK'; flush();
	}
}
else
{
	echo 'NOK'; flush();
}

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
