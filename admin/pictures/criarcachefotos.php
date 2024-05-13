<?php

//----------------------------------------------------------------------------//
//
// Inicializa��es
//
//----------------------------------------------------------------------------//

//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
define('HOMEPAGE_PATH', './../../');
define('RELATIVE_PATH', './../../');
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
	   $pictureWidth = (string) $fotoDir->pictureWidth;
	   $thumbWidth = (string) $fotoDir->thumbWidth;
   }
}

//
//
// envia para o cliente o header e o in�cio do body, at� o t�tulo da tabela.
//
enviarInicioPagina();

//
//
// cria os diret�rios base do cache
//

//
// diret�rio de flv's para os mpegs
criarDiretorioSeNecessario($dircache . '.flv');

//
// cache para os thumbnails
criarDiretorioSeNecessario($dircache . '.w=' . $thumbWidth);

//
// cache para as fotos reduzidas
criarDiretorioSeNecessario($dircache . '.w=' . $pictureWidth);

/*----------------------------------------------------------------------------
//
// faz o que tem que fazer...
//
//----------------------------------------------------------------------------*/

//
//loop principal
$inicio = time();
criarCacheDiretorio($dirfotos, 1);
$fim = time();

//
// �ltimo relat�rio e fim da execu��o
enviarImediatamente("<hr><br /><b>Tempo total para convers&atilde;o: " . ($fim - $inicio) . " segundos</b><br /><br />");

//
//
// envia o final da p�gina, desde o fim da tabela at� o �ltimo </html>
enviarFinalPagina();
exit;

//----------------------------------------------------------------------------//
//
// Fun��es de processamento de midia.
//
//----------------------------------------------------------------------------//

function criarCacheDiretorio($currentDir, $howDeep) {
	global $dircache, $thumbWidth, $pictureWidth;

	// dou 600 segundos a cada diret�rio - se passar disso o programa morre.
	set_time_limit(600);

	// se for um diret�rio e existir um arquivo de nome 'naolistar.info' no diret�rio, nao faz nada.
	if (file_exists($currentDir . '/' . '/naolistar.info')) return;

	// se for um diret�rio e n�o existir um arquivo de nome 'listar.info', nao faz nada.
	if (!file_exists($currentDir . '/' . '/listar.info')) return;

	// informa o in�cio do diret�rio e o n�mero de fotos
	$numThumbs = countthumbs($currentDir);
	enviarInicioDiretorio($currentDir, $numThumbs);

	// se este diret�rio ainda n�o existe nos caches, cria logo de uma vez para n�o dar confus�o depois...
	if ($howDeep > 1) 
	{
		criarDiretorioSeNecessario($dircache . '.flv/' . obterCaminhoAnterior($currentDir, $howDeep - 1));
		criarDiretorioSeNecessario($dircache . '.w=' . $thumbWidth . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1));
		criarDiretorioSeNecessario($dircache . '.w=' . $pictureWidth . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1));
	}

	// este � o loop principal.
	$listaArquivos = opendir($currentDir);
	while ($arquivo = readdir($listaArquivos))
	{

		// n�o fa�o nada caso o arquivo n�o seja .jpeg, .mpeg ou um diret�rio diferente dos ponteiros default (. e ..)
		if ($arquivo == '.' || $arquivo == '..') continue;
		if (!preg_match('/(jpg|mpg|jpeg|mpeg)$/', strtolower($arquivo)) && !is_dir($currentDir . '/' . $arquivo)) continue;

		if (is_dir($currentDir . '/' . $arquivo)) 
		{
			criarCacheDiretorio($currentDir . '/' . $arquivo, $howDeep+1);
		}
		elseif (preg_match('/jpg$|JPG$/', $arquivo))
		{
			criarPictureThumb($currentDir, $arquivo, $howDeep, $thumbWidth);
			criarPictureThumb($currentDir, $arquivo, $howDeep, $pictureWidth);
		}
		elseif (preg_match('/mpg$|mpeg$|MPG$|MPEG$/', $arquivo))
		{
			criarMpegThumb($currentDir, $arquivo, $howDeep, $thumbWidth);
			criarFlv($currentDir, $arquivo, $howDeep);
		}

	}
	closedir($listaArquivos);

	enviarFinalDiretorio();
}

//
// Fun��es de processamento de midia.
//
//

function criarFlv($currentDir, $mov, $howDeep) {
	global $dircache;

	// Localiza��o e nome do arquivo de sa�da no cache
	if ($howDeep > 1) 
	{
		$cacheFile = $dircache . '.flv' . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1) . '/' . $mov;
	}
	else
	{
		$cacheFile = $dircache . '.flv' . '/' . $mov;
	}


	//
	// nome do arquivo
	$infile = $currentDir . '/' . $mov;

	// obt�m o nome base deste arquivo (sem a extens�o)
	$cacheFile_info = pathinfo($cacheFile);
	$cacheFile_name = basename($cacheFile_info['basename'], '.' . $cacheFile_info['extension']);

	// ff_flvFile � o arquivo de sa�da em formato flv
	$ff_flvFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.flv';

	// se este filme ainda n�o tem seu flv no cache, cria...
	if (!file_exists($ff_flvFile))
	{
		enviarInfoMidia($mov, 320);

		// dispara a convers�o do v�deo para o formato flv
		// a convers�o � feita em duas etapas. 
		// Primeiro, ffmpeg transforma de mpg em flv. 
		// Por fim, flvtool2 insere meta-tags(?) para permitir a navega��o no filme quando exibido via flvprovider.php...
		exec("ffmpeg-flv.exe -i \"$infile\" -b 360 -r 25 -s 320x240 -hq -deinterlace  -ab 56 -ar 22050 -ac 1  \"$ff_flvFile\" 2>&1", $output);
		exec("flvtool2.exe u \"$ff_flvFile\" \"$ff_flvFile\" 2>&1", $output);

		// txtFile � o arquivo com informa��es gerado durante a convers�o. 
		$txtFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.txt';

		// cria um arquivo com a descri��o deste filme.
		$output[0] = $mov;
		$output[1] = preg_replace('/Duration/', 'Dura��o', $output[1]);
		$output[2] = preg_replace('/Stream \#0\.0\:/', '', $output[2]);
		$output[3] = preg_replace('/Stream \#0\.0\:/', '', $output[3]);
		$th = fopen($txtFile, 'wb');
		$output = array_slice($output, 0, 4); // quero s� informa��es sobre o filme
		fwrite($th, implode("\n", $output));
		fclose($th);
	}
}

function criarPictureThumb($currentDir, $pic, $howDeep, $width) {
	global $dircache;

	// Localiza��o e nome do arquivo de sa�da no cache
	if ($howDeep > 1) 
	{
		$cacheFile = $dircache . ".w=$width" . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1) . '/' . $pic;
	}
	else
	{
		$cacheFile = $dircache . ".w=$width" . '/' . $pic;
	}

	// se esta foto ainda n�o est� no cache, cria
	if (!file_exists($cacheFile))
	{
		enviarInfoMidia($pic, $width);

		// l� a foto no diret�rio original
		$im    = imagecreatefromjpeg($currentDir . '/' . $pic);

		// calcula as novas dimens�es da imagem a partir das dimens�es da imagem original
		$old_x=imageSX($im);
		$old_y=imageSY($im);
		$new_w=(int)($width);
		if (($new_w<=0) or ($new_w>$old_x)) {
			$new_w=$old_x;
		}
		$new_h=($old_x*($new_w/$old_x));
		if ($old_x > $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);

		}
		if ($old_x < $old_y) {
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}

		// cria a nova imagem com as dimens�es calculadas. o fundo ser� darkorange...
		$thumb=ImageCreateTrueColor($thumb_w + 2,$thumb_h + 2);
		$darkorange = imagecolorallocate($thumb, 255, 140, 0);
		imagefill($thumb, 0, 0, $darkorange);
		imagecopyresized($thumb,$im,1,1,0,0,$thumb_w-1,$thumb_h-1,$old_x,$old_y);
		imagejpeg($thumb, $cacheFile, 75);
		imagedestroy($im);
		unset($im);
	}
}

function criarMpegThumb($currentDir, $mov, $howDeep, $width) {
	global $dircache;

	// Localiza��o e nome do arquivo de sa�da no cache
	if ($howDeep > 1) 
	{
		$cacheFile = $dircache . ".w=$width" . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1) . '/' . $mov;
	}
	else
	{
		$cacheFile = $dircache . ".w=$width" . '/' . $mov;
	}

	// obt�m o nome base deste arquivo (sem a extens�o)
	$cacheFile_info = pathinfo($cacheFile);
	$cacheFile_name = basename($cacheFile_info['basename'], '.' . $cacheFile_info['extension']);
	// nome do arquivo que ser� efetivamente armazenado no cache.
	$ff_flvFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.jpg';

	// se este filme ainda n�o tem frame-thumb no cache, cria
	if (!file_exists($ff_flvFile))
	{
		enviarInfoMidia($mov, $width);

		// m�dia original
		$infile = $currentDir . '/' . $mov;
		
		// 
		// ff_fileOption � como eu tenho que compor o nome do arquivo de sa�da para que ffmpeg o aceite
		// ff_outFile � o arquivo de sa�da pp dito. ser� removido ao final.
		// txtFile � o arquivo com informa��es gerado durante a convers�o. ser� removido ao final.
		$ff_fileOption = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '%d.jpg';
		$ff_outFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '1.jpg';
		$txtFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.txt';

		// utilizo o ffmpeg.
		exec("ffmpeg-jpg.exe -an -img jpeg -i \"$infile\" -t 00:00:00.001 \"$ff_fileOption\" 2>&1", $output);

		// l� a frame-thumb criada anteriormente
		$im    = imagecreatefromjpeg($ff_outFile);

		// calcula as novas dimens�es da imagem a partir das dimens�es da imagem original
		$old_x=imageSX($im);
		$old_y=imageSY($im);
		if ($old_x > 0 && $old_y > 0) 
		{
			$new_w=(int)($width);
			if (($new_w<=0) or ($new_w>$old_x)) {
				$new_w=$old_x;
			}
			$new_h=($old_x*($new_w/$old_x));
			if ($old_x > $old_y) {
				$thumb_w=$new_w;
				$thumb_h=$old_y*($new_h/$old_x);

			}
			if ($old_x < $old_y) {
				$thumb_w=$old_x*($new_w/$old_y);
				$thumb_h=$new_h;
			}
			if ($old_x == $old_y) {
				$thumb_w=$new_w;
				$thumb_h=$new_h;
			}

			// cria a nova imagem com as dimens�es calculadas. o fundo ser� darkorange...
			$thumb=ImageCreateTrueColor($thumb_w + 2,$thumb_h + 2);
			$darkorange = imagecolorallocate($thumb, 255, 140, 0);
			imagefill($thumb, 0, 0, $darkorange);
			imagecopyresized($thumb,$im,1,1,0,0,$thumb_w-1,$thumb_h-1,$old_x,$old_y);
			imagejpeg($thumb, $ff_flvFile, 75);
			imagedestroy($im);
			unset($im);

			// cria um arquivo com a descri��o deste filme. de repente eu consigo usar, n�?
			$output[0] = $mov;
			$output[1] = preg_replace('/Duration/', 'Dura��o', $output[1]);
			$output[2] = preg_replace('/Stream \#0\.0\:/', '', $output[2]);
			$output[3] = preg_replace('/Stream \#0\.0\:/', '', $output[3]);
			$th = fopen($txtFile, 'wb');
			$output = array_slice($output, 0, 4); // quero s� informa��es sobre o filme
			fwrite($th, implode("\n", $output));
			fclose($th);

		}
		else
		{
			// alguma coisa deu errado.  exibe o no-thumb
			$thumb=imagecreatefromjpeg(RELATIVE_PATH . './imagens/no-picture.jpg');
		}

		// apaga os rastros...
		unlink($ff_outFile);
	//	unlink($ff_txtFile);
	}
}

//----------------------------------------------------------------------------//
//
// Fun��es de envio de texto para a p�gina
//
//----------------------------------------------------------------------------//

//
// Do come�o da html at� o in�cio da table � responsabilidade do template page_header.tpl
function enviarInicioPagina()
{
	global $dirfotos, $homepage;

	$homepage->assign('tituloPagina', '.: Manuten&ccedil;&atilde;o do cache de M&iacute;dia :.');
	$homepage->assign('tituloTabela', " .: Processamento do diret&oacute;rio $dirfotos :. ");
	$homepage->assign('classPagina', 'admin');
	$homepage->assign('displayGoogle', false);
	$homepage->assign('displayFindaMap', false);
	$homepage->assign('displayFortune', false);
	$homepage->assign('relativePATH', RELATIVE_PATH);

	echo $homepage->fetch('page_header.tpl');
	echo '<td>';

	// envia tudo para o browser sem esperar...
	flush();
}

function enviarInicioDiretorio($currentDir, $totalFotos)
{
	global $homepage;

	$homepage->assign('currentDir', $currentDir);
	$homepage->assign('totalFotos', $totalFotos);

	echo $homepage->fetch('admin/pictures/start_dir.tpl');

	flush();
}

function enviarInfoMidia($midiaFile, $width)
{
	global $homepage;

	$homepage->assign('midiaFile', $midiaFile);
	$homepage->assign('width', $width);

	echo $homepage->fetch('admin/pictures/midia_file.tpl');

	flush();
}

function enviarFinalDiretorio()
{
	global $homepage;

	echo '</td>';
	echo $homepage->fetch('admin/pictures/end_dir.tpl');

	flush();
}

function enviarFinalPagina()
{
	global $homepage;

	echo $homepage->fetch('page_footer.tpl');

	flush();
}

function enviarImediatamente($text)
{
	echo $text;
	flush();
}

//----------------------------------------------------------------------------//
//
// Fun��es auxiliares ao processamento
//
//----------------------------------------------------------------------------//

//
// conta o n�mero de poss�veis thumbs a serem exibidos em um determinado diret�rio.
// os poss�veis thumbs s�o os arquivos de extens�o $ext e os diret�rios.
function countthumbs($dir)
{
  
	$pattern = $dir . "/*.jpg";
	$jpeg = glob($pattern, GLOB_NOSORT);
	$pattern = $dir . "/*.JPG";
	$JPEG = glob($pattern, GLOB_NOSORT);
	$pattern = $dir . "/*.mpg";
	$mpeg = glob($pattern, GLOB_NOSORT);
	$pattern = $dir . "/*.MPG";
	$MPEG = glob($pattern, GLOB_NOSORT);
	$pattern = $dir . "/*/listar.info";
	$dirs = glob($pattern, GLOB_NOSORT);
	
	if ($jpeg || $JPEG || $dirs || $mpeg || $MPEG )
	{
		return(count($jpeg) + count($JPEG) + count($dirs) + count($mpeg) + count($MPEG));
	}
	else
	{
		return 0 ;
	}

}

//
// recebe um path e devolve os �ltimos n diretorios
function obterCaminhoAnterior($theFile, $howDeep) {
	$pathParts = preg_split('!/|\\\\!', $theFile);
	$pathLen = count($pathParts);
	$NAnteriores = $pathParts[$pathLen - $howDeep];
	for ($i = 1; $i < $howDeep; $i++) {
		$NAnteriores .= '/' . $pathParts[$pathLen - $howDeep + $i];
	}
	return $NAnteriores;
}

// 
// cria um diret�rio se for necess�rio
function criarDiretorioSeNecessario($theCacheDir) {
	if (!file_exists($theCacheDir))
	{
		enviarImediatamente("<b> Vou criar o diret�rio: $theCacheDir...</b><br />\n");
		mkdir($theCacheDir, 0777, true);
	}
}

//-- vi: set tabstop=4 shiftwidth=4 showmatch: 

?>
