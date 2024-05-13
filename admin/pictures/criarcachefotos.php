<?php

//----------------------------------------------------------------------------//
//
// Inicializações
//
//----------------------------------------------------------------------------//

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
define('HOMEPAGE_PATH', './../../');
define('RELATIVE_PATH', './../../');
include_once(RELATIVE_PATH . 'common.php');

//
// configurações para exibição das fotos
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
// envia para o cliente o header e o início do body, até o título da tabela.
//
enviarInicioPagina();

//
//
// cria os diretórios base do cache
//

//
// diretório de flv's para os mpegs
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
// último relatório e fim da execução
enviarImediatamente("<hr><br /><b>Tempo total para convers&atilde;o: " . ($fim - $inicio) . " segundos</b><br /><br />");

//
//
// envia o final da página, desde o fim da tabela até o último </html>
enviarFinalPagina();
exit;

//----------------------------------------------------------------------------//
//
// Funções de processamento de midia.
//
//----------------------------------------------------------------------------//

function criarCacheDiretorio($currentDir, $howDeep) {
	global $dircache, $thumbWidth, $pictureWidth;

	// dou 600 segundos a cada diretório - se passar disso o programa morre.
	set_time_limit(600);

	// se for um diretório e existir um arquivo de nome 'naolistar.info' no diretório, nao faz nada.
	if (file_exists($currentDir . '/' . '/naolistar.info')) return;

	// se for um diretório e não existir um arquivo de nome 'listar.info', nao faz nada.
	if (!file_exists($currentDir . '/' . '/listar.info')) return;

	// informa o início do diretório e o número de fotos
	$numThumbs = countthumbs($currentDir);
	enviarInicioDiretorio($currentDir, $numThumbs);

	// se este diretório ainda não existe nos caches, cria logo de uma vez para não dar confusão depois...
	if ($howDeep > 1) 
	{
		criarDiretorioSeNecessario($dircache . '.flv/' . obterCaminhoAnterior($currentDir, $howDeep - 1));
		criarDiretorioSeNecessario($dircache . '.w=' . $thumbWidth . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1));
		criarDiretorioSeNecessario($dircache . '.w=' . $pictureWidth . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1));
	}

	// este é o loop principal.
	$listaArquivos = opendir($currentDir);
	while ($arquivo = readdir($listaArquivos))
	{

		// não faço nada caso o arquivo não seja .jpeg, .mpeg ou um diretório diferente dos ponteiros default (. e ..)
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
// Funções de processamento de midia.
//
//

function criarFlv($currentDir, $mov, $howDeep) {
	global $dircache;

	// Localização e nome do arquivo de saída no cache
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

	// obtém o nome base deste arquivo (sem a extensão)
	$cacheFile_info = pathinfo($cacheFile);
	$cacheFile_name = basename($cacheFile_info['basename'], '.' . $cacheFile_info['extension']);

	// ff_flvFile é o arquivo de saída em formato flv
	$ff_flvFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.flv';

	// se este filme ainda não tem seu flv no cache, cria...
	if (!file_exists($ff_flvFile))
	{
		enviarInfoMidia($mov, 320);

		// dispara a conversão do vídeo para o formato flv
		// a conversão é feita em duas etapas. 
		// Primeiro, ffmpeg transforma de mpg em flv. 
		// Por fim, flvtool2 insere meta-tags(?) para permitir a navegação no filme quando exibido via flvprovider.php...
		exec("ffmpeg-flv.exe -i \"$infile\" -b 360 -r 25 -s 320x240 -hq -deinterlace  -ab 56 -ar 22050 -ac 1  \"$ff_flvFile\" 2>&1", $output);
		exec("flvtool2.exe u \"$ff_flvFile\" \"$ff_flvFile\" 2>&1", $output);

		// txtFile é o arquivo com informações gerado durante a conversão. 
		$txtFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.txt';

		// cria um arquivo com a descrição deste filme.
		$output[0] = $mov;
		$output[1] = preg_replace('/Duration/', 'Duração', $output[1]);
		$output[2] = preg_replace('/Stream \#0\.0\:/', '', $output[2]);
		$output[3] = preg_replace('/Stream \#0\.0\:/', '', $output[3]);
		$th = fopen($txtFile, 'wb');
		$output = array_slice($output, 0, 4); // quero só informações sobre o filme
		fwrite($th, implode("\n", $output));
		fclose($th);
	}
}

function criarPictureThumb($currentDir, $pic, $howDeep, $width) {
	global $dircache;

	// Localização e nome do arquivo de saída no cache
	if ($howDeep > 1) 
	{
		$cacheFile = $dircache . ".w=$width" . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1) . '/' . $pic;
	}
	else
	{
		$cacheFile = $dircache . ".w=$width" . '/' . $pic;
	}

	// se esta foto ainda não está no cache, cria
	if (!file_exists($cacheFile))
	{
		enviarInfoMidia($pic, $width);

		// lê a foto no diretório original
		$im    = imagecreatefromjpeg($currentDir . '/' . $pic);

		// calcula as novas dimensões da imagem a partir das dimensões da imagem original
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

		// cria a nova imagem com as dimensões calculadas. o fundo será darkorange...
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

	// Localização e nome do arquivo de saída no cache
	if ($howDeep > 1) 
	{
		$cacheFile = $dircache . ".w=$width" . '/' . obterCaminhoAnterior($currentDir, $howDeep - 1) . '/' . $mov;
	}
	else
	{
		$cacheFile = $dircache . ".w=$width" . '/' . $mov;
	}

	// obtém o nome base deste arquivo (sem a extensão)
	$cacheFile_info = pathinfo($cacheFile);
	$cacheFile_name = basename($cacheFile_info['basename'], '.' . $cacheFile_info['extension']);
	// nome do arquivo que será efetivamente armazenado no cache.
	$ff_flvFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.jpg';

	// se este filme ainda não tem frame-thumb no cache, cria
	if (!file_exists($ff_flvFile))
	{
		enviarInfoMidia($mov, $width);

		// mídia original
		$infile = $currentDir . '/' . $mov;
		
		// 
		// ff_fileOption é como eu tenho que compor o nome do arquivo de saída para que ffmpeg o aceite
		// ff_outFile é o arquivo de saída pp dito. será removido ao final.
		// txtFile é o arquivo com informações gerado durante a conversão. será removido ao final.
		$ff_fileOption = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '%d.jpg';
		$ff_outFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '1.jpg';
		$txtFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.txt';

		// utilizo o ffmpeg.
		exec("ffmpeg-jpg.exe -an -img jpeg -i \"$infile\" -t 00:00:00.001 \"$ff_fileOption\" 2>&1", $output);

		// lê a frame-thumb criada anteriormente
		$im    = imagecreatefromjpeg($ff_outFile);

		// calcula as novas dimensões da imagem a partir das dimensões da imagem original
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

			// cria a nova imagem com as dimensões calculadas. o fundo será darkorange...
			$thumb=ImageCreateTrueColor($thumb_w + 2,$thumb_h + 2);
			$darkorange = imagecolorallocate($thumb, 255, 140, 0);
			imagefill($thumb, 0, 0, $darkorange);
			imagecopyresized($thumb,$im,1,1,0,0,$thumb_w-1,$thumb_h-1,$old_x,$old_y);
			imagejpeg($thumb, $ff_flvFile, 75);
			imagedestroy($im);
			unset($im);

			// cria um arquivo com a descrição deste filme. de repente eu consigo usar, né?
			$output[0] = $mov;
			$output[1] = preg_replace('/Duration/', 'Duração', $output[1]);
			$output[2] = preg_replace('/Stream \#0\.0\:/', '', $output[2]);
			$output[3] = preg_replace('/Stream \#0\.0\:/', '', $output[3]);
			$th = fopen($txtFile, 'wb');
			$output = array_slice($output, 0, 4); // quero só informações sobre o filme
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
// Funções de envio de texto para a página
//
//----------------------------------------------------------------------------//

//
// Do começo da html até o início da table é responsabilidade do template page_header.tpl
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
// Funções auxiliares ao processamento
//
//----------------------------------------------------------------------------//

//
// conta o número de possíveis thumbs a serem exibidos em um determinado diretório.
// os possíveis thumbs são os arquivos de extensão $ext e os diretórios.
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
// recebe um path e devolve os últimos n diretorios
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
// cria um diretório se for necessário
function criarDiretorioSeNecessario($theCacheDir) {
	if (!file_exists($theCacheDir))
	{
		enviarImediatamente("<b> Vou criar o diretório: $theCacheDir...</b><br />\n");
		mkdir($theCacheDir, 0777, true);
	}
}

//-- vi: set tabstop=4 shiftwidth=4 showmatch: 

?>
