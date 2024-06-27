<?php
// del cookie 
// - apaga um cookie de cor para a página atual. Retorna OK ou NOK dependendo do sucesso da operação
//

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

require_once($include_path . 'class_database.php');
require_once($include_path . 'class_estilos.php');

// localização do xml com detalhes da conexão e o número da conexão a ser utilizada...
$connection_info_xml_path = $config_path . 'connections.xml';

// obtém as chaves da página e do elementocolorido a partir da request url
if (!isset($_REQUEST['id']) || !isset($_REQUEST['el'])) 
{
	echo 'NOK'; flush();
	exit;
}
else
{
	$idPagina = urldecode($_REQUEST['id']);
	$idElementoColorido = urldecode($_REQUEST['el']);
}

// global que manterá a conexão à base de dados única para todos os objetos instanciados.
$global_hpDB = new database($connection_info_xml_path, 1);

// elimina o cookie e, em caso de sucesso, envia de volta a informação necessária para restaurar a cor na página
$cookie = new cookedStyle();
if ($cookie->eliminarCookedStyle($idPagina, $idElementoColorido))
{
	$elementoColorido = new elementoColorido($idElementoColorido);
	if ($elementoColorido->idElementoColorido = $idElementoColorido) {
		require_once($include_path . 'class_homepage.php');
		$pagina = new pagina($idPagina);

		$selectors = explode(';', $elementoColorido->termoBuscaElemento);
		if ($elementoColorido->criterioBuscaElemento == 'id') 
			$selector = 'body.' . $pagina->classPagina;
		else
			$selector = 'body.' . $pagina->classPagina . " $selectors[0]";
		$atributos = explode(';', $elementoColorido->atributoCorElemento); 
		$atributo = $atributos[0];
		switch ($atributo) // como todos os selectors terão os mesmos atributos, só preciso do primeiro
		{
			case 'color':
				$atributo = 'color';
				break;
			case 'bcolor':
				$atributo = 'background-color';
				break;
			case 'tborder':
				$atributo = 'border-top-color';
				break;
			case 'lborder':
				$atributo = 'border-left-color';
				break;
			case 'bborder':
				$atributo = 'border-bottom-color';
				break;
			case 'rborder':
				$atributo = 'border-right-color';
				break;
			case 'bimg':
				$atributo = 'background-image';
				break;
		}

		// agora, obtém o atributo diretamente na folha de estilo, usando um cssparser que eu encontrei por aí (aTutor.org).
		require_once($include_path . 'class_cssparser.php');
		$css = new cssparser();
		$css->Parse($estilos_path . $pagina->classPagina . '.css');
		$valorCor = urlencode($css->getCSSSelectorAtribute($selector, $atributo));

		echo $elementoColorido->descricaoElemento . "|" . $elementoColorido->atributoCorElemento . "|" .
		     $elementoColorido->criterioBuscaElemento . "|" . $elementoColorido->termoBuscaElemento . "|" .
			 $elementoColorido->cookieElemento . "|" . $valorCor; flush();
	
	}
}
else
{
	echo 'NOK'; flush();
}

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
