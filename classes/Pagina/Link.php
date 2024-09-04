<?php

Namespace Shiresco\Homepage\Pagina;

class Link extends Elemento
{
    var $idLink;
    var $linkURL;
    var $descricaoLink;
    var $dicaLink;
    var $localLink;
    var $urlElementoSSL;
    var $targetLink;
    var $urlElementoSVN;
    var $tipoElemento;

    public function __construct($_idLink) // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê{#
    {

        parent::__construct();
        $this->tipoElemento = Constantes::ELEMENTO_LINK;

        // se não passou um id para este link, é porque ele está sendo criado.
        // se passou, ele já existe e vai ser lido.
        if (isset($_idLink) && $_idLink != NULL) {

            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idLink);
            if (!$_sql->execute()) {
                throw new \Exception("Código do link provavelmente incorreto: $_idLink");
            }

            try {
                $line = $_sql->get_result()->fetch_assoc();
            } catch(Exception $e) {
                throw new \Exception("Código do link provavelmente incorreto: $_idLink");
            }

            $this->idLink = $line['idElemento'];
            $this->idGrupo = $line['idGrupo'];
            $this->posGrupo = $line['posGrupo'];
            $this->linkURL = $line['urlElemento'];
            $this->descricaoLink = $line['descricaoElemento'];
            $this->dicaLink = $line['dicaElemento'];
            $this->localLink = $line['urlElementoLocal'];
            $this->targetLink = $line['urlElementoTarget'];
            $this->urlElementoSSL = $line['urlElementoSSL'];
            $this->urlElementoSVN = $line['urlElementoSVN'];
            $this->idElemento = $this->idLink;
            $this->descricaoElemento = $this->descricaoLink;
        }

    }

    function inserir ()
    {

        $_sql = $this->hpDB->prepare("INSERT INTO hp_elementos (idTipoElemento, idGrupo, posGrupo,
                                      urlELemento, descricaoElemento, dicaElemento,
                                      urlElementoLocal, urlElementoSSL, urlElementoSVN, urlElementoTarget)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $_sql->bind_param("iiisssiiis", $this->tipoElemento, $this->idGrupo, $this->posGrupo,
                          $this->linkURL, $this->descricaoLink, $this->dicaLink, $this->localLink, $this->urlElementoSSL,
                          $this->urlElementoSVN, $this->targetLink);
        // executa o query e resgata o id criado.
        if (!$_sql->execute())
        {
            throw new \Exception ("erro ao gravar o novo link: $this->descricaoLink");
        }
        $this->idLink = $this->hpDB->getLastInsertId();
        return $this->idLink;
    }

    function atualizar ()
    {

        $_sql = $this->hpDB->prepare("UPDATE hp_elementos
                                      SET posGrupo = ?, urlElemento = ?, descricaoElemento = ?, dicaElemento = ?, urlElementoLocal = ?,
                                          urlElementoTarget = ?, urlElementoSSL = ?, urlElementoSVN = ?
                                      WHERE idElemento = ?");
        $_sql->bind_param("isssisiii", $this->posGrupo, $this->linkURL, $this->descricaoLink,
                          $this->dicaLink, $this->localLink, $this->targetLink,
                          $this->urlElementoSSL, $this->urlElementoSVN, $this->idLink);

        // executa o query
        if (!$_sql->execute())
        {
            throw new \Exception ("erro ao gravar o link: " . $this->idLink . ": " . $this->descricaoLink);
        }
        else
        {
            return $this->hpDB->getAffectedRows();
        }

    }

    public function excluir()
    {

        if (isset($this->idLink)) {
            $_sql = $this->hpDB->prepare("DELETE FROM hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $this->idLink);
            if (!$_sql->execute())
            {
                throw new \Exception ("erro ao excluir o link: " . $this->idLink . ": " . $this->descricaoLink);
            }
            else
            {
                return $this->hpDB->getAffectedRows();
            }
        }

    }

    public function getArray()
    {
        if (isset($this->idLink))
        {
            return array(
                    'idElemento' => $this->idLink,
                    'descricaoElemento' => $this->descricaoLink,
                    'idGrupo' => $this->idGrupo,
                    'posGrupo' => $this->posGrupo,
                    'tipoElemento' => Constantes::ELEMENTO_LINK,
                    'linkURL' => htmlentities($this->linkURL),
                    'descricaoLink' => $this->descricaoLink,
                    'dicaLink' => $this->dicaLink,
                    'localLink' => $this->localLink,
                    'targetLink' => $this->targetLink,
                    'urlElementoSSL' => $this->urlElementoSSL,
                    'urlElementoSVN' => $this->urlElementoSVN);
        }
        else
        {
            throw new \Exception('erro em Link::getArray(). Não inicializado!' .  debug_print_backtrace());
        }
    }

    static function newFromArray($_array)
    {

        $newLink = new Link(NULL);

        $newLink->idLink = $_array['idElemento'];
        $newLink->idGrupo = $_array['idGrupo'];
        $newLink->posGrupo = $_array['posGrupo'];
        $newLink->linkURL = $_array['urlElemento'];
        $newLink->descricaoLink = $_array['descricaoElemento'];
        $newLink->dicaLink = $_array['dicaElemento'];
        $newLink->localLink = $_array['urlElementoLocal'];
        $newLink->targetLink = $_array['urlElementoTarget'];
        $newLink->urlElementoSSL = $_array['urlElementoSSL'];
        $newLink->urlElementoSVN = $_array['urlElementoSVN'];
        $newLink->idElemento = $newLink->idLink;
        $newLink->descricaoElemento = $newLink->descricaoLink;

        return $newLink;
    }/*}#*/

    static function getCount()
    {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = ?");
        $_sql->bind_param("i", ...array(Constantes::ELEMENTO_LINK));

        if (!$_sql->execute())
            throw new \Exception("Erro ao contar número de links");

        $_row = $_sql->get_result()->fetch_assoc();
        if (!$_row)
            return 0;

        return $_row['numElementos'];
    }

}

?>
