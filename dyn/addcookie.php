<?php
// add cookie 
// - cria um cookie de cor para a página atual e retorna uma string apontando os elementos da página que 
//   deverão ter sua cor imediatamente modificada.
//

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

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
?>
