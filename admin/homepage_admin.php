<?php
	

// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
if (!defined('RELATIVE_PATH'))
{
    define('RELATIVE_PATH', './../');
}
define('HOMEPAGE_PATH', './../');

if (isset($_REQUEST['navpanel']) and $_REQUEST['navpanel'] == 'yes') 
{
	// le as categorias, grupos e elementos desta p�gina...
	include(RELATIVE_PATH . 'homepage.php');
}
else
{
    include_once(RELATIVE_PATH . 'common.php');

	// A p�gina de administra��o tem idPagina = 5
	$_idPagina = 5;

	// abro e inicializo minha p�gina
	$pagina = new pagina($_idPagina);
	$homepage->assign('idPagina', $pagina->idPagina);
	$homepage->assign('tituloPagina', $pagina->tituloPagina);
	$homepage->assign('tituloTabela', $pagina->tituloTabela);
	$homepage->assign('classPagina', $pagina->classPagina);
	$homepage->assign('displayImagemTitulo', '1');
	$homepage->assign('displaySelectColor', $pagina->displaySelectColor);

	$homepage->assign('relativePATH', RELATIVE_PATH);
	$homepage->display('admin/index_admin.tpl');
}

//-- vim: set shiftwidth=4 tabstop=4: 

?>
