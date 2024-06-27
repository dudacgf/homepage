<?php

//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

//
// configurações para exibição das fotos
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

if (isset($requests['dir'])) 
{
	$requests['dir'] = str_replace('..', '', $requests['dir']);
	$dirfotos .= '/' . html_entity_decode($requests['dir']);
	$dirfotos = str_replace(array('\\', '//'), '/', $dirfotos);
}
else
{
	$requests['dir'] = '/';
}

//
// só continua se não existir um arquivo de nome naolistar.info no diretório
if (file_exists($dirfotos . '/naolistar.info')) {
	die('hacking attempt!!!');
}

// 
// só continua se existir um arquivo de nome listar.info
if (!file_exists($dirfotos . '/listar.info')) {
	die('hacking attempt!!!');
}

if (isset($requests['pag']))
{
	$pag = $requests['pag'];
	if ($pag < 1) $pag = 1;
}
else
{	
	$pag = 1;
}

// limpa o cache de tipo de arquivos - provavelmente desnecessário. vou pesquisar mais tarde.
clearstatcache();

// inicializações. 
$numThumbs = countthumbs($dirfotos);
$maxFotos = $colunas * $linhas;
$numPaginas =  ceil($numThumbs / $maxFotos);
$primoThumb = $maxFotos * ($pag - 1) + 1;
$ultimoThumb = $maxFotos * $pag;
$nFotos = 1;
$thumbNum = 0;

// este é o loop principal.
$listaArquivos = opendir($dirfotos);
while ($arquivo = readdir($listaArquivos))
{
	// não faço nada caso o arquivo não seja .jpeg ou um diretório diferente dos ponteiros default (. e ..)
	if ($arquivo == '.' || $arquivo == '..') continue;
	if (!preg_match('/(jpg|mpg|jpeg|mpeg)$/', strtolower($arquivo)) && !is_dir($dirfotos . '/' . $arquivo)) continue;

	// se for um diretório e existir um arquivo de nome 'naolistar.info' no diretório, nao faz nada.
	if (is_dir($dirfotos . '/' . $arquivo) && file_exists($dirfotos . '/' . $arquivo . '/naolistar.info')) continue;

	// se for um diretório e não existir um arquivo de nome 'listar.info', nao faz nada.
	if (is_dir($dirfotos . '/' . $arquivo) && !file_exists($dirfotos . '/' . $arquivo . '/listar.info')) continue;

	// só continuo se estiver no intervalo correto para a página solicitada
	$thumbNum++;
	if ($thumbNum < $primoThumb || $thumbNum > $ultimoThumb) continue;

	// flag para verificar (no template) se há necessidade de quebra de linhas...
	$fimColuna = ($nFotos % $colunas) == 0;

	// insere dados no array
	if (is_dir($dirfotos . '/' . $arquivo)) 
	{
		$arquivos[] = array(
			'url' => "picturedir.php?dir=" . $requests['dir'] . "$arquivo&pag=1",
			'arquivo' => $arquivo,
			'fimColuna' => $fimColuna,
			'image' => RELATIVE_PATH . './imagens/folder.gif');
	}
	elseif (preg_match('/jpg$|JPG$/', $arquivo))
	{
		$arquivos[] = array(
			'image' => "showthumb.php?pic=" . $requests['dir'] . "/$arquivo&width=120",
			'arquivo' => $arquivo,
			'fimColuna' => $fimColuna,
			'url' => htmlentities("showpicture.php?pic=" . $requests['dir'] . '/' .$arquivo));
	}
	elseif (preg_match('/mpg$|mpeg$|MPG$|MPEG$/', $arquivo))
	{
		$arquivos[] = array(
			'image' => "showmpegthumb.php?pic=" . $requests['dir'] . "/$arquivo&width=120",
			'arquivo' => $arquivo,
			'fimColuna' => $fimColuna,
			'url' => htmlentities('showmpeg.php?mov=' . '/' . $requests['dir'] . '/' .$arquivo));
	}
	$nFotos++;

}
closedir($listaArquivos);

// Passa as variáveis para o template e o exibe.
$homepage->assign('tituloPaginaAlternativo', ':: ' . $requests['dir'] . ' ::');
$homepage->assign('tituloTabelaAlternativo', ':: ' . $requests['dir'] . ' ::');
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('classPagina', 'black');
$homepage->assign('arquivos', ( isset($arquivos) ) ? $arquivos : array());
$homepage->assign('dirAtual', $requests['dir']);
$homepage->assign('paganterior', $pag-1);
$homepage->assign('pagatual', $pag);
$homepage->assign('pagproxima', $pag+1);
$homepage->assign('pagpenultima', $numPaginas-1);
$homepage->assign('numpaginas', $numPaginas);

$homepage->display('pictures/picturedir.tpl');

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

//-- vi: set tabstop=4 shiftwidth=4 showmatch: 

?>
