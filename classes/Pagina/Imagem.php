<?php

Namespace Shiresco\Homepage\Pagina;

class Imagem extends Elemento
{#
    var $idImagem;
    var $descricaoImagem;
    var $ImagemURL;
    var $tipoElemento;

    public function __construct($_idImagem)  // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
    {

        parent::__construct();
        $this->tipoElemento = Constantes::ELEMENTO_IMAGEM;

        if (isset($_idImagem) && $_idImagem != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idImagem);

            if (!$_sql->execute())
                throw new \Exception("idImagem incorreto: $_idImagem");

            $line = $_sql->get_result()->fetch_assoc();

            $this->idImagem = $line['idElemento'];
            $this->idGrupo = $line['idGrupo'];
            $this->posGrupo = $line['posGrupo'];
            $this->urlImagem = $line['urlElemento'];
            $this->localLink = $line['urlElementoLocal'];
            $this->descricaoImagem = $line['descricaoElemento'];
            $this->idElemento = $this->idImagem;
            $this->descricaoElemento = $this->descricaoImagem;
        }
    }

    function inserir()
    {
        $_sql = $this->hpDB->prepare("INSERT INTO hp_elementos
                    (idTipoElemento, idGrupo, posGrupo, descricaoElemento, urlElemento, urlElementoLocal)
                 VALUES (?, ?, ?, ?, ?, ?)");
        $_sql->bind_param("iiissi", $this->tipoElemento, $this->idGrupo, $this->posGrupo , $this->descricaoImagem, $this->urlImagem, $this->localLink);

        // executa o query e resgata o id criado.
        if (!$_sql->execute())
            throw new \Exception ("erro ao criar a imagem: [$this->descricaoImagem]!");

        $this->idImagem = $this->hpDB->getLastInsertId();
        return $this->idImagem;
    }

    function atualizar ()
    {
        $_sql = $this->hpDB->prepare("UPDATE hp_elementos
                    SET posGrupo = ?,
                        descricaoElemento = ?,
                        urlElemento = ?,
                        urlElementoLocal = ?
                  WHERE idElemento = ?");
        $_sql->bind_param("issii", $this->posGrupo, $this->descricaoImagem, $this->urlImagem, $this->localLink, $this->idImagem);

        // executa o query e retorna o número de linhas afetadas (uma, se tudo der certo)
        if (!$_sql->execute())
            throw new \Exception ("erro ao gravar a imagem: '$this->descricaoImagem'!");

        return $this->hpDB->getAffectedRows();
    }

    public function excluir()
    {
        if (!isset($this->idImagem))
            return NULL;

        $_sql = $this->hpDB->prepare("DELETE FROM hp_elementos where idElemento = ?");
        $_sql->bind_param("i", $this->idImagem);
        if (!$_sql->execute())
            throw new \Exception ("erro ao excluir a imagem: '$this->descricaoImagem'!");

        return $this->hpDB->getAffectedRows();
    }

    public function getArray()
    {
        if (isset($this->idImagem))
        {
            return array(
                    'idElemento' => $this->idImagem,
                    'descricaoElemento' => $this->descricaoImagem,
                    'idGrupo' => $this->idGrupo,
                    'posGrupo' => $this->posGrupo,
                    'tipoElemento' => Constantes::ELEMENTO_IMAGEM,
                    'urlImagem' => $this->urlImagem,
                    'localLink' => $this->localLink,
                    'descricaoImagem' => $this->descricaoImagem);
        }
        else
        {
            throw new \Exception('erro em wImagem::getArray(). Não inicializado!');
        }
    }

    static function newFromArray($_array)
    {
        $newImagem = new Imagem(NULL);

        $newImagem->idImagem = $_array['idElemento'];
        $newImagem->idGrupo = $_array['idGrupo'];
        $newImagem->posGrupo = $_array['posGrupo'];
        $newImagem->urlImagem = $_array['urlElemento'];
        $newImagem->localLink = $_array['urlElementoLocal'];
        $newImagem->descricaoImagem = $_array['descricaoElemento'];
        $newImagem->idElemento = $newImagem->idImagem;
        $newImagem->descricaoElemento = $newImagem->descricaoImagem;

        return $newImagem;
    }

    static function getCount()
    {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = ?");
        $_sql->bind_param("i", ...array(Constantes::ELEMENTO_IMAGEM));

        if (!$_sql->execute())
            throw new \Exception("Erro ao contar número de imagens");

        $_count = $_sql->get_result()->fetch_assoc();
        if (!$_count)
            return 0;

        return $_count['numElementos'];
    }


}#  wImagem */

?>
