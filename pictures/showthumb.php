<?php
// kokesh@kokeshnet.com 2004

//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
define('HOMEPAGE_PATH', './../');
define('RELATIVE_PATH', './../');

//
// configura��es para exibi��o das fotos
$fotos = simplexml_load_file(RELATIVE_PATH . 'configs/pictures.xml');
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
if (!file_exists($dircache . '/' . dirname($pic))) 
{
	mkdir($dircache . '/' . dirname($pic), 0777, true);
}

// se esta foto ainda n�o est� no cache, cria
if (!file_exists($dircache . '/' . $pic))
{
	// l� a foto no diret�rio original
	$im    = imagecreatefromjpeg($dirfotos . '/' . $pic);

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
	imagejpeg($thumb, $dircache . '/' . $pic, 75);
	imagedestroy($im);
	unset($im);
}
else
{
	$thumb = imagecreatefromjpeg($dircache . '/' . $pic);
}

// envia a imagem lida do cache (ou criada no cache) para o browser
header("Content-type: image/jpeg");
imagejpeg($thumb,"",75);
imagedestroy($thumb);
unset($thumb);

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>

