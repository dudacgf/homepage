<?php
//
// Classe smarty para manipulação de templates
Namespace Shiresco\Homepage\HpSmarty; 

use Smarty\Smarty;

// localização dos templates
//
// wrapper around Smarty class with my own parameters...
class HpSmarty extends Smarty {
	function __construct()
	{

		parent::__construct();

        $this->setTemplateDir(HOMEPAGE_PATH . '/modelos');
        $this->setCompileDir(HOMEPAGE_PATH . '/smarty_c');

		// pediu debug?
		if (isset($_REQUEST['debug']) && $_REQUEST['debug'] == 'sim') 
		{
			$this->debugging = true;
		}

        if (php_sapi_name() != "cli") {
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
}	
?>
