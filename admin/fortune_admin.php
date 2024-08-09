<?php
//
// Definições necessárias para todos os programas, principalmente paths e localizações de arquivos/classes.  
// Carregar apenas uma vez.
require_once('../common.php');

// insere a classe de fortunes.
require($include_path . "class_fortune.php");

// verifica se há cookies e, neste caso, cria os estilos adicionais.
$cookedStyles = ':root {';
$colorCookies = cookedStyle::getArray($_idPagina);
if ($colorCookies) 
{
    foreach ($colorCookies as $elementoColorido) {
        $cookedStyles .= $elementoColorido['root_var'] . ': ' . $elementoColorido['color'] . '; ';
    }
}
$cookedStyles .= '}';
$homepage->assign('cookedStyles', $cookedStyles);

// Obtém a página administrativa
$admPag = new pagina(ID_ADM_PAG);

$homepage->assign('displayImagemTitulo', '1');
$homepage->assign('tituloPagina', ':: Administra&ccedil;&atilde;o de Fortunes');
$homepage->assign('tituloTabela', ' :: Biscoitinhos da sorte :: administra&ccedil;&atilde;o ::');
$homepage->assign('classPagina', $admPag->classPagina);
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('displaySelectColor', 0);

echo $homepage->fetch("page_header.tpl");

// 
// verifica se houve pedido de upload...
if (isset($_FILES['userfile']['error']) && $_FILES['userfile']['error'] > 0) 
{
	switch ($_FILES['userfile']['error']) {
		case UPLOAD_ERR_INI_SIZE:
			echo "O arquivo tem tamanho maior do que o permitido pelo servidor.<br />";
			break;
		case UPLOAD_ERR_FORM_SIZE:
			echo "O arquivo tem tamanho maior do que o permitido pelo formul&aacute;rio HTML.<br />";
			break;
		case UPLOAD_ERR_PARTIAL:
			echo "O arquivo foi apenas parcialmente carregado.<br />";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "Nenhum arquivo foi carregado.<br />";
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			echo "Não h&aacute; diret&oacute;rio tempor&aacute;rio. Contacte seu provedor.<br />";
			break;
		case UPLOAD_ERR_CANT_WRITE:
			echo "N&atilde;o foi poss&iacute;vel gravar o arquivo em disco. Contacte seu provedor ou o administrador do sistema.<br />";
			break;
		case UPLOAD_ERR_EXTENSION:
			echo "O arquivo tem extens&atilde;o proibida.";
			break;
	}
}
elseif ( isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != '') 
{
	$file = basename($_FILES['userfile']['name']);
        echo "$file<br />";
        echo "uploaddir: $uploaddir<br />";
	$uploadfile = $uploaddir . $file;

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile) === FALSE)
	{
		echo "<div class=\"itemLateral\">Erro ao carregar arquivo $uploadfile. </div>";
	}

	$f = new Fortune;
	$totais = array('totalQuotes' => 0, 
					'totalCreated' => 0,
					'totalFailSize' => 0,
					'totalFailExists' => 0);

	$fileinfo = pathinfo( $fortune_path . $file );

	$auxtotais = $f->criarFortunes( $uploadfile );
	$totais['totalQuotes'] += $auxtotais['totalQuotes'];
	$totais['totalCreated'] += $auxtotais['totalCreated'];
	$totais['totalFailSize'] += $auxtotais['totalFailSize'];
	$totais['totalFailExists'] += $auxtotais['totalFailExists'];
	echo "</th></tr>";

	echo "<div class=\"tituloLateral\" style=\"width: 100%; float: none; font-size: 0.9em;\">Totais";
	echo "<br />" . str_repeat(".", 30) . " " . $totais['totalQuotes'] . " fortunes indexados!<br />"; flush();
	echo str_repeat(".", 30) . " " . $totais['totalCreated'] . " fortunes inseridos na base de dados<br />"; flush();
	echo str_repeat(".", 30) . " " . $totais['totalFailSize'] . " fortunes recusados por terem tamanho muito grande<br />"; flush();
	echo str_repeat(".", 30) . " " . $totais['totalFailExists'] . " fortunes recusados por j&aacute; existirem na base<br />"; flush();
	echo "</div>";
	echo "<hr />";
}

// uma pequena estatística do numero de fortunes na base
$nFortunes = Fortune::getCount();
echo "<div class=\"tituloLateral\" style=\"width: 100%; float: none; font-size: 0.9em;\">N&uacute;mero de fortunes armazenados na base:";
echo " $nFortunes</div><br />";

// exibe um novo form para carga de um arquivo
$homepage->assign('cabecalhoUpload', ' :: Arquivo de fortunes:');
$homepage->assign('maxfilesize', 500000);
$homepage->assign('form_action', 'fortune_admin.php');
$homepage->assign('filenamesize', 60);
$homepage->assign('uploadLabel', 'Carrega Fortunes!');
echo $homepage->fetch('file_upload.tpl');

// Finalização...
echo $homepage->fetch("page_footer.tpl");

//-- vim: set shiftwidth=4 tabstop=4 showmatch nowrap: 

?>	
