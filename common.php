<?php
// define a localização atual
define('HOMEPAGE_PATH', __dir__ . '/');
define('INCLUDE_PATH', str_replace($_SERVER['DOCUMENT_ROOT'], '', HOMEPAGE_PATH));

// diretório para uploads...
$uploaddir = HOMEPAGE_PATH . 'download/';

// localização dos includes
$include_path = HOMEPAGE_PATH . 'includes/';

// localização dos fortunes
$fortune_path = HOMEPAGE_PATH . 'fortunes/';

// localização das imagens
$images_path = HOMEPAGE_PATH . 'imagens/';

// localização dos arquivos de language
$language_path = HOMEPAGE_PATH . 'language/';

// localização da página de estilos
$estilos_path = HOMEPAGE_PATH . 'estilos/';
$estilos_sheet = $estilos_path . 'estilos.css';

// localização do diretório de configuração
$config_path = HOMEPAGE_PATH . 'configs/';

// localização do diretório de páginas administrativas
$admin_path = HOMEPAGE_PATH . 'admin/';

// localização do xml com detalhes da conexão e o número da conexão a ser utilizada...
$connection_info_xml_path = $config_path . 'connections.xml';
$connection_info_xml_id = 1;

// localização do xml com detalhes de folders especiais para a aplicação
$pictures_info_xml_path = $config_path . 'pictures.xml';
$pictures_info_xml_id = 1;

// pediu debug?
if (isset($_REQUEST['debug']) && $_REQUEST['debug'] == 'sim') 
{
	include($include_path . 'debug.php');
}
else
{
	// pequena echo local
	function localEcho($_theVar, $_theLabel)
	{
		echo "<b>$_theLabel</b><br />";
		echo str_replace(array("\n", "  "), array("<br />", "&nbsp;&nbsp;"), print_r($_theVar, true));
		echo "<br />";
	}
}

//
// resolve qual grupo de variáveis super-globais que trazem os parâmetros da url-query 
// (o que vai depois do ? em http://.../...?x=y)
if (isset($_GET)) 
{
	$requests = $_GET;
} 
elseif (isset($_POST)) 
{
	$requests = $_POST;
} 
$requests = array_merge($_REQUEST, $requests);

//
// Classe smarty adaptada.
include_once($include_path . 'class_hp_smarty.php');

// Crio a homepage
$homepage = new hp_smarty();

// se for página administrativa, le o arquivo de linguagem
if (preg_match('/\/admin\//', $_SERVER['SCRIPT_NAME'])) 
{
	include($language_path . "lang_homepage_admin.php");
	global $lang;
	$homepage->assign('LANG', $lang);
}

// classes específicas da homepage
include_once($include_path . 'class_homepage.php');

// classes para trabalho com estilos e cookedstyles
include_once($include_path . 'class_estilos.php');

// classe para acesso ao database mysql
include($include_path . "class_database.php");

// global que manterá a conexão à base de dados única para todos os objetos instanciados.
$global_hpDB = new database($connection_info_xml_path, $connection_info_xml_id);

// if there was a request with msgAlert, assing it to homepage (may be overwritten later)
if (isset($requests['msgAlerta']) and !isset($requests['reload']))
    $homepage->assign('msgAlerta', htmlentities($requests['msgAlerta']));
elseif (isset($requests['msgAlerta']))
    $homepage->assign('msgAlerta', htmlentities($requests['msgAlerta']));

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
?>
