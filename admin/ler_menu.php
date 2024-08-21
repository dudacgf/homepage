<?php
$admPag = new pagina(ID_ADM_PAG);

//
// Leio todos os elementos da página e percorro-os, quebrando por categoria e grupo e os vou incluindo no template
$menusLidos = $admPag->getBigArray();
$categAnterior = 'xx';
$grupoAnterior = 'xx';

foreach ($menusLidos as $menu) {

    // realiza a quebra por grupo. 
    // para cada grupo, tenho que guardar sua descricao para comparar novamente e seu tipo para o template.
    // para o primeiro grupo da primeira categoria, como obviamente ainda não tenho elementos, faço uma quebra falsa.
    if ( $menu['descricaoGrupo'] != $grupoAnterior ) {
        if ( isset ( $menus ) ) {
            $grupos[] = array('grupo' => $grupoAnterior, 'idtipoGrupo' => $idTipoGrupoAnterior, 'elementos' => $menus);
            unset ( $menus );
        }
        $grupoAnterior = $menu['descricaoGrupo'];
        $idTipoGrupoAnterior = $menu['idTipoGrupo'];
    }

    // realiza a quebra por categoria.
    // para cada categoria, tenho que guardar sua descricao para comparar novamente e sua posicao na pagina.
    // para a primeira categoria, como obviamente não tenho grupos, faço uma quebra falsa.
    if ( $menu['descricaoCategoria'] != $categAnterior ) {
        if ( isset ( $grupos ) ) {
            $menuGrupos[] = array('index' => $posicaoAnterior, 'idGrupo' => $menu['idGrupo'], 'grupos' => $grupos);
            unset($grupos);
        }
        $categAnterior = $menu['descricaoCategoria'];
        $posicaoAnterior = $menu['posPagina'];
        $menuCategorias[] = array('index' => $menu['posPagina'], 'categoria' => $menu['descricaoCategoria']);
    }

    // incluo os elementos de um grupo num array que na quebra de grupos será adicionado a um array de grupos.
    // este array de grupos será incluido num array co-irmão do array de categorias a cada quebra de categoria.
    $menus[] = array( 
                   'idElemento' => $menu['idElemento'],
                   'descricaoLink' => $menu['descricaoElemento'],
                   'tipoElemento' => $menu['idTipoElemento'],
                   'posGrupo' => $menu['posGrupo'], 
                   'linkURL' => $menu['urlElemento'],
                   'localLink' => $menu['urlElementoLocal'], 
                   'dicaLink' => $menu['dicaElemento'], 
                   'targetLink' => $menu['urlElementoTarget'], 
                   'nomeForm' => $menu['formNome'], 
                   'descricaoForm' => $menu['descricaoElemento'],
                   'acao' => $menu['urlElemento'],
                   'nomeCampo' => $menu['formNomeCampo'],
                   'tamanhoCampo' => $menu['formTamanhoCampo'], 
                   'breakBefore' => $menu['separadorBreakBefore'], 
                   'descricaoSeparador' => $menu['descricaoElemento'],
                   'urlImagem' => $menu['urlElemento'],
                   'descricaoImagem' => $menu['descricaoElemento'],
                   'nomeTemplate' => $menu['templateFileName'],
                   'urlElementoSSL' => $menu['urlElementoSSL'], 
                   'urlElementoSVN' => $menu['urlElementoSVN']
            );
}

// neste tipo de loop com quebra no início, fica sempre faltando adicionar os grupos da última categoria.
$grupos[] = array('grupo' => $grupoAnterior, 'idtipoGrupo' => $idTipoGrupoAnterior, 'elementos' => $menus);
$menuGrupos[] = array('index' => $posicaoAnterior, 'idGrupo' => $menu['idGrupo'], 'grupos' => $grupos);
$homepage->assign('menuCategorias', $menuCategorias);
$homepage->assign('menuGrupos', $menuGrupos);
?>
