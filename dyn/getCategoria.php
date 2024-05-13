<?php

header('Content-Type: text/html; charset=iso-8859-1');

// 
// pega o id da categoria selecionada no form
if (isset($_REQUEST['idCat']) && !empty($_REQUEST['idCat'])) {
	$idCat = $_REQUEST['idCat'];
}
else
{
	echo '<br /><div id="alarm" style="width:150px; padding: 5px; font-size: 1.2em; font-weight: bold; background-color: red; color: white;">Selecione uma categoria!</div>';
	exit;
}

// se não tiver definido nenhuma restrição, pega todas
if (!isset($_REQUEST['gr'])) {
	$_REQUEST['gr'] = 'all';
}

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
define('RELATIVE_PATH', './../');
define('HOMEPAGE_PATH', './../');

include_once(RELATIVE_PATH . 'includes/class_database.php');
// localização do xml com detalhes da conexão e o número da conexão a ser utilizada...
$connection_info_xml_path = RELATIVE_PATH . 'configs/connections.xml';

// global que manterá a conexão à base de dados única para todos os objetos instanciados.
try {
	$global_hpDB = new database($connection_info_xml_path, 1);
}
catch (Exception $e) {
	echo 'NOK';
	exit;
}

//
// Leio a categoria e percorro-a, incluíndo-a e a seus grupos no template

// classes da estrutura da página, para a leitura da categoria, dos grupos e seus elementos.
include_once(RELATIVE_PATH . 'includes/class_homepage.php');
$categ = new Categoria($idCat);

// Leio os grupos desta categoria e percorro-os, incluíndo-os num array que será passado para o template
$categ->lerElementos();
foreach ($categ->elementos as $grupo) 
{

	// Leio os elementos deste grupo e percorro-os, incluíndo-os num array que será incluído no grupo a que pertence.
	$grupo->lerElementos();
	unset($elementos);
	foreach($grupo->elementos as $elemento) 
	{
		$elementos[] = $elemento->getArray();
	}		

	$grupos[] = array(
					'grupo' => $grupo->descricaoGrupo,
					'idtipoGrupo' => $grupo->idTipoGrupo,
					'elementos' => $elementos);

}
//
// tem que passar como array porque o template page_body.tpl, feito para homepage.php, espera as informações desta forma.
// TODO. melhorar esta coisa. tá ruim.
$descricoesCategorias[] = array('index' => $categ->posPagina, 'categoria' => $categ->descricaoCategoria);
$descricoesGrupos[] = array('index' => $grupo->posCategoria, 'idGrupo' => $grupo->idGrupo, 'grupos' => $grupos);

//
// obtenho via smarty a página que vou utilizar.

// localização da library do smarty, suas classes e plugins
include_once(RELATIVE_PATH . 'configs/smarty_location.php');
include_once(RELATIVE_PATH . 'includes/class_hp_smarty.php');

// localização do xml com detalhes da configuração do smarty
$smarty_info_xml_path = RELATIVE_PATH . 'configs/smarty.xml';
$smarty_info_xml_id = 1;

// cria o template smart, associa valores
$catInnerHtml = new hp_smarty($smarty_info_xml_path, $smarty_info_xml_id);
$catInnerHtml->assign('descricoesCategorias', $descricoesCategorias);
$catInnerHtml->assign('descricoesGrupos', $descricoesGrupos);
$catInnerHtml->assign('relativePATH', RELATIVE_PATH);

// retorna o que vier do template para quem me chamou
echo $catInnerHtml->fetch('page_body.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch: 

?>
