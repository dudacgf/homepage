<?php
//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
include_once(RELATIVE_PATH . 'common.php');

//
// configura��es para exibi��o das fotos
$fotos = simplexml_load_file($pictures_info_xml_path);
foreach ($fotos as $fotoDir)
{
   if ($fotoDir['ID'] == "$pictures_info_xml_id")
   {
	   $dirfotos = (string) $fotoDir->path;
	   $dircache = (string) $fotoDir->cacheDir;
	   $colunas = (int) $fotoDir->colunas;
	   $linhas = (int) $fotoDir->linhas;
   }
}

if (isset($requests['pic'])) 
{
	$requests['pic'] = str_replace('..', '', $requests['pic']);
	$picture = '/' . html_entity_decode($requests['pic']);
	$picture = str_replace(array('\\', '//'), '/', $picture);
}

// obtem a extens�o, que ser� utilizada pelo template
$picture_info = pathinfo($picture);
$url = htmlentities("showpicture.php?pic=" . $requests['pic']);

// insere dados no array que ser� passado para o template
$arquivo = array(
		'image' => "showthumb.php?pic=" . $requests['pic'] . "&width=640",
		'arquivo' => $requests['pic'],
		'tipo' => $picture['extension'],
		'url' => $url);

// Passa as vari�veis para o template e o exibe.
$homepage->assign('tituloPaginaAlternativo', ':: ' . $requests['pic'] . ' ::');
$homepage->assign('tituloTabelaAlternativo', ':: ' . $requests['pic'] . ' ::');
$homepage->assign('relativePATH', RELATIVE_PATH);
$homepage->assign('classPagina', 'black');
$homepage->assign('arquivo', $arquivo);

$homepage->display('pictures/showpicture.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch: 

?>
