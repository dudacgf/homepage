<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
//
// Defini��es necess�rias para todos os programas, principalmente paths e localiza��es de arquivos/classes.  
// Carregar apenas uma vez.
if (!defined('RELATIVE_PATH'))
{
	define("RELATIVE_PATH", getcwd() . "/");
}
include_once(RELATIVE_PATH . 'common.php');

// 
// verifica se houve pedido de upload...
if (isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != '') 
{
	$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
   	{
		$homepage->assign('msgAlerta', "Arquivo $uploadfile carregado com sucesso");
	} 
	else 
	{
		$homepage->assign('msgAlerta', "Erro ao carregar arquivo $uploadfile");
	}
}

// instancia a p�gina informada a partir do id e coloca o t�tulo na p�gina...
if (isset($requests['id'])) {
	$_idPagina = $requests['id'];
}
else
{
	$_idPagina = 1;
}

// abro e inicializo minha p�gina
$pagina = new pagina($_idPagina);
$homepage->assign('idPagina', $_idPagina);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('classPagina', $pagina->classPagina);
$homepage->assign('displayGoogle', $pagina->displayGoogle);
$homepage->assign('displayFindaMap', $pagina->displayFindaMap);
$homepage->assign('displayFortune', $pagina->displayFortune);
$homepage->assign('displayImagemTitulo', $pagina->displayImagemTitulo);
$homepage->assign('displaySelectColor', $pagina->displaySelectColor);

//
// se esta p�gina apresentar fortune, obt�m uma...
if ($pagina->displayFortune != 0) {
	require($include_path . "class_fortune.php");
	$f = new fortune;
	$biscoitinho = $f->fortune;
	if (strpos($biscoitinho, "--") > 0) 
	{
		$biscoitinho = str_replace("--", "<b>--", $biscoitinho) . "</b>";
	}
	$homepage->assign("fortuneCookie", $biscoitinho);
}

// exibe o form de sele��o de cores se:
// - foi requisitado na url
// - a p�gina est� configurada para exib�-lo
if ( (isset($requests['selectcolor']) && $requests['selectcolor'] == 'sim') || ($pagina->displaySelectColor == 1))
{
	$homepage->assign('displaySelectColor', 1);
	// l� os elementos coloridos e os pares de cores
	$homepage->assign('elementosColoridos', elementoColorido::getArray());
	$homepage->assign('paresCores', RGBColor::getArray());
}
else
{
	$homepage->assign('displaySelectColor', 0);
}

// exibe o form do dicion�rio se:
// - foi requisitado na url
// - a p�gina est� configurada para exib�-lo (ainda n�o est� implementado).
if ( (isset($requests['dicionario']) && $requests['dicionario'] == 'sim') ) // || ($pagina->displayDicionario == 1))
{
	$homepage->assign('displayDicionario', 1);
}
else
{
	$homepage->assign('displayDicionario', 0);
}

// le os cookies e passa para a p�gina a ser carregada.
$cookedStyles = '';
$colorCookies = cookedStyle::getArray($_idPagina);
if ($colorCookies) 
{
	foreach ($colorCookies as $selector => $colorCookie) {
		$cookedStyles .= implode("\n", $colorCookie) . "\n}\n";
	}
}
$homepage->assign('cookedStyles', $cookedStyles);

// Leio todos os elementos da p�gina e percorro-os, quebrando por categoria e grupo e os vou incluindo no template
$elementosLidos = $pagina->getBigArray();
$categAnterior = 'xx';
$grupoAnterior = 'xx';

foreach ($elementosLidos as $elemento) {

    // realiza a quebra por grupo. 
    // para cada grupo, tenho que guardar sua descricao para comparar novamente e seu tipo para o template.
    // para o primeiro grupo da primeira categoria, como obviamente ainda n�o tenho elementos, fa�o uma quebra falsa.
    if ( $elemento['descricaoGrupo'] != $grupoAnterior ) {
        if ( isset ( $elementos ) ) {
        //    localEcho( $elementos, "ELEMENTOS ($grupoAnterior)" );
            $grupos[] = array('grupo' => $grupoAnterior, 'idtipoGrupo' => $idTipoGrupoAnterior, 'elementos' => $elementos);
            unset ( $elementos );
        }
        $grupoAnterior = $elemento['descricaoGrupo'];
        $idTipoGrupoAnterior = $elemento['idTipoGrupo'];
    }

    // realiza a quebra por categoria.
    // para cada categoria, tenho que guardar sua descricao para comparar novamente e sua posicao na pagina.
    // para a primeira categoria, como obviamente n�o tenho grupos, fa�o uma quebra falsa.
    if ( $elemento['descricaoCategoria'] != $categAnterior ) {
        if ( isset ( $grupos ) ) {
            $descricoesGrupos[] = array('index' => $posicaoAnterior, 'idGrupo' => $elemento['idGrupo'], 'grupos' => $grupos);
            unset($grupos);
        }
        $categAnterior = $elemento['descricaoCategoria'];
        $posicaoAnterior = $elemento['posPagina'];
        $descricoesCategorias[] = array('index' => $elemento['posPagina'], 'categoria' => $elemento['descricaoCategoria']);
    }

    // incluo os elementos de um grupo num array que na quebra de grupos ser� adicionado a um array de grupos.
    // este array de grupos ser� incluido num array co-irm�o do array de categorias a cada quebra de categoria.
	$elementos[] = array( 
                   'idElemento' => $elemento['idElemento'],
                   'descricaoLink' => $elemento['descricaoElemento'],
                   'tipoElemento' => $elemento['idTipoElemento'],
                   'posGrupo' => $elemento['posGrupo'], 
                   'linkURL' => $elemento['urlElemento'],
                   'localLink' => $elemento['urlElementoLocal'], 
                   'dicaLink' => $elemento['dicaElemento'], 
                   'targetLink' => $elemento['urlElementoTarget'], 
                   'nomeForm' => $elemento['formNome'], 
                   'descricaoForm' => $elemento['descricaoElemento'],
                   'acao' => $elemento['urlElemento'],
                   'nomeCampo' => $elemento['formNomeCampo'],
                   'tamanhoCampo' => $elemento['formTamanhoCampo'], 
                   'breakBefore' => $elemento['separadorBreakBefore'], 
                   'descricaoSeparador' => $elemento['descricaoElemento'],
                   'rssURL' => $elemento['urlElemento'],
                   'rssItemNum' => $elemento['rssItemNum'], 
                   'urlImagem' => $elemento['urlElemento'],
                   'descricaoImagem' => $elemento['descricaoElemento'],
                   'nomeTemplate' => $elemento['templateFileName'],
                   'urlElementoSSL' => $elemento['urlElementoSSL'], 
                   'urlElementoSVN' => $elemento['urlElementoSVN']
            );
}

// neste tipo de loop com quebra no in�cio, fica sempre faltando adicionar os grupos da �ltima categoria.
$grupos[] = array('grupo' => $grupoAnterior, 'idtipoGrupo' => $idTipoGrupoAnterior, 'elementos' => $elementos);
$descricoesGrupos[] = array('index' => $posicaoAnterior, 'idGrupo' => $elemento['idGrupo'], 'grupos' => $grupos);

// elementos enviados ao template
$homepage->assign('descricoesCategorias', $descricoesCategorias);
$homepage->assign('descricoesGrupos', $descricoesGrupos);
/*
if ( $_idPagina == 5 ) 
{
	$homepage->assign('relativePATH', '/');
}
else
{
	$homepage->assign('relativePATH', '/');
}
*/
$homepage->assign('relativePATH', basename(RELATIVE_PATH) . '/');
if (isset($requests['gr']))
{
	$homepage->assign('gr', $requests['gr']);
}

$homepage->display('index.tpl');

//-- vi: set tabstop=4 shiftwidth=4 showmatch: 

?>

