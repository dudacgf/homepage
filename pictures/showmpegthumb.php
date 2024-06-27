<?php
//
// configura��es para exibi��o das fotos
$fotos = simplexml_load_file('../configs/pictures.xml');
foreach ($fotos as $fotoDir)
{
   if ($fotoDir['ID'] == "1")
   {
	   $dirfotos = (string) $fotoDir->path;
	   $dircache = (string) $fotoDir->cacheDir;
	   $colunas = (int) $fotoDir->colunas;
	   $linhas = (int) $fotoDir->linhas;
   }
}
// obt�m a largura solicitada...
$width = ( isset($_REQUEST['width']) ) ? $_REQUEST['width'] : 120 ;

// Se ainda n�o existe cache para este tamanho, cria
$dircache .= ".w=$width" ;
if (!file_exists($dircache))
{
	mkdir($dircache, 0777, true);
}

// se este diret�rio ainda n�o existe no cache, cria
$pic = $_REQUEST['pic'];
$pic = str_replace('..', '', $pic);
if (!file_exists($dircache . '/' . dirname($pic))) 
{
	mkdir($dircache . '/' . dirname($pic), 0777, true);
}

// Localiza��o e nome do arquivo de sa�da no cache
$cacheFile = $dircache . $pic;

// obt�m o nome base deste arquivo (sem a extens�o)
$cacheFile_info = pathinfo($cacheFile);
$cacheFile_name = basename($cacheFile_info['basename'], '.' . $cacheFile_info['extension']);

// Arquivo com as informa��es que ser�o exibidas no popup...
$txtFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.txt';

// se este filme ainda n�o tem frame-thumb no cache, cria
if (!file_exists($cacheFile))
{
	// cria uma frame a partir do filme original
	$infile = $dirfotos . $pic;
	
	// 
	// ff_fileOption � como eu tenho que compor o nome do arquivo de sa�da para que ffmpeg o aceite
	// ff_outFile � o arquivo de sa�da pp dito. ser� removido ao final.
	// txtFile � o arquivo com informa��es gerado durante a convers�o. ser� removido ao final.
	$ff_fileOption = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '%d.jpg';
	$ff_outFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '1.jpg';
	$ff_flvFile = $cacheFile_info['dirname'] . '/' . $cacheFile_name . '.flv';
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
		imagejpeg($thumb, $cacheFile, 75);
		imagedestroy($im);
		unset($im);

		// cria um arquivo com a descri��o deste filme. de repente eu consigo usar, n�?
		$output[0] = $pic;
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
		$thumb=imagecreatefromjpeg('./imagens/no-picture.jpg');
	}

	// apaga os rastros...
	unlink($ff_outFile);
//	unlink($ff_txtFile);
}
else
{
	$thumb = imagecreatefromjpeg($cacheFile);
}

// envia a imagem lida do cache (ou criada no cache) para o browser
header("Content-type: image/jpeg");
imagejpeg($thumb,"", 100);
imagedestroy($thumb);
unset($thumb);

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
