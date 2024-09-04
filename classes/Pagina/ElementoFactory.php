<?php

Namespace Shiresco\Homepage\Pagina;

class ElementoFactory {
    private $oElemento;
    private $idTipoElemento;

    public function __construct($_idElm) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare('select idTipoElemento from hp_elementos where idElemento = ?');
        $_sql->bind_param("i", $_idElm);
        if (!$_sql->execute())
            die("{'status': 'error', 'message': 'Não pude ler o elemento solicitado.'}");
        $results = $_sql->get_result();
        if (mysqli_num_rows($results) == 0)
            return NULL;

        $this->idTipoElemento = $results->fetch_assoc()['idTipoElemento'];
        switch ($this->idTipoElemento) {
            case Constantes::ELEMENTO_LINK:
                $this->oElemento = new Link($_idElm);
                break;
            case Constantes::ELEMENTO_FORM:
                $this->oElemento = new Form($_idElm);
                break;
            case Constantes::ELEMENTO_SEPARADOR:
                $this->oElemento = new Separador($_idElm);
                break;
            case Constantes::ELEMENTO_IMAGEM:
                $this->oElemento = new Imagem($_idElm);
                break;
            case Constantes::ELEMENTO_TEMPLATE:
                $this->oElemento = new Template($_idElm);
                break;
            default:
                return null;
        }
        return $this->oElemento;
    }

    public function excluir() {
        return $this->oElemento->excluir();
    }

    public function descricaoElemento() {
        switch ($this->idTipoElemento) {
            case Constantes::ELEMENTO_LINK:
                return $this->oElemento->descricaoLink;
                break;
            case Constantes::ELEMENTO_FORM:
                return $this->oElemento->descricaoForm;
                break;
            case Constantes::ELEMENTO_SEPARADOR:
                return $this->oElemento->descricaoSeparador;
                break;
            case Constantes::ELEMENTO_IMAGEM:
                return $this->oElemento->descricaoImagem;
                break;
            case Constantes::ELEMENTO_TEMPLATE:
                $this->oElemento->descricaoTemplate;
                break;
            default:
                return null;
        }
    }

    public function atualizar() {
        switch ($this->idTipoElemento) {
            case Constantes::ELEMENTO_LINK:
                return $this->oElemento->atualizar();
                break;
            case Constantes::ELEMENTO_FORM:
                return $this->oElemento->atualizar();
                break;
            case Constantes::ELEMENTO_SEPARADOR:
                return $this->oElemento->atualizar();
                break;
            case Constantes::ELEMENTO_IMAGEM:
                return $this->oElemento->atualizar();
                break;
            case Constantes::ELEMENTO_TEMPLATE:
                $this->oElemento->atualizar();
                break;
            default:
                return null;
        }
    }

    public function setPosGrupo($_posGrupo) {
        $this->oElemento->posGrupo = $_posGrupo;
    }
}

?>
