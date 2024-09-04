<?php

Namespace Shiresco\Homepage\Pagina;

abstract class ElementoAgrupado extends Elemento
{#
    public $elementos = array();

    function __construct()
    {
        parent::__construct();
        $this->comportamentoElemento = Constantes::ELEMENTO_AGRUPADO;
    }

    abstract function numeroElementos();

    abstract function lerElementos();

    abstract function elementoNaPosicao($_posElemento);

    abstract function elementoDeCodigo($_idElemento);

    abstract function lerNaoElementos();

    abstract function incluirElemento($_idElemento, $_posElemento = 0);

    abstract function excluirElemento($_idElemento);

    abstract function deslocarElementoParaCima($_idElemento);

    abstract function deslocarElementoParaBaixo($_idElemento);

}#  elementoAgrupado */

?>
