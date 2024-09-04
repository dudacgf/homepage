<?php

Namespace Shiresco\Homepage\Pagina;

class Form extends Elemento
{#
    var $idForm;
    var $nomeForm;
    var $acao;
    var $nomeCampo;
    var $tamanhoCampo;
    var $descricaoForm;

    public function __construct($_idForm) // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
    {

        parent::__construct();
        $this->tipoElemento = Constantes::ELEMENTO_FORM;

        if (isset($_idForm) && $_idForm != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idForm);
            if (!$_sql->execute()) {
                throw new \Exception("idForm incorreto: $_idForm");
            }

            $line = $_sql->get_result()->fetch_assoc();

            if (!$line)
            {
                throw new \Exception("idForm incorreto: $_idForm");
            }

            $this->idForm = $line['idElemento'];
            $this->idGrupo = $line['idGrupo'];
            $this->posGrupo = $line['posGrupo'];
            $this->nomeForm = $line['formNome'];
            $this->acao = $line['urlElemento'];
            $this->nomeCampo = $line['formNomeCampo'];
            $this->tamanhoCampo = $line['formTamanhoCampo'];
            $this->descricaoForm = $line['descricaoElemento'];
            $this->idElemento = $this->idForm;
            $this->descricaoElemento = $this->descricaoForm;
        }
    }

    function inserir()
    {
        $_sql = $this->hpDB->prepare("INSERT INTO hp_elementos (idTipoElemento, idGrupo, posGrupo, formNome,
                                                               urlElemento, formNomeCampo, formTamanhoCampo,
                                                               descricaoElemento)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $_sql->bind_param("iiisssis", $this->tipoElemento, $this->idGrupo, $this->posGrupo , $this->nomeForm,
                 $this->acao, $this->nomeCampo, $this->tamanhoCampo, $this->descricaoForm);

        // executa o query e resgata o id criado.
        if (!$_sql->execute())
        {
            throw new \Exception("erro ao criar o Form: '$this->descricaoForm'!");
        }
        $this->idForm = $this->hpDB->getLastInsertId();
        return $this->idForm;
    }

    function atualizar()
    {

        $_sql = $this->hpDB->prepare("UPDATE hp_elementos
                    SET posGrupo = ?,
                        urlElemento = ?,
                        descricaoElemento = ?,
                        formNome = ?,
                        formNomeCampo = ?,
                        formTamanhoCampo = ?
                  WHERE idElemento = ?");
        $_sql->bind_param("issssii", $this->posGrupo, $this->acao, $this->descricaoForm, $this->nomeForm, $this->nomeCampo, $this->tamanhoCampo, $this->idForm);

        // executa o query e retorna o número de linhas afetadas
        if (!$_sql->execute())
        {
            throw new \Exception("erro ao gravar o form: '$this->descricaoForm'!");
        }
        return $this->hpDB->getAffectedRows();
    }

    public function excluir()
    {

        if (isset($this->idForm)) {
            $_sql = $this->hpDB->prepare("DELETE FROM hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $this->idForm);
            if (!$_sql->execute())
            {
                throw new \Exception ("erro ao excluir o form: '$this->descricaoForm'!");
            }
            return $this->hpDB->getAffectedRows();
        }

    }

    public function getArray()
    {
        if (isset($this->idForm))
        {
            return array(
                    'idElemento' => $this->idForm,
                    'descricaoElemento' => $this->descricaoForm,
                    'idGrupo' => $this->idGrupo,
                    'posGrupo' => $this->posGrupo,
                    'tipoElemento' => Constantes::ELEMENTO_FORM,
                    'nomeForm' => $this->nomeForm,
                    'acao' => $this->acao,
                    'nomeCampo' => $this->nomeCampo,
                    'tamanhoCampo' => $this->tamanhoCampo,
                    'descricaoForm' => $this->descricaoForm);
        }
        else
        {
            throw new \Exception('erro em wForm::getArray(). Não inicializado!');
        }
    }

    static function newFromArray($_array)
    {
        $newForm = new Form(NULL);

        $newForm->idForm = $_array['idElemento'];
        $newForm->idGrupo = $_array['idGrupo'];
        $newForm->posGrupo = $_array['posGrupo'];
        $newForm->nomeForm = $_array['formNome'];
        $newForm->acao = $_array['urlElemento'];
        $newForm->nomeCampo = $_array['formNomeCampo'];
        $newForm->tamanhoCampo = $_array['formTamanhoCampo'];
        $newForm->descricaoForm = $_array['descricaoElemento'];
        $newForm->idElemento = $newForm->idForm;
        $newForm->descricaoElemento = $newForm->descricaoForm;

        return $newForm;
    }

    static function getCount()
    {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = ?");
        $_sql->bind_param("i", ...array(Constantes::ELEMENTO_FORM));
        if (!$_sql->execute()) {
            throw new \Exception("Não consegui ler o número de elementos do tipo form");
        }

        $_count = $_sql->get_result()->fetch_assoc();
        if (!$_count)
        {
            return 0;
        }
        else
        {
            return $_count['numElementos'];
        }
    }

}#  wForm */

?>
