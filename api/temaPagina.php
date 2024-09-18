<?php
header('Expires: ' . date( DATE_RFC1123, strtotime( "+1 hour" )));
header('Cache-Control: no-cache');
header('Content-Type: text/css');
require_once('../common.php');

use Shiresco\Homepage\Temas as Temas;

if (!isset($requests['nome'])) 
    $_tema = Temas\Temas::obterPorNome('Default');
else
    $_tema = Temas\Temas::obterPorNome($requests['nome']);
$homepage->assign('tema', $_tema);

// só uso as variáveis root temporárias no caso de estar editando um tema
if (isset($_SERVER['HTTP_REFERER']) and 
    (strpos($_SERVER['HTTP_REFERER'], 'tema_edit') > 0 or 
     strpos($_SERVER['HTTP_REFERER'], 'tema_frame') > 0))
    $homepage->assign('rootvars', Temas\TemaCSS::obterTempCSS($_tema->id));
else
    $homepage->assign('rootvars', Temas\TemaCSS::obterCSS($_tema->id));

$homepage->display('temaPagina.tpl');
?>
