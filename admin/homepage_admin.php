<?php
//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

if (isset($_REQUEST['navpanel']) and $_REQUEST['navpanel'] == 'yes') 
{
	// le as categorias, grupos e elementos desta página...
	include(HOMEPAGE_PATH . 'homepage.php');
}
else
{

	// A página de administração tem idPagina = 5
	$_idPagina = 5;

	// abro e inicializo minha página
	$pagina = new pagina($_idPagina);
	$homepage->assign('idPagina', $pagina->idPagina);
	$homepage->assign('tituloPagina', $pagina->tituloPagina);
	$homepage->assign('tituloTabela', $pagina->tituloTabela);
	$homepage->assign('classPagina', $pagina->classPagina);
	$homepage->assign('displayImagemTitulo', '1');
	$homepage->assign('displaySelectColor', $pagina->displaySelectColor);

    $homepage->assign('includePATH', INCLUDE_PATH);
	$homepage->display('admin/index_admin.tpl');
}

//-- vim: set shiftwidth=4 tabstop=4: 

?>
