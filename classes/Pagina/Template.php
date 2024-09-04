<?php

Namespace Shiresco\Homepage\Pagina;

class Template extends Elemento
{#
    var $idTemplate;
    var $descricaoTemplate;
    var $nomeTemplate;

    public function __construct($_idTemplate)  // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
    {

        parent::__construct();
        $this->tipoElemento = Constantes::ELEMENTO_TEMPLATE;

        if (isset($_idTemplate) && $_idTemplate != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idTemplate);

            if (!$_sql->execute())
                throw new \Exception("idTemplate incorreto: $_idTemplate");

            $line = $_sql->get_result()->fetch_assoc();

            $this->idTemplate = $line['idElemento'];
            $this->idGrupo = $line['idGrupo'];
            $this->posGrupo = $line['posGrupo'];
            $this->descricaoTemplate = $line['descricaoElemento'];
            $this->nomeTemplate = $line['templateFileName'];
            $this->idElemento = $this->idTemplate;
            $this->descricaoElemento = $this->descricaoTemplate;
        }
    }

    function inserir()
    {
        $_sql = $this->hpDB->prepare("INSERT INTO hp_elementos (idTipoElemento, idGrupo, posGrupo, templateFileName, descricaoElemento)
                VALUES (?, ?, ?, ?, ?)");
        $_sql->bind_param("iiiss", $this->tipoElemento, $this->idGrupo, $this->posGrupo , $this->nomeTemplate, $this->descricaoTemplate);

        // executa o query e resgata o id criado.
        if (!$_sql->execute())
            throw new \Exception ("erro ao criar o template: '$this->descricaoTemplate'!");

        $this->idTemplate = $this->hpDB->getLastInsertId();
        return $this->idTemplate;
    }

    function atualizar ()
    {
        $_sql = $this->hpDB->prepare("UPDATE hp_elementos
                                         SET posGrupo = ?,
                                             descricaoElemento = ?,
                                             templateFileName = ?
                                       WHERE idElemento = ?");
        $_sql->bind_param("issi", $this->posGrupo, $this->descricaoTemplate, $this->nomeTemplate, $this->idTemplate);

        // executa o query
        if (!$_sql->execute())
            throw new \Exception ("erro ao atualizar o template: '$this->descricaoTemplate'!");

        return $this->hpDB->getAffectedRows();
    }

    public function excluir()
    {
        if (!isset($this->idTemplate))
            return NULL;

        $_sql = $this->hpDB->prepare("DELETE FROM hp_elementos where idElemento = ?");
        $_sql->bind_param("i", $this->idTemplate);

        // executa o query
        if (!$_sql->execute())
            throw new \Exception ("erro ao excluir o template: '$this->descricaoTemplate'!");

        return $this->hpDB->getAffectedRows();
    }

    public function getArray()
    {
        if (isset($this->idTemplate))
        {
            return array(
                    'idElemento' => $this->idTemplate,
                    'descricaoElemento' => $this->descricaoTemplate,
                    'idGrupo' => $this->idGrupo,
                    'posGrupo' => $this->posGrupo,
                    'tipoElemento' => Constantes::ELEMENTO_TEMPLATE,
                    'descricaoTemplate' => $this->descricaoTemplate,
                    'nomeTemplate' => $this->nomeTemplate );
        }
        else
        {
            throw new \Exception('erro em wTemplate::getArray(). Não inicializado!');
        }
    }

    static function newFromArray($_array)
    {
        $newTemplate = new Template(NULL);

        $newTemplate->idTemplate = $_array['idElemento'];
        $newTemplate->idGrupo = $_array['idGrupo'];
        $newTemplate->posGrupo = $_array['posGrupo'];
        $newTemplate->descricaoTemplate = $_array['descricaoElemento'];
        $newTemplate->nomeTemplate = $_array['templateFileName'];
        $newTemplate->idElemento = $newTemplate->idTemplate;
        $newTemplate->descricaoElemento = $newTemplate->descricaoTemplate;

        return $newTemplate;
    }

    static function getCount()
    {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = ?");
        $_sql->bind_param("i", ...array(Constantes::ELEMENTO_TEMPLATE));

        if (!$_sql->execute())
            throw new \Exception("Erro ao contar templates");

        $_row = $_sql->get_result()->fetch_assoc();
        if (!$_row)
            return 0;

        return $_row['numElementos'];
    }


}#  wTemplate */

?>
