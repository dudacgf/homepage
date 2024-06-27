<?php
//
// Classe smarty para manipulação de templates
require HOMEPAGE_PATH . '/vendor/autoload.php';
use Smarty\Smarty;

// localização dos templates
//
// wrapper around Smarty class with my own parameters...
class hp_smarty extends Smarty
{
	function __construct()
	{

		parent::__construct();

        $this->setTemplateDir(HOMEPAGE_PATH . '/templates');

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
}	


//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
