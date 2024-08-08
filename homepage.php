<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
require_once('common.php');

// 
// verifica se houve pedido de upload...
if (isset($_FILES['userfile']['name']) && $_FILES['userfile']['name'] != '') 
{
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
        prepare_msgAlerta('success', "Arquivo $uploadfile carregado com sucesso");
    else 
        prepare_msgAlerta('warning', "Erro ao carregar arquivo $uploadfile");
}

// instancia a página informada a partir do id e coloca o título na página...
if (isset($requests['id'])) {
    $_idPagina = $requests['id'];
}
else
{
    $_idPagina = 1;
}

// abro e inicializo minha página
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
// se esta página apresentar fortune, obtém uma...
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

// exibe o form de seleção de cores se:
// - foi requisitado na url
// - a página está configurada para exibí-lo
if ( (isset($requests['selectcolor']) && $requests['selectcolor'] == 'sim') || ($pagina->displaySelectColor == 1))
{
    $homepage->assign('displaySelectColor', 1);
    // lê os elementos coloridos e os pares de cores
    $homepage->assign('elementosColoridos', elementoColorido::getArray());
    $homepage->assign('paresCores', RGBColor::getArray());
}
else
{
    $homepage->assign('displaySelectColor', 0);
}

// exibe o form do dicionário se:
// - foi requisitado na url
// - a página está configurada para exibí-lo (ainda não está implementado).
if ( (isset($requests['dicionario']) && $requests['dicionario'] == 'sim') ) // || ($pagina->displayDicionario == 1))
{
    $homepage->assign('displayDicionario', 1);
}
else
{
    $homepage->assign('displayDicionario', 0);
}

// le os cookies e passa para a página a ser carregada.
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

// Leio todos os elementos da página e percorro-os, quebrando por categoria e grupo e os vou incluindo no template
$elementosLidos = $pagina->getBigArray();
$categAnterior = 'xx';
$grupoAnterior = 'xx';

foreach ($elementosLidos as $elemento) {

    // realiza a quebra por grupo. 
    // para cada grupo, tenho que guardar sua descricao para comparar novamente e seu tipo para o template.
    // para o primeiro grupo da primeira categoria, como obviamente ainda não tenho elementos, faço uma quebra falsa.
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
    // para a primeira categoria, como obviamente não tenho grupos, faço uma quebra falsa.
    if ( $elemento['descricaoCategoria'] != $categAnterior ) {
        if ( isset ( $grupos ) ) {
            $descricoesGrupos[] = array('index' => $posicaoAnterior, 'idGrupo' => $elemento['idGrupo'], 'grupos' => $grupos);
            unset($grupos);
        }
        $categAnterior = $elemento['descricaoCategoria'];
        $posicaoAnterior = $elemento['posPagina'];
        $descricoesCategorias[] = array('index' => $elemento['posPagina'], 'categoria' => $elemento['descricaoCategoria']);
    }

    // incluo os elementos de um grupo num array que na quebra de grupos será adicionado a um array de grupos.
    // este array de grupos será incluido num array co-irmão do array de categorias a cada quebra de categoria.
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
                   'urlImagem' => $elemento['urlElemento'],
                   'descricaoImagem' => $elemento['descricaoElemento'],
                   'nomeTemplate' => $elemento['templateFileName'],
                   'urlElementoSSL' => $elemento['urlElementoSSL'], 
                   'urlElementoSVN' => $elemento['urlElementoSVN']
            );
}

// neste tipo de loop com quebra no início, fica sempre faltando adicionar os grupos da última categoria.
$grupos[] = array('grupo' => $grupoAnterior, 'idtipoGrupo' => $idTipoGrupoAnterior, 'elementos' => $elementos);
$descricoesGrupos[] = array('index' => $posicaoAnterior, 'idGrupo' => $elemento['idGrupo'], 'grupos' => $grupos);

// elementos enviados ao template
$homepage->assign('descricoesCategorias', $descricoesCategorias);
$homepage->assign('descricoesGrupos', $descricoesGrupos);
$homepage->assign('includePATH', INCLUDE_PATH);
if (isset($requests['gr']))
{
    $homepage->assign('gr', $requests['gr']);
}

$homepage->display('index.tpl');
?>

