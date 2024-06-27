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
require_once('../common.php');

require_once($include_path . 'class_database.php');
// localização do xml com detalhes da conexão e o número da conexão a ser utilizada...
$connection_info_xml_path = $config_path . 'connections.xml';

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
require_once($include_path . 'class_homepage.php');
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

// localização da library do smarty, suas classes e plugins
require_once($include_path . 'class_hp_smarty.php');

// cria o template smart, associa valores
$catInnerHtml = new hp_smarty();
$catInnerHtml->assign('descricoesCategorias', $descricoesCategorias);
$catInnerHtml->assign('descricoesGrupos', $descricoesGrupos);
$homepage->assign('includePATH', INCLUDE_PATH);

// retorna o que vier do template para quem me chamou
echo $catInnerHtml->fetch('page_body.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch: 

?>
