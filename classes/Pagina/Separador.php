<?php

Namespace Shiresco\Homepage\Pagina;

class Separador extends Elemento
{#
    var $idSeparador;
    var $descricaoSeparador;
    var $breakBefore;

    public function __construct($_idSeparador) // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
    {
        parent::__construct();
        $this->tipoElemento = Constantes::ELEMENTO_SEPARADOR;

        if (isset($_idSeparador) && $_idSeparador != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idSeparador);

            if (!$_sql->execute()) {
                throw new \Exception("idSeparador incorreto: $_idSeparador");
            }

            $line = $_sql->get_result()->fetch_assoc();
            if (!$line)
            {
                throw new \Exception("idSeparador incorreto: $_idSeparador");
            }

            $this->idSeparador = $line['idElemento'];
            $this->idGrupo = $line['idGrupo'];
            $this->posGrupo = $line['posGrupo'];
            $this->descricaoSeparador = $line['descricaoElemento'];
            $this->breakBefore = $line['separadorBreakBefore'];
            $this->idElemento = $this->idSeparador;
            $this->descricaoElemento = $this->descricaoSeparador;
        }
    }

    function inserir()
    {
        $_sql = $this->hpDB->prepare("INSERT INTO hp_elementos
                    (idTipoElemento, idGrupo, posGrupo, descricaoElemento, separadorBreakBefore)
                 VALUES (?, ?, ?, ?, ?)");
        $_sql->bind_param("iiisi", $this->tipoElemento, $this->idGrupo, $this->posGrupo , $this->descricaoSeparador, $this->breakBefore);

        // executa o query e resgata o id criado.
        if (!$_sql->execute())
        {
            throw new \Exception("erro ao criar o separador: [$this->descricaoSeparador]!");
        }
        $this->idSeparador = $this->hpDB->getLastInsertId();
        return $this->idSeparador;
    }

    function atualizar()
    {
        $_sql = $this->hpDB->prepare("UPDATE hp_elementos
                    SET posGrupo = ?,
                        descricaoElemento = ?,
                        separadorBreakBefore = ?
                  WHERE idElemento = ?");
        $_sql->bind_param("isii", $this->posGrupo, $this->descricaoSeparador, $this->breakBefore, $this->idSeparador);

        // executa o query
        if (!$_sql->execute())
        {
            throw new \Exception("erro ao gravar o separador: '$this->descricaoSeparador'!");
        }
        return $this->hpDB->getAffectedRows();
    }

    public function excluir()
    {
        if (isset($this->idSeparador)) {
            $_sql = $this->hpDB->prepare("DELETE FROM hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $this->idSeparador);

            if (!$_sql->execute())
            {
                throw new \Exception("erro ao excluir o separador: $this->descricaoSeparador");
            }
            return $this->hpDB->getAffectedRows();
        }

    }

    public function getArray()
    {
        if (isset($this->idSeparador))
        {
            return array(
                    'idElemento' => $this->idSeparador,
                    'descricaoElemento' => $this->descricaoSeparador,
                    'idGrupo' => $this->idGrupo,
                    'posGrupo' => $this->posGrupo,
                    'tipoElemento' => Constantes::ELEMENTO_SEPARADOR,
                    'descricaoSeparador' => $this->descricaoSeparador,
                    'breakBefore' => $this->breakBefore    );
        }
        else
        {
            throw new \Exception('erro em wSeparador::getArray(). Não inicializado!'. debug_print_backtrace());
        }
    }

    static function newFromArray($_array)
    {
        $newSeparador = new Separador(NULL);

        $newSeparador->idSeparador = $_array['idElemento'];
        $newSeparador->idGrupo = $_array['idGrupo'];
        $newSeparador->posGrupo = $_array['posGrupo'];
        $newSeparador->descricaoSeparador = $_array['descricaoElemento'];
        $newSeparador->breakBefore = $_array['separadorBreakBefore'];
        $newSeparador->idElemento = $newSeparador->idSeparador;
        $newSeparador->descricaoElemento = $newSeparador->descricaoSeparador;

        return $newSeparador;
    }

    static function getCount()
    {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = ?");
        $_sql->bind_param("i", ...array(Constantes::ELEMENTO_SEPARADOR));

        if (!$_sql->execute())
            throw new \Exception("Não consegui ler o número de separadores.");

        $_count = $_sql->get_result()->fetch_assoc();
        if (!$_count)
            return 0;
        return $_count['numElementos'];
    }


}#  wSeparador */

?>
