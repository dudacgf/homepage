<?php
use Shiresco\Homepage\Pagina as Pagina;

require_once('../common.php');
$admPag = new Pagina\Pagina(ID_ADM_PAG);

//
// Leio todos os elementos da página e percorro-os, quebrando por categoria e grupo e os vou incluindo no template
$admPag->lerElementos();
foreach ($admPag->elementos as $categ) {

    $menuCategorias[] = array('index' => $categ->posPagina, 'categoria' => $categ->descricaoCategoria);
    
    // Leio os grupos desta categoria e percorro-os, incluíndo-os no template
    $categ->lerElementos();
    $tempGrupos = [];
    foreach ($categ->elementos as $umGrupo) 
    {

        // Leio os elementos deste grupo e percorro-os, incluíndo-os no template
        $umGrupo->lerElementos();
        $menutItems = [];
        foreach($umGrupo->elementos as $menuItem) 
        {
            $menuItems[] = $menuItem->getArray();
        }       

        $tempGrupos[] = array(
                        'grupo' => $umGrupo->descricaoGrupo,
                        'idtipoGrupo' => $umGrupo->idTipoGrupo,
                        'menuItems' => $menuItems);

    }
    
    $menuGrupos[] = array(
                            'index' => $umGrupo->posCategoria, 
                            'idGrupo' => $umGrupo->idGrupo,
                            'grupos' => $tempGrupos
                            );

}

// neste tipo de loop com quebra no início, fica sempre faltando adicionar os grupos da última categoria.
$homepage->assign('menuCategorias', $menuCategorias);
$homepage->assign('menuGrupos', $menuGrupos);
?>
