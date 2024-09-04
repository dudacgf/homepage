<?php

Namespace Shiresco\Homepage\Pagina;

class TiposGrupos
{#
    static function getArray()
    {
        global $global_hpDB;

        $_sql = "select * from hp_tiposgrupos";
        $tipos = $global_hpDB->query($_sql);
        if (!$tipos)
        {
            throw new \Exception('não consegui ler a tabela de tipos de grupos!');
        }
        else
        {
            foreach ($tipos as $tipoGrupo)
            {
                $tiposgrupos[$tipoGrupo['idTipoGrupo']] = $tipoGrupo['descricaoTipoGrupo'];
            }
        }

        return isset($tiposgrupos) ? $tiposgrupos : FALSE;

    }

}#  tiposGrupos*/

?>
