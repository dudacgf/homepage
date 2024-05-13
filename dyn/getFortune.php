<?php

header('Content-Type: text/html; charset=UTF-8');

// 
// pega o id da fortune selecionada no form
if (isset($_REQUEST['idFortune']) && !empty($_REQUEST['idFortune'])) {
	$idFortune = $_REQUEST['idFortune'];
}

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
define('RELATIVE_PATH', './../');
define('HOMEPAGE_PATH', './../');
// localização dos includes
$include_path = HOMEPAGE_PATH . 'includes/';

include_once($include_path . 'class_database.php');
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

// Lê um fortune e devolve
require($include_path . "class_fortune.php");
$f = new fortune;
$biscoitinho = $f->fortune;
if (strpos($biscoitinho, "--") > 0)
{
    $biscoitinho = str_replace("--", "<b>--", $biscoitinho) . "</b>";
}

//
// manda o biscoitinho para quem me chamou
echo "<div class=\"fortune\">
<br />
$biscoitinho<br /></div>";

//-- vi: set tabstop=4 shiftwidth=4 showmatch: 

?>
