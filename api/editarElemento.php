<?php
/*
 * editarElemento.php 
 * chamada quando for solicitada a edição de um elemento na página de edição de um grupo
 * 
 * recebe - idElemento e idGrp via $requests
 * devolve - json response contendo status e, se bem sucedido, message com html de um form
 *           que será exibido via pop na página de edição de grupo (via templates)
 */
header( 'Expires: ' .  date( DATE_RFC1123, strtotime( "+1 hour" ) ));
header( 'Cache-Control: no-cache' );
header( 'Content-Type: application/json');
include_once('../common.php');

use Shiresco\Homepage\Pagina as Pagina;

// se não foi passado nenhum grupo, morre.
if (isset($requests['idGrp'])) {
	$_idGrupo = $requests['idGrp'];
    $grupo = new Pagina\Grupo($_idGrupo);
    $homepage->assign('grupo', $grupo->getArray());

    // obtém a página administrativa
    $admPag = new Pagina\Pagina(ID_ADM_PAG);

    // se tenho o id do elemento a editar/excluir/salvar, lê e passa para o template
    if (isset($requests['idElm']) && $requests['idElm'] != '0') {
        $_idElm = $requests['idElm'];
        $elemento = $grupo->elementoDeCodigo($_idElm);
        $homepage->assign('elemento', $elemento->getArray());

        switch ($elemento->tipoElemento)
        {
            case Pagina\Constantes::ELEMENTO_LINK:
                $homepage->assign('mode', 'edLnk');
                $homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarLink']);
                $homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoLink . ' ::');
                $template = 'admin/link_edit.tpl';
            break;

            case Pagina\Constantes::ELEMENTO_FORM:
                $homepage->assign('mode', 'edFrm');
                $homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarForm']);
                $homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoForm . ' ::');
                $template = 'admin/form_edit.tpl';
            break;

            case Pagina\Constantes::ELEMENTO_SEPARADOR:
                $homepage->assign('mode', 'edSrp');
                $homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarSeparador']);
                $homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoSeparador. ' ::');
                $template = 'admin/separador_edit.tpl';
            break;

            case Pagina\Constantes::ELEMENTO_IMAGEM:
                $homepage->assign('mode', 'edImg');
                $homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarImagem']);
                $homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoImagem . ' ::');
                $template = 'admin/imagem_edit.tpl';
            break;

            case Pagina\Constantes::ELEMENTO_TEMPLATE:
                $homepage->assign('mode', 'edTpt');
                $homepage->assign('tituloPaginaAlternativo', $lang['tituloPaginaEditarTemplate']);
                $homepage->assign('tituloTabelaAlternativo', ':: ' . $elemento->descricaoElemento . '::');
                $template = 'admin/template_edit.tpl';
            break;

            default:
                $requests['mode'] = 'edLnk';
                $template = 'admin/link_edit.tpl';
            break;
        }

        $homepage->assign('includePATH', INCLUDE_PATH);
        $homepage->assign('criarElemento', false);
        $homepage->assign('imagesPATH', $images_path);
        $homepage->assign('displayImagemTitulo', '0');
        $homepage->assign('temaPagina', $admPag->temaPagina);
        $html_template = base64_encode($homepage->fetch($template));
        $homepage->assign('response', '{"status": "success", "message": "' . $html_template . '"}');
    } else 
        $homepage->assign('response', '{"status": "error", "message": "Não foi informado o id do elemento a editar!"}');
} else
    $homepage->assign('response', '{"status": "error", "message": "Não foi informado o id do grupo do elemento a editar"}'); 

$homepage->display('response.tpl');
?>
