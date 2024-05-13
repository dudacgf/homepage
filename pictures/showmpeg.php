<?php
// http://www.jeroenwijering.com/
// "C:\Program Files\Riva\Riva FLV Encoder 2.0\ffmpeg.exe" -i "C:\Fotos\niver Olivia 2006\04-02-2006 15-25-10.MPG" -b 360 -r 25 -s 240x180 -hq -deinterlace  -ab 56 -ar 22050 -ac 1  "C:\HomePage\ddd\04-02-2006 15-25-10.flv" 2>encode.txt
//

//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
define('HOMEPAGE_PATH', './../');
define('RELATIVE_PATH', './../');
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
$dircache = $dircache . '.flv' ;	// os flv ficam em um diret�rio pr�prio 

// nome do filme a passar
if (isset($_REQUEST['mov'])) {
	$mov = str_replace('..', '', $_REQUEST['mov']);
	$mov = html_entity_decode($mov);
	$mov = str_replace(array('\\', '//'), '/', $mov);
}

if (!file_exists($dircache))
{
	mkdir($dircache, 0777, true);
}

// se este diret�rio ainda n�o existe no cache, cria
if (!file_exists($dircache . '/' . dirname($mov))) 
{
	mkdir($dircache . '/' . dirname($mov), 0777, true);
}

// cria uma frame a partir do filme original
$infile = $dirfotos . $mov;
// Localiza��o e nome do arquivo de sa�da no cache
$cacheFile = $dircache . $mov;

// obt�m o nome base deste arquivo (sem a extens�o)
$cacheFile_info = pathinfo($cacheFile);
$cacheFile_name = basename($cacheFile_info['basename'], '.' . $cacheFile_info['extension']);

// Arquivo com as informa��es que ser�o exibidas no popup...
$txtFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.txt';
// ff_flvFile � o arquivo de sa�da em formato flv
// txtFile � o arquivo com informa��es gerado durante a convers�o. ser� removido ao final.
$ff_flvFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.flv';

// se este filme ainda n�o tem seu flv no cache, cria...
if (!file_exists($ff_flvFile))
{
	// dispara a convers�o do v�deo para o formato flv
	// a convers�o � feita em duas etapas. 
	// Primeiro, ffmpeg transforma de mpg em flv. 
	// Por fim, flvtool2 insere meta-tags(?) para permitir a navega��o no filme quando exibido via flvprovider.php...
	exec("ffmpeg-flv.exe -i \"$infile\" -b 360 -r 25 -s 320x240 -hq -deinterlace  -ab 56 -ar 22050 -ac 1  \"$ff_flvFile\" 2>&1", $output);
	exec("flvtool2.exe u \"$ff_flvFile\" \"$ff_flvFile\" 2>&1", $output);
}

// insere dados no array que ser� passado para o template
$arquivo = array(
		'arquivo' => $requests['mov'],
		'tipo' => 'flv',
		'url' => str_replace(array("MPG", "mpg", "MPEG", "mpeg"), "flv", $mov));

// Passa as vari�veis para o template e o exibe.
$homepage->assign('tituloPaginaAlternativo', ':: ' . $requests['mov'] . ' ::');
$homepage->assign('tituloTabelaAlternativo', ':: ' . $requests['mov'] . ' ::');
$homepage->assign('relativePATH', RELATIVE_PATH);
$homepage->assign('classPagina', 'black');
$homepage->assign('arquivo', $arquivo);

$homepage->display('pictures/showpicture.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
