<?php

Namespace Shiresco\Homepage\Pagina;

class Categoria extends ElementoAgrupado
{#
    var $idCategoria;
    var $descricaoCategoria;
    var $idPagina;
    var $posPagina;
    var $categoriaRestrita;
    var $restricaoCategoria;

    public function __construct($_idCategoria = NULL, $_idPagina = NULL)
    {

        parent::__construct();

        // se não passou $_idCategoria, deve provavelmente estar criando uma categoria.
        if ($_idCategoria == NULL)
            return true;

        if ($_idPagina != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT c.*, cp.posPagina
                   from hp_categorias c, hp_categoriasxpaginas cp
                  where c.idCategoria = cp.idCategoria
                    and cp.idCategoria = ?
                    and cp.idPagina = ?");
            $_sql->bind_param("ii", $_idCategoria, $_idPagina);
        }
        else
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_categorias where idCategoria = ?");
            $_sql->bind_param("i", $_idCategoria);
        }

        if (!$_sql->execute())
            throw new \Exception("Erro ao obter categoria: " . $_idCategoria);

        $line = $_sql->get_result()->fetch_assoc();
        if (!$line)
            throw new \Exception("idCategoria incorreto: $_idCategoria");

        $this->idCategoria = $_idCategoria;
        $this->descricaoCategoria = $line['descricaoCategoria'];
        $this->categoriaRestrita = $line['categoriaRestrita'];
        $this->restricaoCategoria = $line['restricaoCategoria'];
        if ($_idPagina != NULL)
        {
            $this->idPagina = $_idPagina;
            $this->posPagina = $line['posPagina'];
        }
    }

    function inserir()
    {
        $_sql = $this->hpDB->prepare("INSERT INTO hp_categorias (descricaoCategoria, categoriaRestrita, restricaoCategoria) VALUE (?, ?, ?)");
        $_sql->bind_param("sis", $this->descricaoCategoria, $this->categoriaRestrita, $this->restricaoCategoria);

        // executa o query e resgata o id criado.
        if (!$_sql->execute())
            throw new \Exception ("erro ao criar a categoria: " . $this->descricaoCategoria);

        $this->idCategoria = $this->hpDB->getLastInsertId();
        return $this->idCategoria;
    }

    function atualizar()
    {
        $_sql = $this->hpDB->prepare("UPDATE hp_categorias SET descricaoCategoria = ?, categoriaRestrita = ?, restricaoCategoria = ?
                                      WHERE idCategoria = ?");
        $_sql->bind_param("sisi", $this->descricaoCategoria, $this->categoriaRestrita, $this->restricaoCategoria, $this->idCategoria);

        // executa o query
        if (!$_sql->execute())
            throw new \Exception ("erro ao atualizar a categoria: " . $this->idCategoria . ": " . $this->descricaoCategoria);

        return $this->hpDB->getAffectedRows();
    }

    function excluir()
    {
        $result = true;

        $this->lerElementos();

        $result = $result and $this->hpDB->begin_transaction();

        foreach ($this->elementos as $elemento) {
            $result = $result and $this->excluirElemento($elemento->idGrupo);
        }

        $_sql = $this->hpDB->prepare("delete from hp_categorias where idCategoria = ?");
        $_sql->bind_param("i", $this->idCategoria);

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
        if (isset($this->idCategoria))
        {
            return array(
                'idCategoria' => $this->idCategoria,
                'descricaoCategoria' => $this->descricaoCategoria,
                'posPagina' => $this->posPagina,
                'categoriaRestrita' => $this->categoriaRestrita,
                'restricaoCategoria' => $this->restricaoCategoria);
        }
        else
        {
            throw new \Exception('erro em categoria::getArray(). Não inicializado!');
        }
    }

    static function newFromArray($_array)
    {
        $newCategoria = new Categoria(NULL);

        $newCategoria->idCategoria = $_array['idCategoria'];
        $newCategoria->descricaoCategoria = $_array['descricaoCategoria'];
        $newCategoria->categoriaRestrita = $_array['categoriaRestrita'];
        $newCategoria->restricaoCategoria = $_array['restricaoCategoria'];
        $newCategoria->idPagina = $_array['idPagina'];
        $newCategoria->posPagina = $_array['posPagina'];

        return $newCategoria;
    }

    function numeroElementos() { }

    function lerElementos()
    {

        // verifica se foi pedido algum grupo restrito
        // na stored proc chamada, eu faço um REGEXP com os grupos passados. aqui eu monto a expressão regular...
        if (isset($_REQUEST['gr']))
        {
            if (strpos($_REQUEST['gr'], 'all') !== false)
            {
                $_grParm = ".*";
            }
            elseif ($_REQUEST['gr'] != '') // pediu alguns dos grupos restritos corretamente?
            {
                // sim, aceitando qualquer separador...
                $_grParm = str_replace(array(",", ".", ";", "+", "-"), "|", $_REQUEST['gr']);
                while (strpos($_grParm, "||") !== FALSE) $_grParm = str_replace("||", "|", $_grParm);
            }
            else // não, coloca o default
            {
                $_grParm = "_";
            }
        }
        else // não pediu nenhum grupo restrito
        {
            $_grParm = "_";
        }

        $_sql = $this->hpDB->prepare("SELECT g.*, gc.*, tg.*
       from hp_grupos g, hp_gruposxcategorias gc, hp_tiposgrupos tg
      where gc.idGrupo = g.idGrupo
        and gc.idCategoria = ?
        and g.idTipoGrupo = tg.idTipoGrupo
        and ( (grupoRestrito = 0)
           or (grupoRestrito = 1 and restricaoGrupo REGEXP ? ) )
     order by gc.posCategoria;");
        $_sql->bind_param("is", $this->idCategoria, $_grParm);

        if (!$_sql->execute())
            throw new \Exception("não consegui ler os elementos da categoria " . $this->descricaoCategoria);

        $_elementos = $_sql->get_result();
        if (!$_elementos)
            return array();

        while ($_el = $_elementos->fetch_assoc())
            $this->elementos[] = Grupo::newFromArray($_el);
    }

    function elementoNaPosicao($_posElemento) { }

    function elementoDeCodigo($_idElemento) { }

    // se $_idPagina foi informado quando categoria:: foi instanciada,
    //         lê todos os grupos que não pertencem a esta página
    // senão,
    //        lê todos os grupos que não pertencem a esta categoria
    function lerNaoElementos()
    {
        $_sql = $this->hpDB->prepare("SELECT DISTINCT g.*, 0 as idCategoria, 0 as idTipoGrupo, '' as descricaoTipoGrupo, 0 as posCategoria
                   FROM hp_grupos g, hp_gruposxcategorias gc
                  WHERE g.idGrupo not in (SELECT idGrupo FROM hp_gruposxcategorias where idCategoria = ?)
                  ORDER BY g.descricaoGrupo");
        $_sql->bind_param("i", $this->idCategoria);

        if (!$_sql->execute())
           throw new \Exception("Erro ao ler não elementos da categoria " . $this->hpDB->real_escape_string($this->descricaoCategoria) ."!");

        $_naoElementos = $_sql->get_result();
        while ($_nel = $_naoElementos->fetch_assoc())
        {
            $_grupo = Grupo::newFromArray($_nel);
            $naoElementos[] = $_grupo->getArray();
        }

        return isset($naoElementos) ? $naoElementos : false ;
    }

    function deslocarElementoParaCima($_idElemento)
    {
        // calcula a posição do grupo anterior.
        $_sql = $this->hpDB->prepare("SELECT posCategoria FROM hp_gruposxcategorias  WHERE idCategoria = ? AND idGrupo = ?");
        $_sql->bind_param("ii", $this->idCategoria, $_idElemento);
        if (!$_sql->execute())
            throw new \Exception("Não consegui ler a posição do elemento na categoria");

        $_result = $_sql->get_result()->fetch_assoc();
        if (!$_result or $_result['posCategoria'] == 0)
            throw new \Exception ("Não há qualquer grupo na categoria : $this->idCategoria :  " . $this->hpDB->real_escape_string($this->descricaoCategoria) . " com chave $_idElemento");

        $_ProxPosCategoria = $_result['posCategoria']-1;
        if ($_ProxPosCategoria > 0) {
            // desloca o grupo anterior para baixo (se ele não existir, não tem problema).
            $_sql = $this->hpDB->prepare("UPDATE hp_gruposxcategorias set posCategoria = posCategoria + 1 WHERE idCategoria = ? AND posCategoria = ?");
            $_sql->bind_param("ii", $this->idCategoria, $_ProxPosCategoria);
            if (!$_sql->execute())
                throw new \Exception("Erro ao deslocar elemento anterior para baixo");

            // desloca para cima o grupo solicitado...
            $_sql = $this->hpDB->prepare("UPDATE hp_gruposxcategorias set posCategoria = ? WHERE idCategoria = ? AND idGrupo = ?");
            $_sql->bind_param("iii", $_ProxPosCategoria, $this->idCategoria, $_idElemento);
            if (!$_sql->execute())
                throw new \Exception("Erro ao deslocar elemento para cima");
        }
        return $this->hpDB->getAffectedRows();
    }

    public function deslocarElementoParaBaixo($_idElemento)
    {
        // calcula a posição do elemento posterior.
        $_sql = $this->hpDB->prepare("SELECT posCategoria FROM hp_gruposxcategorias WHERE idCategoria = ? AND idGrupo = ?");
        $_sql->bind_param("ii", $this->idCategoria, $_idElemento);
        if (!$_sql->execute())
            throw new \Exception ("Não há qualquer grupo na categoria : $this->idCategoria :  " . $this->hpDB->real_escape_string($this->descricaoCategoria) . " com chave $_idElemento");
        $_result = $_sql->get_result()->fetch_assoc();
        $_ProxPosCategoria = $_result['posCategoria']+1;

        // obtém o total de elementos deste grupo.
        $_sql = $this->hpDB->prepare("SELECT Count(1) as numGrupos from hp_gruposxcategorias WHERE idCategoria = ?");
        $_sql->bind_param("i", $this->idCategoria);
        if (!$_sql->execute())
            throw new \Exception ("Não há qualquer grupo na categoria : $this->idCategoria :  " . $this->hpDB->real_escape_string($this->descricaoCategoria) . "!");
        $_result = $_sql->get_result()->fetch_assoc();
        $_numGrupos = $_result['numGrupos'];

        // desloca o elemento posterior para cima. (se ele não existir não tem problema)
        $_sql = $this->hpDB->prepare("UPDATE hp_gruposxcategorias set posCategoria = posCategoria - 1 WHERE idCategoria = ? AND posCategoria = ?");
        $_sql->bind_param("ii", $this->idCategoria, $_ProxPosCategoria);
        if (!$_sql->execute())
            throw new \Exception("Erro ao deslocar elemento seguinte para cima");

        // desloca para baixo o elemento solicitado.
        $_sql = $this->hpDB->prepare("UPDATE hp_gruposxcategorias set posCategoria = ? WHERE idCategoria = ? AND idGrupo = ?");
        $_sql->bind_param("iii", $_ProxPosCategoria, $this->idCategoria, $_idElemento);
        if (!$_sql->execute())
            throw new \Exception("Erro ao deslocar elemento para baixo");

        return $this->hpDB->getAffectedRows();
    }

    function incluirElemento($_idElemento, $_posElemento = 0)
    {
        if ($_posElemento > 0)
        {
            $_sql = $this->hpDB->prepare("insert into hp_gruposxcategorias (idGrupo, idCategoria, posCategoria) VALUES (?, ?, ?)");
            $_sql->bind_param("iii", $_idElemento, $this->idCategoria, $_posElemento);
        }
        else
        {
            $_sql = $this->hpDB->prepare("SELECT COALESCE(MAX(posCategoria), 0) as maxPos from hp_gruposxcategorias where idCategoria = ?");
            $_sql->bind_param("i", $this->idCategoria);
            if (!$_sql->execute())
                throw new \Exception("Erro ao obter maior posição na categoria: " . $this->descricaoCategoria);
            $_row = $_sql->get_result()->fetch_assoc();
            $maxPos = $_row['maxPos']+1;

            $_sql = $this->hpDB->prepare("insert into hp_gruposxcategorias (idGrupo, idCategoria, posCategoria) VALUES (?, ?, ?)");
            $_sql->bind_param("iii", $_idElemento, $this->idCategoria, $maxPos);
        }

        if (!$_sql->execute())
            throw new \Exception('erro ao inserir um grupo na categoria: ' . $this->idCategoria . ' : ' . $_idElemento);

        return true;
    }

    function excluirElemento($_idElemento)
    {
        $_sql = $this->hpDB->prepare("DELETE FROM hp_gruposxcategorias where idGrupo = ? and idCategoria = ?");
        $_sql->bind_param("ii", $_idElemento, $this->idCategoria);

        return $_sql->execute();
    }

    static function getCategorias()
    {
        // como esta função é um método de classe, não posso usar nenhuma variável de instância, apenas locais e globais.
        global $global_hpDB;

        $_sql = "SELECT * FROM hp_categorias ORDER BY descricaoCategoria";
        $_categorias = $global_hpDB->simple_query($_sql);
        if (!$_categorias)
        {
            throw new \Exception('erro: não consegui ler nenhuma categoria!');
        }
        else
        {
            foreach ($_categorias as $_cat)
            {
                $categorias[] = array(
                    'idCategoria' => $_cat['idCategoria'],
                    'descricaoCategoria' => $_cat['descricaoCategoria'],
                    'categoriaRestrita' => $_cat['categoriaRestrita'],
                    'restricaoCategoria' => $_cat['restricaoCategoria']);
            }
        }
        return isset($categorias) ? $categorias : FALSE;
    }

    static function getCount()
    {
        global $global_hpDB;

        $_sql = "SELECT COUNT(*) as numElementos FROM hp_categorias";
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

}# categoria */

?>
