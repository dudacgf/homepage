<?php

Namespace Shiresco\Homepage\Pagina;

class Grupo extends ElementoAgrupado
{#
    var $descricaoGrupo;
    var $idTipoGrupo;
    var $descricaoTipoGrupo;
    var $grupoRestrito;
    var $restricaoGrupo;
    var $idCategoria;
    var $posCategoria;

    public function __construct($_idGrupo, $_idCategoria = NULL)
    {
        parent::__construct();

        if ($_idGrupo != NULL)
        {
            if ($_idCategoria != NULL)
            {
                $_sql = $this->hpDB->prepare("SELECT g.*, gc.posCategoria, tg.descricaoTipoGrupo
                           from hp_grupos g, hp_gruposxcategorias gc, hp_tiposgrupos tg
                          where g.idGrupo = gc.idGrupo
                            and g.idTipoGrupo = tg.idTipoGrupo
                            and gc.idGrupo = ?
                            and gc.idCategoria = ?");
                $_sql->bind_param("ii", $_idGrupo, $_idCategoria);
            }
            else
            {
                $_sql = $this->hpDB->prepare("SELECT g.*, tg.descricaoTipoGrupo
                           FROM hp_grupos g, hp_tiposgrupos tg
                          WHERE g.idTipoGrupo = tg.idTipoGrupo
                            AND g.idGrupo = ?");
                $_sql->bind_param("i", $_idGrupo);
            }
            $_sql->execute();
            $result = $_sql->get_result();
            $line = $result->fetch_assoc();

            if (!$line)
            {
                throw new \Exception("idGrupo incorreto: $_idGrupo");
            }

            $this->idGrupo = $line['idGrupo'];
            $this->descricaoGrupo = $line['descricaoGrupo'];
            $this->idTipoGrupo = $line['idTipoGrupo'];
            $this->descricaoTipoGrupo = $line['descricaoTipoGrupo'];
            $this->grupoRestrito = $line['grupoRestrito'];
            $this->restricaoGrupo = $line['restricaoGrupo'];
            if ($_idCategoria != NULL)
            {
                $this->idCategoria = $_idCategoria;
                $this->posCategoria = $line['posCategoria'];
            }
            //syslog(LOG_INFO, print_r($this, true));
        }

    }

    function inserir()
    {
        $_sql = $this->hpDB->prepare("INSERT INTO hp_grupos (descricaoGrupo, idTipoGrupo, grupoRestrito, restricaoGrupo) VALUES (?, ?, ?, ?)");
        $_sql->bind_param("siis", $this->descricaoGrupo, $this->idTipoGrupo, $this->grupoRestrito, $this->restricaoGrupo);

        // executa o query e resgata o id criado.
        $result = $_sql->execute();
        if (!$result)
        {
            throw new \Exception ("erro ao criar o grupo: " . $this->descricaoGrupo);
        }
        else
        {
            $this->idGrupo = $this->hpDB->getLastInsertId();
            return $this->idGrupo;
        }
    }

    function atualizar()
    {
        $_sql = $this->hpDB->prepare("UPDATE hp_grupos SET descricaoGrupo = ?,
                                     idTipoGrupo = ?,
                                     grupoRestrito = ?,
                                     restricaoGrupo = ?
                                     WHERE idGrupo = ?");
        $_sql->bind_param("siisi", $this->descricaoGrupo, $this->idTipoGrupo, $this->grupoRestrito, $this->restricaoGrupo, $this->idGrupo);

        // executa o query e retorna o número de linhas afetadas pelo update
        $result = $_sql->execute();
        if (!$result)
        {
            throw new \Exception ("erro ao atualizar o grupo: " . $this->idGrupo . ": " . $this->descricaoGrupo);
        }
        else
        {
            return $this->hpDB->getAffectedRows();
        }
    }

    function excluir()
    {
        $result = true;

        $this->lerElementos();

        $result = $result and $this->hpDB->begin_transaction();

        foreach ($this->elementos as $elemento) {
            $result = $result and $elemento->excluir();
        }

        # apaga os elementos deste grupo antes de apagar o grupo
        $_sql = $this->hpDB->prepare("delete from hp_grupos where idGrupo = ?");
        $_sql->bind_param("i", $this->idGrupo);
        $result = $result and $_sql->execute();

        # remove este grupo de todas as categorias em que ele aparece antes de apagar o grupo
        $_sql = $this->hpDB->prepare("delete from hp_gruposxcategorias where idGrupo = ?");
        $_sql->bind_param("i", $this->idGrupo);
        $result = $result and $_sql->execute();

        if ($result) {
            $result = $result and $this->hpDB->commit();
        }
        else
        {
            $result = $result and $this->hpDB->rollback();
        }

        return $result;
    }

    function getArray()
    {
        if (isset($this->idGrupo))
        {
            return array(
                'idCategoria' => $this->idCategoria,
                'posCategoria' => $this->posCategoria,
                'idGrupo' => $this->idGrupo,
                'descricaoGrupo' => $this->descricaoGrupo,
                'idTipoGrupo' => $this->idTipoGrupo,
                'descricaoTipoGrupo' => $this->descricaoTipoGrupo,
                'grupoRestrito' => $this->grupoRestrito,
                'restricaoGrupo' => $this->restricaoGrupo);
        }
        else
        {
            throw new \Exception('erro em grupo::getArray(). Não inicializado!');
        }
    }

    static function newFromArray($_array)
    {
        $newGrupo = new grupo(NULL);

        $newGrupo->idGrupo = $_array['idGrupo'];
        $newGrupo->descricaoGrupo = $_array['descricaoGrupo'];
        $newGrupo->idTipoGrupo = $_array['idTipoGrupo'];
        $newGrupo->descricaoTipoGrupo = $_array['descricaoTipoGrupo'];
        $newGrupo->grupoRestrito = $_array['grupoRestrito'];
        $newGrupo->restricaoGrupo = $_array['restricaoGrupo'];
        $newGrupo->idCategoria = $_array['idCategoria'];
        $newGrupo->posCategoria = $_array['posCategoria'];

        return $newGrupo;
    }

    function numeroElementos()
    {
        if (!isset($this->idGrupo))
        {
            throw new \Exception('não posso ler o numero de elementos de um grupo se não souber o grupo!');
        }

        $_sql = $this->hpDB->prepare("select COUNT(*) as numElementos from hp_elementos where idGrupo = ?");
        $_sql->bind_param("i", $this->idGrupo);
        if (!$_sql->execute())
        {
            throw new \Exception('numeroElementos: could not execute query');
        }

        $_count = $_sql->get_result();
        if (!$_count)
        {
            return 0;
        }
        return $_count->fetch_assoc()['numElementos'];
    }

    function lerElementos()
    {
        // lê tudo: links, forms e separadores e devolve como array de objetos
        $_sql = $this->hpDB->prepare("select * from hp_elementos where idGrupo = ? order by posGrupo");
        $_sql->bind_param("i", $this->idGrupo);
        if (!$_sql->execute())
        {
            return array();
        }

        $_elementos = $_sql->get_result();

        while ($_el = $_elementos->fetch_assoc())
        {
            switch ($_el['idTipoElemento'])
            {
                case Constantes::ELEMENTO_LINK:
                    $this->elementos[] = Link::newFromArray($_el);
                    break;

                case Constantes::ELEMENTO_FORM:
                    $this->elementos[] = Form::newFromArray($_el);
                    break;

                case Constantes::ELEMENTO_SEPARADOR:
                    $this->elementos[] = Separador::newFromArray($_el);
                    break;

                case Constantes::ELEMENTO_IMAGEM:
                    $this->elementos[] = Imagem::newFromArray($_el);
                    break;

                case Constantes::ELEMENTO_TEMPLATE:
                    $this->elementos[] = Template::newFromArray($_el);
                    break;

                default:
                    throw new \Exception ('tipo errado de elemento. socorro!');
            }
        }
    }

    function lerArrayElementos()
    {
        // lê tudo: links, forms e separadores e devolve como array de arrays
        $_sql = $this->hpDB->prepare("select * from hp_elementos where idGrupo = ? order by posGrupo");
        $_sql->bind_param("i", $this->idGrupo);
        if (!$_sql->execute())
        {
            return array();
        }

        $_elementos = $_sql->get_result();
        $elementos = [];

        while ($_el = $_elementos->fetch_assoc())
        {
            switch ($_el['idTipoElemento'])
            {
                case Constantes::ELEMENTO_LINK:
                    $elementos[] = Link::newFromArray($_el)->getArray();
                    break;

                case Constantes::ELEMENTO_FORM:
                    $elementos[] = Form::newFromArray($_el)->getArray();
                    break;

                case Constantes::ELEMENTO_SEPARADOR:
                    $elementos[] = Separador::newFromArray($_el)->getArray();
                    break;

                case Constantes::ELEMENTO_IMAGEM:
                    $elementos[] = Imagem::newFromArray($_el)->getArray();
                    break;

                case Constantes::ELEMENTO_TEMPLATE:
                    $elementos[] = Template::newFromArray($_el)->getArray();
                    break;

                default:
                    throw new \Exception ('tipo errado de elemento. socorro!');
            }
        }
        return $elementos;
    }

    public function elementoNaPosicao($_posElemento)
    {
        // lê tudo: links, forms e separadores
        $_sql = $this->hpDB->prepare("select * from hp_elementos where idGrupo = ? and posGrupo = :");
        $_sql->bind_param("ii", $this->idGrupo, $_posElemento);
        if (!$_sql->execute()) {
            throw new \Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo na posição $_posElemento");
        }

        $_elemento = $_sql->get_result();

        if (!$_elemento)
        {
            throw new \Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo na posição $_posElemento");
        }


        $_el = $_elemento->fetch_assoc();
        switch ($_el['idTipoElemento'])
        {
            case Constantes::ELEMENTO_LINK:
                $elemento = Link::newFromArray($_el);
            break;

            case Constantes::ELEMENTO_FORM:
                $elemento = Form::newFromArray($_el);
            break;

            case Constantes::ELEMENTO_SEPARADOR:
                  $elemento = Separador::newFromArray($_el);
            break;

            case Constantes::ELEMENTO_IMAGEM:
                $elemento = Imagem::newFromArray($_el);
            break;

            case Constantes::ELEMENTO_TEMPLATE:
                $elemento = Template::newFromArray($_el);
            break;

            default:
                throw new \Exception ('tipo errado de elemento. socorro!');
        }

        return $elemento;
    }

    function elementoDeCodigo($_idElemento)
    {
        // lê tudo: links, forms e separadores
        $_sql = $this->hpDB->prepare("select * from hp_elementos where idGrupo = ? and idElemento = ?");
        $_sql->bind_param("ii", $this->idGrupo, $_idElemento);
        if (!$_sql->execute()) {
            throw new \Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        $_elemento = $_sql->get_result();

        if (!$_elemento)
        {
            throw new \Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        $_el = $_elemento->fetch_assoc();
        switch ($_el['idTipoElemento'])
        {
            case Constantes::ELEMENTO_LINK:
                $elemento = Link::newFromArray($_el);
            break;

            case Constantes::ELEMENTO_FORM:
                $elemento = Form::newFromArray($_el);
            break;

            case Constantes::ELEMENTO_SEPARADOR:
                  $elemento = Separador::newFromArray($_el);
            break;

            case Constantes::ELEMENTO_IMAGEM:
                $elemento = Imagem::newFromArray($_el);
            break;

            case Constantes::ELEMENTO_TEMPLATE:
                $elemento = Template::newFromArray($_el);
            break;

            default:
                throw new \Exception ('tipo errado de elemento. socorro!');
        }

        return $elemento;
    }

    function lerNaoElementos() { }

    function deslocarElementoParaCima($_idElemento)
    {
        // calcula a posição do elemento anterior.
        $_sql = $this->hpDB->prepare("SELECT posGrupo FROM hp_elementos WHERE idGrupo = ? AND idElemento = ?");
        $_sql->bind_param("ii", $this->idGrupo, $_idElemento);
        if (!$_sql->execute()) {
            throw new \Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        try
        {
            $_posGrupo = $_sql->get_result()->fetch_assoc()['posGrupo'];
        } catch (Exception $e) {
            throw new \Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        $_ProxPosGrupo = $_posGrupo-1;
        if ($_ProxPosGrupo > 0) {
            // desloca o elemento anterior para baixo (se ele não existir, não tem problema).
            $_sql = $this->hpDB->prepare("UPDATE hp_elementos set posGrupo = posGrupo + 1 WHERE idGrupo = ? AND posGrupo = ?");
            $_sql->bind_param("ii", $this->idGrupo, $_ProxPosGrupo);
            $_sql->execute();

            // desloca para cima o elemento solicitado
            $_sql = $this->hpDB->prepare("UPDATE hp_elementos set posGrupo = ? WHERE idGrupo = ? AND idElemento = ?");
            $_sql->bind_param("iii", $_ProxPosGrupo, $this->idGrupo, $_idElemento);
            $_return = $_sql->execute();
        }
        return $_return;
    }

    function deslocarElementoParaBaixo($_idElemento)
    {

        // calcula a posição do elemento anterior.
        $_sql = $this->hpDB->prepare("SELECT posGrupo FROM hp_elementos WHERE idGrupo = ? AND idElemento = ?");
        $_sql->bind_param("ii", $this->idGrupo, $_idElemento);
        if (!$_sql->execute()) {
            throw new \Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        try
        {
            $_posGrupo = $_sql->get_result()->fetch_assoc()['posGrupo'];
        } catch (Exception $e) {
            throw new \Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        $_ProxPosGrupo = $_posGrupo+1;

        // obtém o total de elementos deste grupo.
        $_sql = $this->hpDB->prepare("SELECT Count(1) as numElementos from hp_elementos WHERE idGrupo = ?");
        $_sql->bind_param("i", $this->idGrupo);
        if (!$_sql->execute()) {
            throw new \Exception ("erro executando a query numElementos em deslocarElementoParaBaixo");
        }

        try
        {
            $_numElementos = $_sql->get_result()->fetch_assoc()['numElementos'];
        } catch (Exception $e) {
            throw new \Exception ("erro executando a query numElementos em deslocarElementoParaBaixo");
        }

        // desloca o elemento posterior para cima. (se ele não existir não tem problema)
        $_sql = $this->hpDB->prepare("UPDATE hp_elementos set posGrupo = posGrupo - 1 WHERE idGrupo = ? AND posGrupo = ?");
        $_sql->bind_param("ii", $this->idGrupo, $_ProxPosGrupo);
        $_return = $_sql->execute();

        // desloca para baixo o elemento solicitado.
        $_sql = $this->hpDB->prepare("UPDATE hp_elementos set posGrupo = ? WHERE idGrupo = ? AND idElemento = ?");
        $_sql->bind_param("iii", $_ProxPosGrupo, $this->idGrupo, $_idElemento);
        $_return = $_return and $_sql->execute();

        return $_return;
    }

    function incluirElemento($_idElemento, $_posElemento = 0) { }

    function excluirElemento($_idElemento) { }

    static function getGrupos()
    {
        // como esta função é um método de classe, não posso usar nenhuma variável de instância, apenas locais e globais.
        // desta forma, tenho que usar uma nova conexão para a base de dados, ainda que já haja uma aberta.
        global $global_hpDB;

        $_sql = "SELECT * FROM hp_grupos order by descricaoGrupo";
        $_grupos = $global_hpDB->simple_query($_sql);
        if (!$_grupos)
        {
            throw new \Exception('erro: não consegui ler nenhum grupo!');
        }
        else
        {
            foreach ($_grupos as $_grp)
            {
                $grupos[] = array(
                    'idGrupo' => $_grp['idGrupo'],
                    'descricaoGrupo' => $_grp['descricaoGrupo'],
                    'idTipoGrupo' => $_grp['idTipoGrupo'],
                    'grupoRestrito' => $_grp['grupoRestrito'],
                    'restricaoGrupo' => $_grp['restricaoGrupo']);
            }
        }
        return isset($grupos) ? $grupos : FALSE;
    }

    static function getCount()
    {
        global $global_hpDB;

        $_sql = "SELECT COUNT(*) as numElementos FROM hp_grupos";
        $_count = $global_hpDB->simple_query($_sql);
        if (!$_count)
        {
            return 0;
        }
        else
        {
            return $_count[0]['numElementos'];
        }
    }

}#  grupo */

?>
