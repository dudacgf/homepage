<?php

Namespace Shiresco\Homepage\Temas;

class PaletasdeCor
{
    var $r;
    var $g;
    var $b;
    var $ok = false;

    public function __construct($nome = '') 
    {

        if ($nome[0] === '#') { // é uma cor no formato #RRGGBB - trunca no tamanho correto.
            $cor = substr($nome, 0, 7);
        }
        else
        {
            global $global_hpDB;

            $_sql = "select * from hp_paletasdecor where nome = '". strtolower($nome) ."'";
            $parCor = $global_hpDB->query($_sql);
            if (!$parCor)
            {
                die('não consegui ler a tabela de pares de cores!');
            }
            $cor = $parCor[0]['cor'];
        }

        $this->r = hexdec(substr($cor, 1, 2));
        $this->g = hexdec(substr($cor, 3, 2));
        $this->b = hexdec(substr($cor, 5, 2));
        $this->ok = true;

    }

    static function obtemIdCor($cor = '')
    {
        if ($cor == '' || $cor[0] !== '#')
        {
            return false;
        }
        else
        {
            global $global_hpDB;

            $_sql = "select id from hp_paletasdecor where cor = '$cor'";
            $result = $global_hpDB->query($_sql);
            if (!$result) 
            {
                return false;
            }
            else
            {
                return $result[0]['id'];
            }
        }
    }

    static function hspCor ($cor) {
        $r = hexdec(substr($cor, 1, 2));
        $g = hexdec(substr($cor, 3, 2));
        $b = hexdec(substr($cor, 5, 2));
        $hsp = sqrt(0.299 * ($r * $r) + 0.587 * ($g * $g) + 0.114 * ($b * $b));
        if ($hsp > 127.5) 
            return '#000000';
        else
            return '#ffffff';
    }

    static function getArray($_paleta = 'Pantone')
    {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("select * from hp_paletasdecor WHERE paleta = ?");
        $_sql->bind_param('s', $_paleta);

        try {
            if (!$_sql->execute())
                throw new Exception('não consegui ler a tabela de pares de cores!');
            else
            {
                $pares = $_sql->get_result();
                while ($parCor = $pares->fetch_assoc())
                {
                    $paresCores[] = array (
                            'id' => $parCor['id'],
                            'nome' => $parCor['nome'],
                            'cor' => $parCor['cor'],
                            'hspCor' => PaletasdeCor::hspCor($parCor['cor']),
                    );
                }
            }
        } catch (Exception $e) {
            throw new Exception ("erro na consulta à tabela de pares de cores: $_sql->error");
        }


        return isset($paresCores) ? $paresCores : FALSE;

    }

}

?>
