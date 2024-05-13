<?php

// Onde coloquei o smarty?
$smarty_class = $smarty_location . 'Smarty.class.php';

//
// Classe smarty para manipulação de templates
// Carregar apenas uma vez.
require_once($smarty_class);

// localização dos templates
//
// wrapper around Smarty class with my own parameters...
class hp_smarty extends Smarty
{
	function __construct($XMLFilePath, $configID)
	{

		parent::__construct();

		// carrega os parâmetros de configuração com a localização dos diretórios utilizados pelo Smarty
        if(!$this->_set_Smarty_Parameters($XMLFilePath, $configID))
		{
			die('Erro na carga dos parâmetros do smarty! - arquivo informado: ' . $XMLFilePath);
		}

		// pediu debug?
		if (isset($_REQUEST['debug']) && $_REQUEST['debug'] == 'sim') 
		{
			$this->debugging = true;
		}

		// de acordo com o tipo de browser:
		// - utiliza um atributo ou outro para a classe de um elemento.
		if (strstr($_SERVER['HTTP_USER_AGENT'], 'Gecko')) 
		{
			$this->assign('classAttribute', 'class');
		}
		elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Opera'))
		{
			$this->assign('classAttribute', 'class');
		}	
		elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
		{
			$this->assign('classAttribute', 'className');
		}	

	}

	private function _set_Smarty_Parameters($XMLFilePath, $configID)
	{

		if (!(file_exists($XMLFilePath)))
		{
			return FALSE;
		}

		$configurations = simplexml_load_file($XMLFilePath);

		foreach ($configurations as $configuration)
		{
			if ($configuration['ID'] == $configID)
			{
				$this->template_dir = HOMEPAGE_PATH . (string) $configuration->Smarty_Templates_PATH;
				$this->compile_dir = (string) $configuration->Smarty_Compile_PATH;
				$this->cache_dir = (string) $configuration->Smarty_Cache_PATH;
				$this->config_dir = HOMEPAGE_PATH . (string) $configuration->Smarty_Config_PATH;
			}
		}

		return TRUE;
	}

}	


//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
