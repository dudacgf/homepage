<?php

Namespace Shiresco\Homepage\Pagina;

class TiposElementos
{#
    static function getArray()
    {
        global $global_hpDB;

        $_sql = "select * from hp_tiposelementos";
        $tipos = $global_hpDB->query($_sql);
        if (!$tipos)
        {
            throw new \Exception('não consegui ler a tabela de tipos de elementos!');
        }
        else
        {
            foreach ($tipos as $tipoElemento)
            {
                $tiposelementos[$tipoElemento['idTipoElemento']] = $tipoElemento['descricaoTipoElemento'];
            }
        }

        return isset($tiposelementos) ? $tiposelementos : FALSE;

    }

}#

?>
