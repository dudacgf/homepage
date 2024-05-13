<?php
 
//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
define('HOMEPAGE_PATH', './../');
define('RELATIVE_PATH', './../');
include_once(RELATIVE_PATH . 'common.php');

// obt�m as chaves da p�gina, do elementocolorido e do valorCor a partir da request url
if (!isset($requests['el'])) 
{
	echo 'NOK';
	exit;
}
else
{
	$idElementoColorido = urldecode($requests['el']);
}

$elementoColorido = new elementoColorido($idElementoColorido);
if ($elementoColorido->idElementoColorido = $idElementoColorido) {
	echo $elementoColorido->descricaoElemento . ";" . $elementoColorido->atributoCorElemento . ";" .
		   $elementoColorido->criterioBuscaElemento . ";" . $elementoColorido->termoBuscaElemento . ";" .
		   $elementoColorido->cookieElemento;
}
else
{
	echo 'NOK';
}

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
