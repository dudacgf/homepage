<?php

Namespace Shiresco\Homepage\Pagina;

class Pagina extends ElementoAgrupado
{
    var $idPagina;
    var $tituloPagina;
    var $temaPagina;
    var $tituloTabela;
    var $displayFortune;
    var $displayImagemTitulo;
    var $displaySelectColor;

    public function __construct($_idPagina = null) {
        parent::__construct();

        if (isset($_idPagina))
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_paginas where idPagina = ?");
            $_sql->bind_param("i", $_idPagina);
            if (!$_sql->execute())
                throw new \Exception("idPagina incorreto: $_idPagina");

            $line = $_sql->get_result()->fetch_assoc();

            $this->tituloPagina = $line['TituloPagina'];
            if (isset($_REQUEST['class']) && $_REQUEST['class'] != '')
            {
                $this->temaPagina = $_REQUEST['class'];
            }
            else
            {
                $this->temaPagina = $line['temaPagina'];
            }
            $this->tituloTabela = $line['TituloTabela'];
            $this->idPagina = $line['idPagina'];
            $this->displayFortune = $line['displayFortune'];
            $this->displayImagemTitulo = $line['displayImagemTitulo'];
            $this->displaySelectColor = $line['displaySelectColor'];
        }
    }

    public function inserir()
    {
        $_sql = $this->hpDB->prepare("INSERT INTO hp_paginas
                                      (tituloPagina, tituloTabela, temaPagina, displayFortune, displayImagemTitulo, displaySelectColor)
                                      VALUES (?, ?, ?, ?, ?, ?)");
        $_sql->bind_param("sssiii", $this->tituloPagina, $this->tituloTabela , $this->temaPagina, $this->displayFortune, $this->displayImagemTitulo, $this->displaySelectColor);


        // executa o query e resgata o id criado.
        if (!$_sql->execute())
            throw new \Exception ("erro ao gravar a página: " . $this->idPagina . ": " . $this->tituloPagina);

        return $this->hpDB->getLastInsertId();
    }

    public function atualizar ()
    {
        $_sql = $this->hpDB->prepare("UPDATE hp_paginas SET tituloPagina = ?, tituloTabela = ?, temaPagina = ?,
                                                            displayFortune = ?, displayImagemTitulo = ?, displaySelectColor = ?
                                      WHERE idPagina = ?");
        $_sql->bind_param("sssiiii", $this->tituloPagina, $this->tituloTabela, $this->temaPagina,
            $this->displayFortune, $this->displayImagemTitulo, $this->displaySelectColor, $this->idPagina);

        // executa o query e retorna o número de linhas afetadas (uma, se tudo der certo)
        if (!$_sql->execute())
            throw new \Exception ("erro ao gravar a página: " . $this->idPagina . ": " . $this->tituloPagina);

        return $this->hpDB->getAffectedRows();
    }

    public function excluir()
    {
        $result = true;

        $this->lerElementos();

        $result = $result and $this->hpDB->begin_transaction();

        foreach ($this->elementos as $elemento) {
            $result = $result and $this->excluirElemento($elemento->idCategoria);
        }

        $_sql = $this->hpDB->prepare("delete from hp_paginas where idPagina = ?");
        $_sql->bind_param("i", $this->idPagina);
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

    public function getArray()
    {
        if (isset($this->idPagina))
        {
            return array(
                'idPagina' => $this->idPagina,
                'tituloPagina' => $this->tituloPagina,
                'tituloTabela' => $this->tituloTabela,
                'temaPagina' => $this->temaPagina,
                'displayImagemTitulo' => $this->displayImagemTitulo,
                'displaySelectColor' => $this->displaySelectColor                );
        }
        else
        {
            throw new \Exception('erro em pagina::getArray(). Não inicializado!');
        }
    }

    public function getBigArray( )
    {
        if ( !isset( $this->idPagina ) )
        {
            throw new \Exception ( 'Não é possível ler esta página. ' );
        }

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
                // limpa separadores repetidos no meio da string.
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

        $_sql = $this->hpDB->prepare("select p.idPagina, c.idCategoria, c.descricaoCategoria, c.categoriaRestrita, c.restricaoCategoria, cp.posPagina,
                      g.idGrupo, g.idTipoGrupo, g.descricaoGrupo, g.grupoRestrito, g.restricaoGrupo, gc.posCategoria,
                      e.idElemento, e.descricaoElemento, e.idTipoElemento, e.posGrupo, e.urlElemento,
                      e.urlElementoLocal, e.dicaElemento, e.urlElementoTarget, e.formNome, e.formNomeCampo,
                      e.formTamanhoCampo, e.separadorBreakBefore, e.templateFileName,
                      e.urlElementoSSL, e.urlElementoSVN
                 from hp_paginas p, hp_categoriasxpaginas cp, hp_categorias c,
                      hp_gruposxcategorias gc, hp_grupos g, hp_elementos e
                where p.idPagina = cp.idPagina
                  and cp.idCategoria = c.idCategoria
                  and c.idCategoria = gc.idCategoria
                  and ( (categoriaRestrita = 0)
                      or (categoriaRestrita = 1 and restricaoCategoria REGEXP ? ) )
                  and gc.idGrupo = g.idGrupo
                  and g.idGrupo = e.idGrupo
                  and ( (g.grupoRestrito = 0)
                      or (g.grupoRestrito = 1 and g.restricaoGrupo REGEXP ? ) )
                  and p.idPagina = ?
                order by cp.posPagina, gc.posCategoria, e.posGrupo");
        $_sql->bind_param("ssi", $_grParm, $_grParm, $this->idPagina);

        if (!$_sql->execute())
           throw new \Exception("Não consegui ler o BigArray para a página " . $this->hpDB->real_escape_string($this->tituloPagina) . "!");

        $_elementos = $_sql->get_result();

        if (!$_elementos)
        {
            return array();
        }

        while ($_el = $_elementos->fetch_assoc())
        {
            $elementos[] = array(
                   'idPagina' => $_el['idPagina'],
                   'idElemento' => $_el['idElemento'],
                   'idGrupo' => $_el['idGrupo'],
                   'idCategoria' => $_el['idCategoria'],
                   'descricaoCategoria' => $_el['descricaoCategoria'],
                   'posPagina' => $_el['posPagina'],
                   'idTipoGrupo' => $_el['idTipoGrupo'],
                   'descricaoGrupo' => $_el['descricaoGrupo'],
                   'posCategoria' => $_el['posCategoria'],
                   'descricaoElemento' => $_el['descricaoElemento'],
                   'idTipoElemento' => $_el['idTipoElemento'],
                   'posGrupo' => $_el['posGrupo'],
                   'urlElemento' => $_el['urlElemento'],
                   'urlElementoLocal' => $_el['urlElementoLocal'],
                   'dicaElemento' => $_el['dicaElemento'],
                   'urlElementoTarget' => $_el['urlElementoTarget'],
                   'formNome' => $_el['formNome'],
                   'formNomeCampo' => $_el['formNomeCampo'],
                   'formTamanhoCampo' => $_el['formTamanhoCampo'],
                   'separadorBreakBefore' => $_el['separadorBreakBefore'],
                   'templateFileName' => $_el['templateFileName'],
                   'urlElementoSSL' => $_el['urlElementoSSL'],
                   'urlElementoSVN' => $_el['urlElementoSVN']
                   );
        }

        return isset( $elementos ) ? $elementos : array( ) ;
    }

    static function newFromArray($_array)
    {
    }

    function numeroElementos() { }

    public function lerElementos() {
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
                // limpa separadores repetidos no meio da string.
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

        $_sql = $this->hpDB->prepare("SELECT c.*, cp.* FROM hp_categorias c, hp_categoriasxpaginas cp
                                      WHERE cp.idCategoria = c.idCategoria
                                        AND cp.idPagina = ?
                                        AND ( (categoriaRestrita = 0)
                                         OR (categoriaRestrita = 1 and restricaoCategoria REGEXP ? ) )
                                      ORDER BY cp.PosPagina;");
        $_sql->bind_param("is", $this->idPagina, $_grParm);

        if (!$_sql->execute())
            throw new \Exception("Não consegui ler os elementos da página [" . $this->hpDB->real_escape_string($this->tituloPagina) . "]!");

        $_elementos = $_sql->get_result();
        if (!$_elementos)
        {
            return array();
        }

        foreach ($_elementos as $_el)
        {
            $this->elementos[] = Categoria::newFromArray($_el);
        }
    }

    public function lerPagina() {
        // Leio as categorias da página e percorro-as, incluíndo-as no template
        $this->lerElementos();
        foreach ($this->elementos as $categ) {

            $descricoesCategorias[] = array('index' => $categ->posPagina, 'categoria' => $categ->descricaoCategoria);
            
            // Leio os grupos desta categoria e percorro-os, incluíndo-os no template
            $categ->lerElementos();
            foreach ($categ->elementos as $grupo) 
            {

                // Leio os elementos deste grupo e percorro-os, incluíndo-os no template
                $grupo->lerElementos();
                foreach($grupo->elementos as $elemento) 
                {
                    $elementos[] = $elemento->getArray();
                }		

                $grupos[] = array(
                                'grupo' => $grupo->descricaoGrupo,
                                'idtipoGrupo' => $grupo->idTipoGrupo,
                                'elementos' => $elementos);
                $elementos = [];

            }
            
            $descricoesGrupos[] = array(
                                    'index' => $grupo->posCategoria, 
                                    'idGrupo' => $grupo->idGrupo,
                                    'grupos' => $grupos 
                                    );
            $grupos = [];

        }
        return array('descricoesCategorias' => (isset($descricoesCategorias) ? $descricoesCategorias: []),
                     'descricoesGrupos' => (isset($descricoesGrupos)? $descricoesGrupos: []));
    }

    function elementoNaPosicao($_posElemento) { }

    function elementoDeCodigo($_idElemento) { }

    public function lerNaoElementos()
    {
        $_sql = $this->hpDB->prepare("SELECT DISTINCT c.idCategoria
                   FROM hp_categorias c
                  WHERE c.idCategoria not in (SELECT idCategoria FROM hp_categoriasxpaginas cp WHERE cp.idPagina = ?)
                  ORDER BY descricaoCategoria;");
        $_sql->bind_param("i", $this->idPagina);

        if (!$_sql->execute())
            throw new \Exception("Não consegui ler as categorias não incluídas na página " . $this->hpDB->real_escape_string($this->tituloPagina) . "!]");

        $_naoElementos = $_sql->get_result();

        while ($_nel = $_naoElementos->fetch_assoc())
        {
            $_categoria = new Categoria($_nel['idCategoria']);
            $naoElementos[] = $_categoria->getArray();
        }

        return isset($naoElementos) ? $naoElementos : false ;
    }

    function deslocarElementoParaCima($_idElemento)
    {
        // calcula a posição do elemento anterior.
        $_sql = $this->hpDB->prepare("SELECT posPagina FROM hp_categoriasxpaginas WHERE idPagina = ? AND idCategoria = ?");
        $_sql->bind_param("ii", $this->idPagina, $_idElemento);

        if (!$_sql->execute())
            throw new \Exception("Não consegui obter a posição da categoria $_idElemento!");

        $_return = $_sql->get_result()->fetch_assoc();

        if (!$_return)
            throw new \Exception ("Não há qualquer categoria da página : $this->idPagina :  $this->tituloPagina com chave $_idElemento");

        $_ProxPosPagina = $_return['posPagina']-1;

        if ($_ProxPosPagina > 0) {
            // desloca o elemento anterior para baixo. (se ele não existir não tem problema)
            $_sql = $this->hpDB->prepare("UPDATE hp_categoriasxpaginas set posPagina = posPagina + 1 WHERE idPagina = ? AND posPagina = ?");
            $_sql->bind_param("ii", $this->idPagina, $_ProxPosPagina);
            if (!$_sql->execute())
                throw new \Exception("Não consegui deslocar o elemento anterior para baixo");

            // desloca para cima o elemento solicitado.
            $_sql = $this->hpDB->prepare("UPDATE hp_categoriasxpaginas set posPagina = ? WHERE idPagina = ? AND idCategoria = ?");
            $_sql->bind_param("iii", $_ProxPosPagina, $this->idPagina, $_idElemento);

            if (!$_sql->execute())
                throw new \Exception("Não consegui deslocar o elemento para cima");
        }

        return $this->hpDB->getAffectedRows();
    }

    public function deslocarElementoParaBaixo($_idElemento)
    {
        // calcula a posição do elemento posterior.
        $_sql = $this->hpDB->prepare("SELECT posPagina FROM hp_categoriasxpaginas WHERE idPagina = ? AND idCategoria = ?");
        $_sql->bind_param("ii", $this->idPagina, $_idElemento);

        if (!$_sql->execute())
            throw new \Exception("Não consegui obter a posição da categoria $_idElemento!");

        $_return = $_sql->get_result()->fetch_assoc();

        $_ProxPosPagina = $_return['posPagina']+1;

        // obtém o total de elementos deste grupo.
        $_sql = $this->hpDB->prepare("SELECT Count(1) as numCategorias from hp_categoriasxpaginas WHERE idPagina = ?");
        $_sql->bind_param("i", $this->idPagina);
        if (!$_sql->execute())
            throw new \Exception("Não consegui obter o número de categorias da página [" . $this->hpDB->real_escape_string($this->tituloPagina) . "]!");

        $_row = $_sql->get_result()->fetch_assoc();

        if ($_row['numCategorias'] >= $_ProxPosPagina) {
            // desloca o elemento posterior para cima. (se ele não existir não tem problema)
            $_sql = $this->hpDB->prepare("UPDATE hp_categoriasxpaginas set posPagina = posPagina - 1 WHERE idPagina = ? AND posPagina = ?");
            $_sql->bind_param("ii", $this->idPagina, $_ProxPosPagina);
            if (!$_sql->execute())
                throw new \Exception("Não consegui deslocar o elemento seguinte para cima");

            // desloca para baixo o elemento solicitado.
            $_sql = $this->hpDB->prepare("UPDATE hp_categoriasxpaginas set posPagina = ? WHERE idPagina = ? AND idCategoria = ?");
            $_sql->bind_param("iii", $_ProxPosPagina, $this->idPagina, $_idElemento);
            if (!$_sql->execute())
                throw new \Exception("Não consegui deslocar o elemento para baixo");
        }

        return $this->hpDB->getAffectedRows();
    }

    public function incluirElemento($_idElemento, $_posElemento = 0)
    {
        if ($_posElemento > 0)
        {
            $_sql = $this->hpDB->prepare("insert into hp_categoriasxpaginas (idCategoria, idPagina, posPagina) values (?, ?, ?)");
            $_sql->bind_param("iii",  $_idElemento, $this->idPagina, $_posElemento);
        }
        else
        {
            $_sql = $this->hpDB->prepare("SELECT COALESCE(MAX(posPagina), 0) as maxPos from hp_categoriasxpaginas where idPagina = ?");
            $_sql->bind_param("i", $this->idPagina);
            if (!$_sql->execute())
                throw new \Exception("Erro ao obter maior posição na páginas: " . $this->descricaoCategoria);

            $_row = $_sql->get_result()->fetch_assoc();
            $maxPos = $_row['maxPos']+1;

            $_sql = $this->hpDB->prepare("INSERT INTO hp_categoriasxpaginas (idCategoria, idPagina, posPagina) VALUES (?, ?, ?)");
            $_sql->bind_param("iii", $_idElemento, $this->idPagina, $maxPos);
        }

        if (!$_sql->execute())
            throw new \Exception('erro ao inserir elemento na página: ' . $this->idPagina . ' : ' . $_idElemento);

        return True;
    }

    public function excluirElemento($_idElemento)
    {
        $_sql = $this->hpDB->prepare("DELETE FROM hp_categoriasxpaginas where idCategoria = ? and idPagina = ?");
        $_sql->bind_param("ii", $_idElemento, $this->idPagina);

        return $_sql->execute();
    }

    static function getPaginas()
    {
        global $global_hpDB;

        $_sql = "SELECT idPagina FROM hp_paginas";
        $_paginas = $global_hpDB->simple_query($_sql);
        if (!$_paginas)
        {
            throw new \Exception('erro: não consegui ler nenhuma página!');
        }
        else
        {
            foreach ($_paginas as $_pag)
            {
                $_pagina = new pagina($_pag['idPagina']);
                $paginas[] = $_pagina->getArray();
            }
        }
        return isset($paginas) ? $paginas : FALSE;
    }

    static function getCount()
    {
        global $global_hpDB;

        $_sql = "SELECT COUNT(*) as numElementos FROM hp_paginas";
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

}

?>
