<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: max-age: 10' );
require_once('common.php');

use Shiresco\Homepage\Fortunes as Fortunes;
use Shiresco\Homepage\Pagina as Pagina;

// instancia a página informada a partir do id
if (isset($requests['idPagina']))
    $_idPagina = $requests['idPagina'];
elseif (isset($requests['id']))
    $_idPagina = $requests['id'];
else
    $_idPagina = 1;

$pagina = new Pagina\Pagina($_idPagina);
$homepage->assign('idPagina', $_idPagina);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('temaPagina', $pagina->temaPagina);
$homepage->assign('displayFortune', $pagina->displayFortune);
$homepage->assign('displayImagemTitulo', $pagina->displayImagemTitulo);
$homepage->assign('displaySelectColor', $pagina->displaySelectColor);

// se esta página apresentar fortune, obtém uma...
if ($pagina->displayFortune != 0)
    $homepage->assign("fortune", Fortunes\Fortune::obterFortune());

// Leio as categorias e grupos da página
$links = $pagina->lerPagina();
$homepage->assign('descricoesCategorias', $links['descricoesCategorias']);
$homepage->assign('descricoesGrupos', $links['descricoesGrupos']);

// elementos enviados ao template
$homepage->assign('includePATH', INCLUDE_PATH);
if (isset($requests['gr']))
{
    $homepage->assign('gr', $requests['gr']);
}

$homepage->display('index.tpl');
?>
