<?php

Namespace Shiresco\Homepage\Pagina;

abstract class Elemento
{#
    var $hpDB;
    var $idGrupo;
    var $posGrupo;
    var $comportamentoElemento;
    var $idElemento;
    var $descricaoElemento;
    var $tipoElemento;

    // finalmente resolvido o problema da conexão única com a utilização da variáveil $global_hpDB;
    function __construct()
    {
        global $global_hpDB;
        $this->hpDB = $global_hpDB;
        $this->comportamentoElemento = Constantes::ELEMENTO_SIMPLES;
    }

    abstract function inserir();

    abstract function atualizar();

    abstract function excluir();

    abstract function getArray();

    #abstract static function newFromArray($_array);

    #abstract static function getCount();

}#  elemento*/

?>
