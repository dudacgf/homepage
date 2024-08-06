<?php
// resetpage
// - apaga os cookies de cor para a página atual. Retorna OK ou NOK dependendo do sucesso da operação
//

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

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
?>
