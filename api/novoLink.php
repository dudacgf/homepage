<?php
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
include_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp']))
    $_idGrupo = $requests['idGrp'];
else
    throw new Exception("não posso prosseguir sem um grupo selecionado!");

// lê o grupo deste elemento
$grupo = new Pagina\Grupo($_idGrupo);
$homepage->assign('grupo', $grupo->getArray());

// obtém a página administrativa para poder usar classPagina mais tarde
$admPag = new Pagina\Pagina(ID_ADM_PAG);

// organiza o que vai passar para o template
$criarElemento = true;
$homepage->assign('elemento', array(
    'idGrupo' => $grupo->idGrupo,
    'descricaoLink' => '', 
    'linkURL' => '',
    'dicaLink' => '',
    'localLink' => 0,
    'urlElementoSSL' => 0,
    'urlElementoSVN' => 0,
    'targetLink' => ''));
$template = 'admin/link_edit.tpl';
$homepage->assign('displayImagemTitulo', '0');
$homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaCriarLink']);
$homepage->assign('tituloTabelaAlternativo', ':: ' . $lang['novoLink'] . ' ::');

$homepage->assign('includePATH', INCLUDE_PATH);
$homepage->assign('criarElemento', $criarElemento);
$homepage->assign('imagesPATH', $images_path);
$homepage->assign('classPagina', $admPag->classPagina);
$html_template = base64_encode($homepage->fetch($template));
$homepage->assign('response', '{"status": "success", "message": "' . $html_template . '"}');
$homepage->display('response.tpl');
?>
