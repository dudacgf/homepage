<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: max-age: 10' );
require_once('auth_force.php');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;
use Shiresco\Homepage\Fortunes as Fortunes;
use Shiresco\Homepage\Pagina as Pagina;

// qual o tema que foi solicitado?
if (isset($requests['idTema']))
    $_idTema = $requests['idTema'];
else
    $_idTema = 1;

// carrega o tema
$tema = new Temas\Temas($_idTema);
$homepage->assign('tema', $tema);

// obtenho atributos da página de exibição do tema (admin/tema_frame.tpl)
$pagina = new Pagina\Pagina(ID_TEMA_PAG);
$homepage->assign('idTema', $_idTema);
$homepage->assign('tituloPagina', $pagina->tituloPagina);
$homepage->assign('tituloTabela', $pagina->tituloTabela);
$homepage->assign('temaPagina', $tema->nome);
$homepage->assign('displayImagemTitulo', $pagina->displayImagemTitulo);

// se esta página apresentar fortune, obtém uma...
if ($pagina->displayFortune != 0)
    $homepage->assign("fortune", array('autorFortune' => 'o administrador',
                         'textoFortune' => 'sem fortunes. uma pausa para seus próprios augúrios.'));

// Leio as categorias e grupos da página
$links = $pagina->lerPagina();
$homepage->assign('descricoesCategorias', $links['descricoesCategorias']);
$homepage->assign('descricoesGrupos', $links['descricoesGrupos']);

// elementos enviados ao template
$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('displayFortune', '1');
$homepage->display('admin/tema_frame.tpl');
?>

