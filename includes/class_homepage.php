<?php

// define os tipos de elementos possíveis
define('ELEMENTO_SIMPLES', 1);
define('ELEMENTO_AGRUPADO', 2);

// define os tipos de elementos simples
define('ELEMENTO_LINK', 1);
define('ELEMENTO_FORM', 2);
define('ELEMENTO_SEPARADOR', 3);
define('ELEMENTO_IMAGEM', 4);
define('ELEMENTO_TEMPLATE', 6);

class tiposElementos
{#
    static function getArray()
    {
        global $global_hpDB;

        $_sql = "select * from hp_tiposelementos";
        $tipos = $global_hpDB->query($_sql);
        if (!$tipos)
        {
            throw new Exception('não consegui ler a tabela de tipos de elementos!');
        }
        else
        {
            foreach ($tipos as $tipoElemento)
            {
                $tiposelementos[$tipoElemento['idTipoElemento']] = $tipoElemento['descricaoTipoElemento'];
            }
        }

        return isset($tiposelementos) ? $tiposelementos : FALSE;

    }

}#

class tiposGrupos
{#
    static function getArray()
    {
        global $global_hpDB;

        $_sql = "select * from hp_tiposgrupos";
        $tipos = $global_hpDB->query($_sql);
        if (!$tipos)
        {
            throw new Exception('não consegui ler a tabela de tipos de grupos!');
        }
        else
        {
            foreach ($tipos as $tipoGrupo)
            {
                $tiposgrupos[$tipoGrupo['idTipoGrupo']] = $tipoGrupo['descricaoTipoGrupo'];
            }
        }

        return isset($tiposgrupos) ? $tiposgrupos : FALSE;

    }

}#  tiposGrupos*/

class cssEstilos
{#
    var $hpDB;
    var $idEstilo;
    var $nomeEstilo;
    var $comentarioEstilo;

    public function __construct($_idEstilo = null) {
        global $global_hpDB;
        $this->hpDB = $global_hpDB;

        if ($_idEstilo == null)
            return;

        $_sql = $this->hpDB->prepare('select * from hp_cssestilos where idEstilo = ?');
        $_sql->bind_param('i', $_idEstilo);
        if (!$_sql->execute())
            return;

        $_result = $_sql->get_result()->fetch_assoc();
        $this->idEstilo = $_result['idEstilo'];
        $this->nomeEstilo = $_result['nomeEstilo'];
        $this->comentarioEstilo = $_result['comentarioEstilo'];

        return;
    }

    public function inserir() {
        $_sql = $this->hpDB->prepare('insert into hp_cssestilos (nomeEstilo, comentarioEstilo) values (?, ?)');
        $_sql->bind_param('ss', $this->nomeEstilo, $this->comentarioEstilo);
        if (!$_sql->execute())
            throw new Exception('erro ao inserir estilo: ' . $this->hpDB->real_escape_string($_sql->error));

        return $this->hpDB->getLastInsertId();
    }

    public function atualizar() {
        $_sql = $this->hpDB->prepare('update hp_cssestilos set comentarioEstilo = ? where nomeEstilo = ?');
        $_sql->bind_param('ss', $this->comentarioEstilo, $this->nomeEstilo);
        if (!$_sql->execute())
            throw new Exception('erro ao atualizar estilo: ' . $this->hpDB->real_escape_string($_sql->error));

        return $this->hpDB->getAffectedRows();
    }

    static function getArray()
    {
        global $global_hpDB;

        $_sql = "select * from hp_cssestilos";
        $cssestilos = $global_hpDB->query($_sql);
        if (!$cssestilos)
        {
            throw new Exception('não consegui ler a tabela de tipos de grupos!');
        }
        else
        {
            foreach ($cssestilos as $cssestilo)
            {
                $estilos[] = array(
                        'idEstilo' => $cssestilo['idEstilo'],
                        'nomeEstilo' => $cssestilo['nomeEstilo'],
                        'comentarioEstilo' => $cssestilo['comentarioEstilo']
                        );
            }
        }

        return isset($estilos) ? $estilos : FALSE;

    }

    static function getClassNames()
    {
        global $global_hpDB;

        $_sql = "select nomeEstilo from hp_cssestilos";
        $cssestilos = $global_hpDB->query($_sql);
        if (!$cssestilos)
        {
            throw new Exception('não consegui ler a tabela de nomes de estilos!');
        }
        else
        {
            foreach ($cssestilos as $cssestilo)
            {
                $estilos[] = $cssestilo['nomeEstilo'];
            }
        }

        return isset($estilos) ? $estilos : FALSE;

    }

    static function estiloExiste($_nomeEstilo = null) {
        if (!$_nomeEstilo)
            throw new Exception('Não posso verificar se um estilo existe se não me passarem seu nome');

        global $global_hpDB;

        $_sql = $global_hpDB->prepare('SELECT * FROM hp_cssestilos WHERE nomeEstilo = ?');
        $_sql->bind_param('s', $_nomeEstilo);

        try {
            if (!$_sql->execute())
                throw new Exception('Erro na consulta ao banco de dados');
        } catch (Exception $e) {
            throw new Exception('Erro de banco de dados: ' . $e->getMessage());
        }

        return $_sql->get_result()->num_rows;
    }

}#  cssEstilos */

abstract class elemento
{#
    var $hpDB;
    var $idGrupo;
    var $posGrupo;
    var $comportamentoElemento;
    var $idElemento;
    var $descricaoElemento;
    var $tipoElemento;

    // finalmente resolvido o problema da conexão única com a utilização da variáveil $global_hpDB;
    function __construct()
    {
        global $global_hpDB;
        $this->hpDB = $global_hpDB;
        $this->comportamentoElemento = ELEMENTO_SIMPLES;
    }

    abstract function inserir();

    abstract function atualizar();

    abstract function excluir();

    abstract function getArray();

    #abstract static function newFromArray($_array);

    #abstract static function getCount();

}#  elemento*/

abstract class elementoAgrupado extends elemento
{#
    public $elementos = array();

    function __construct()
    {
        parent::__construct();
        $this->comportamentoElemento = ELEMENTO_AGRUPADO;
    }

    abstract function numeroElementos();

    abstract function lerElementos();

    abstract function elementoNaPosicao($_posElemento);

    abstract function elementoDeCodigo($_idElemento);

    abstract function lerNaoElementos();

    abstract function incluirElemento($_idElemento, $_posElemento = 0);

    abstract function excluirElemento($_idElemento);

    abstract function deslocarElementoParaCima($_idElemento);

    abstract function deslocarElementoParaBaixo($_idElemento);

}#  elementoAgrupado */

class wLink extends elemento
{#
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
        $this->tipoElemento = ELEMENTO_LINK;

        // se não passou um id para este link, é porque ele está sendo criado.
        // se passou, ele já existe e vai ser lido.
        if (isset($_idLink) && $_idLink != NULL) {

            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idLink);
            if (!$_sql->execute()) {
                throw new Exception("Código do link provavelmente incorreto: $_idLink");
            }

            try {
                $line = $_sql->get_result()->fetch_assoc();
            } catch(Exception $e) {
                throw new Exception("Código do link provavelmente incorreto: $_idLink");
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
            throw new Exception ("erro ao gravar o novo link: $this->descricaoLink");
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
            throw new Exception ("erro ao gravar o link: " . $this->idLink . ": " . $this->descricaoLink);
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
                throw new Exception ("erro ao excluir o link: " . $this->idLink . ": " . $this->descricaoLink);
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
                    'tipoElemento' => ELEMENTO_LINK,
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
            throw new Exception('erro em wLink::getArray(). Não inicializado!' .  debug_print_backtrace());
        }
    }

    static function newFromArray($_array)
    {

        $newLink = new wLink(NULL);

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
        $_sql->bind_param("i", ...array(ELEMENTO_LINK));

        if (!$_sql->execute())
            throw new Exception("Erro ao contar número de links");

        $_row = $_sql->get_result()->fetch_assoc();
        if (!$_row)
            return 0;

        return $_row['numElementos'];
    }

}#  wLink */

class Visita
{#
    var $idGo;
    var $idElemento;
    var $dataVisita;

    public function inserir() {
        global $global_hpDB;
        if (!$this->idElemento)
            throw new Exception("Não posso inserir sem saber qual Link foi visitado!");

        $_sql = $global_hpDB->prepare("INSERT INTO hp_visitas (idElemento) VALUES (?)");
        $_sql->bind_param("i", $this->idElemento);

        if (!$_sql->execute())
            throw new Exception("Erro ao gravar visita: $_sql->error");

        return true;
    }

    static function totalLinks($intervalo = '7') {
        global $global_hpDB;

        // pega o total de links diferentes visitados
        $_sql = $global_hpDB->prepare('SELECT COUNT(*) as totalLinks FROM (
          SELECT count(*)
            FROM hp_visitas
           WHERE dataVisita > NOW() + INTERVAL -? DAY
           GROUP BY idElemento) as temp');
        $_sql->bind_param('i', $intervalo);

        if (!$_sql->execute())
            throw new Exception("Erro ao ler contagem de visitas: $_sql->error");

        return $_sql->get_result()->fetch_assoc()['totalLinks'];
    }

    static function lerContagem($intervalo = '7', $numResultados = 5) {
        global $global_hpDB;

        // le a contagem agrupada por link visitado
        $_sql = $global_hpDB->prepare('
          SELECT e.descricaoElemento, count(*) as NumVisitas,
                 Max(V.dataVisita) as ultimaVisita, Min(V.dataVisita) as primeiraVisita
            FROM hp_visitas V, hp_elementos e
           WHERE V.idElemento = e.idElemento
             AND V.dataVisita > NOW() + INTERVAL -? DAY
           GROUP BY V.idElemento
           ORDER BY numVisitas DESC, ultimaVisita DESC
           LIMIT ?;');
        $_sql->bind_param('ii', $intervalo, $numResultados);

        if (!$_sql->execute())
            throw new Exception("Erro ao ler contagem de visitas: $_sql->error");

        $result = $_sql->get_result();
        while ($_line = $result->fetch_assoc())
            $_lines[] = $_line;

        return $_lines;

    }
}

class wForm extends elemento
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
        $this->tipoElemento = ELEMENTO_FORM;

        if (isset($_idForm) && $_idForm != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idForm);
            if (!$_sql->execute()) {
                throw new Exception("idForm incorreto: $_idForm");
            }

            $line = $_sql->get_result()->fetch_assoc();

            if (!$line)
            {
                throw new Exception("idForm incorreto: $_idForm");
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
            throw new Exception("erro ao criar o Form: '$this->descricaoForm'!");
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
            throw new Exception("erro ao gravar o form: '$this->descricaoForm'!");
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
                throw new Exception ("erro ao excluir o form: '$this->descricaoForm'!");
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
                    'tipoElemento' => ELEMENTO_FORM,
                    'nomeForm' => $this->nomeForm,
                    'acao' => $this->acao,
                    'nomeCampo' => $this->nomeCampo,
                    'tamanhoCampo' => $this->tamanhoCampo,
                    'descricaoForm' => $this->descricaoForm);
        }
        else
        {
            throw new Exception('erro em wForm::getArray(). Não inicializado!');
        }
    }

    static function newFromArray($_array)
    {
        $newForm = new wForm(NULL);

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
        $_sql->bind_param("i", ...array(ELEMENTO_FORM));
        if (!$_sql->execute()) {
            throw new Exception("Não consegui ler o número de elementos do tipo form");
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

class wSeparador extends elemento
{#
    var $idSeparador;
    var $descricaoSeparador;
    var $breakBefore;

    public function __construct($_idSeparador) // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
    {
        parent::__construct();
        $this->tipoElemento = ELEMENTO_SEPARADOR;

        if (isset($_idSeparador) && $_idSeparador != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idSeparador);

            if (!$_sql->execute()) {
                throw new Exception("idSeparador incorreto: $_idSeparador");
            }

            $line = $_sql->get_result()->fetch_assoc();
            if (!$line)
            {
                throw new Exception("idSeparador incorreto: $_idSeparador");
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
            throw new Exception("erro ao criar o separador: [$this->descricaoSeparador]!");
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
            throw new Exception("erro ao gravar o separador: '$this->descricaoSeparador'!");
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
                throw new Exception("erro ao excluir o separador: $this->descricaoSeparador");
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
                    'tipoElemento' => ELEMENTO_SEPARADOR,
                    'descricaoSeparador' => $this->descricaoSeparador,
                    'breakBefore' => $this->breakBefore    );
        }
        else
        {
            throw new Exception('erro em wSeparador::getArray(). Não inicializado!'. debug_print_backtrace());
        }
    }

    static function newFromArray($_array)
    {
        $newSeparador = new wSeparador(NULL);

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
        $_sql->bind_param("i", ...array(ELEMENTO_SEPARADOR));

        if (!$_sql->execute())
            throw new Exception("Não consegui ler o número de separadores.");

        $_count = $_sql->get_result()->fetch_assoc();
        if (!$_count)
            return 0;
        return $_count['numElementos'];
    }


}#  wSeparador */

class wImagem extends elemento
{#
    var $idImagem;
    var $descricaoImagem;
    var $ImagemURL;
    var $tipoElemento;

    public function __construct($_idImagem)  // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
    {

        parent::__construct();
        $this->tipoElemento = ELEMENTO_IMAGEM;

        if (isset($_idImagem) && $_idImagem != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idImagem);

            if (!$_sql->execute())
                throw new Exception("idImagem incorreto: $_idImagem");

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
            throw new Exception ("erro ao criar a imagem: [$this->descricaoImagem]!");

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
            throw new Exception ("erro ao gravar a imagem: '$this->descricaoImagem'!");

        return $this->hpDB->getAffectedRows();
    }

    public function excluir()
    {
        if (!isset($this->idImagem))
            return NULL;

        $_sql = $this->hpDB->prepare("DELETE FROM hp_elementos where idElemento = ?");
        $_sql->bind_param("i", $this->idImagem);
        if (!$_sql->execute())
            throw new Exception ("erro ao excluir a imagem: '$this->descricaoImagem'!");

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
                    'tipoElemento' => ELEMENTO_IMAGEM,
                    'urlImagem' => $this->urlImagem,
                    'localLink' => $this->localLink,
                    'descricaoImagem' => $this->descricaoImagem);
        }
        else
        {
            throw new Exception('erro em wImagem::getArray(). Não inicializado!');
        }
    }

    static function newFromArray($_array)
    {
        $newImagem = new wImagem(NULL);

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
        $_sql->bind_param("i", ...array(ELEMENTO_IMAGEM));

        if (!$_sql->execute())
            throw new Exception("Erro ao contar número de imagens");

        $_count = $_sql->get_result()->fetch_assoc();
        if (!$_count)
            return 0;

        return $_count['numElementos'];
    }


}#  wImagem */

class wTemplate extends elemento
{#
    var $idTemplate;
    var $descricaoTemplate;
    var $nomeTemplate;

    public function __construct($_idTemplate)  // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
    {

        parent::__construct();
        $this->tipoElemento = ELEMENTO_TEMPLATE;

        if (isset($_idTemplate) && $_idTemplate != NULL)
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_elementos where idElemento = ?");
            $_sql->bind_param("i", $_idTemplate);

            if (!$_sql->execute())
                throw new Exception("idTemplate incorreto: $_idTemplate");

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
            throw new Exception ("erro ao criar o template: '$this->descricaoTemplate'!");

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
            throw new Exception ("erro ao atualizar o template: '$this->descricaoTemplate'!");

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
            throw new Exception ("erro ao excluir o template: '$this->descricaoTemplate'!");

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
                    'tipoElemento' => ELEMENTO_TEMPLATE,
                    'descricaoTemplate' => $this->descricaoTemplate,
                    'nomeTemplate' => $this->nomeTemplate );
        }
        else
        {
            throw new Exception('erro em wTemplate::getArray(). Não inicializado!');
        }
    }

    static function newFromArray($_array)
    {
        $newTemplate = new wTemplate(NULL);

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
        $_sql->bind_param("i", ...array(ELEMENTO_TEMPLATE));

        if (!$_sql->execute())
            throw new Exception("Erro ao contar templates");

        $_row = $_sql->get_result()->fetch_assoc();
        if (!$_row)
            return 0;

        return $_row['numElementos'];
    }


}#  wTemplate */

class elementoFactory {
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
            case ELEMENTO_LINK:
                $this->oElemento = new wLink($_idElm);
                break;
            case ELEMENTO_FORM:
                $this->oElemento = new wForm($_idElm);
                break;
            case ELEMENTO_SEPARADOR:
                $this->oElemento = new wSeparador($_idElm);
                break;
            case ELEMENTO_IMAGEM:
                $this->oElemento = new wImagem($_idElm);
                break;
            case ELEMENTO_TEMPLATE:
                $this->oElemento = new wTemplate($_idElm);
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
            case ELEMENTO_LINK:
                return $this->oElemento->descricaoLink;
                break;
            case ELEMENTO_FORM:
                return $this->oElemento->descricaoForm;
                break;
            case ELEMENTO_SEPARADOR:
                return $this->oElemento->descricaoSeparador;
                break;
            case ELEMENTO_IMAGEM:
                return $this->oElemento->descricaoImagem;
                break;
            case ELEMENTO_TEMPLATE:
                $this->oElemento->descricaoTemplate;
                break;
            default:
                return null;
        }
    }

    public function atualizar() {
        switch ($this->idTipoElemento) {
            case ELEMENTO_LINK:
                return $this->oElemento->atualizar();
                break;
            case ELEMENTO_FORM:
                return $this->oElemento->atualizar();
                break;
            case ELEMENTO_SEPARADOR:
                return $this->oElemento->atualizar();
                break;
            case ELEMENTO_IMAGEM:
                return $this->oElemento->atualizar();
                break;
            case ELEMENTO_TEMPLATE:
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

class grupo extends elementoAgrupado
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
                throw new Exception("idGrupo incorreto: $_idGrupo");
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
            throw new Exception ("erro ao criar o grupo: " . $this->descricaoGrupo);
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
            throw new Exception ("erro ao atualizar o grupo: " . $this->idGrupo . ": " . $this->descricaoGrupo);
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

        $result = $result and $this->hpDB->begin();

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
            throw new Exception('erro em grupo::getArray(). Não inicializado!');
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
            throw new Exception('não posso ler o numero de elementos de um grupo se não souber o grupo!');
        }

        $_sql = $this->hpDB->prepare("select COUNT(*) as numElementos from hp_elementos where idGrupo = ?");
        $_sql->bind_param("i", $this->idGrupo);
        if (!$_sql->execute())
        {
            throw new Exception('numeroElementos: could not execute query');
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
                case ELEMENTO_LINK:
                    $this->elementos[] = wLink::newFromArray($_el);
                    break;

                case ELEMENTO_FORM:
                    $this->elementos[] = wForm::newFromArray($_el);
                    break;

                case ELEMENTO_SEPARADOR:
                    $this->elementos[] = wSeparador::newFromArray($_el);
                    break;

                case ELEMENTO_IMAGEM:
                    $this->elementos[] = wImagem::newFromArray($_el);
                    break;

                case ELEMENTO_TEMPLATE:
                    $this->elementos[] = wTemplate::newFromArray($_el);
                    break;

                default:
                    throw new Exception ('tipo errado de elemento. socorro!');
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
                case ELEMENTO_LINK:
                    $elementos[] = wLink::newFromArray($_el)->getArray();
                    break;

                case ELEMENTO_FORM:
                    $elementos[] = wForm::newFromArray($_el)->getArray();
                    break;

                case ELEMENTO_SEPARADOR:
                    $elementos[] = wSeparador::newFromArray($_el)->getArray();
                    break;

                case ELEMENTO_IMAGEM:
                    $elementos[] = wImagem::newFromArray($_el)->getArray();
                    break;

                case ELEMENTO_TEMPLATE:
                    $elementos[] = wTemplate::newFromArray($_el)->getArray();
                    break;

                default:
                    throw new Exception ('tipo errado de elemento. socorro!');
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
            throw new Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo na posição $_posElemento");
        }

        $_elemento = $_sql->get_result();

        if (!$_elemento)
        {
            throw new Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo na posição $_posElemento");
        }


        $_el = $_elemento->fetch_assoc();
        switch ($_el['idTipoElemento'])
        {
            case ELEMENTO_LINK:
                $elemento = wLink::newFromArray($_el);
            break;

            case ELEMENTO_FORM:
                $elemento = wForm::newFromArray($_el);
            break;

            case ELEMENTO_SEPARADOR:
                  $elemento = wSeparador::newFromArray($_el);
            break;

            case ELEMENTO_IMAGEM:
                $elemento = wImagem::newFromArray($_el);
            break;

            case ELEMENTO_TEMPLATE:
                $elemento = wTemplate::newFromArray($_el);
            break;

            default:
                throw new Exception ('tipo errado de elemento. socorro!');
        }

        return $elemento;
    }

    function elementoDeCodigo($_idElemento)
    {
        // lê tudo: links, forms e separadores
        $_sql = $this->hpDB->prepare("select * from hp_elementos where idGrupo = ? and idElemento = ?");
        $_sql->bind_param("ii", $this->idGrupo, $_idElemento);
        if (!$_sql->execute()) {
            throw new Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        $_elemento = $_sql->get_result();

        if (!$_elemento)
        {
            throw new Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        $_el = $_elemento->fetch_assoc();
        switch ($_el['idTipoElemento'])
        {
            case ELEMENTO_LINK:
                $elemento = wLink::newFromArray($_el);
            break;

            case ELEMENTO_FORM:
                $elemento = wForm::newFromArray($_el);
            break;

            case ELEMENTO_SEPARADOR:
                  $elemento = wSeparador::newFromArray($_el);
            break;

            case ELEMENTO_IMAGEM:
                $elemento = wImagem::newFromArray($_el);
            break;

            case ELEMENTO_TEMPLATE:
                $elemento = wTemplate::newFromArray($_el);
            break;

            default:
                throw new Exception ('tipo errado de elemento. socorro!');
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
            throw new Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        try
        {
            $_posGrupo = $_sql->get_result()->fetch_assoc()['posGrupo'];
        } catch (Exception $e) {
            throw new Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
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
            throw new Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        try
        {
            $_posGrupo = $_sql->get_result()->fetch_assoc()['posGrupo'];
        } catch (Exception $e) {
            throw new Exception ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
        }

        $_ProxPosGrupo = $_posGrupo+1;

        // obtém o total de elementos deste grupo.
        $_sql = $this->hpDB->prepare("SELECT Count(1) as numElementos from hp_elementos WHERE idGrupo = ?");
        $_sql->bind_param("i", $this->idGrupo);
        if (!$_sql->execute()) {
            throw new Exception ("erro executando a query numElementos em deslocarElementoParaBaixo");
        }

        try
        {
            $_numElementos = $_sql->get_result()->fetch_assoc()['numElementos'];
        } catch (Exception $e) {
            throw new Exception ("erro executando a query numElementos em deslocarElementoParaBaixo");
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
        $_grupos = $global_hpDB->query($_sql);
        if (!$_grupos)
        {
            throw new Exception('erro: não consegui ler nenhum grupo!');
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
        $_count = $global_hpDB->query($_sql);
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

class categoria extends elementoAgrupado
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
            throw new Exception("Erro ao obter categoria: " . $_idCategoria);

        $line = $_sql->get_result()->fetch_assoc();
        if (!$line)
            throw new Exception("idCategoria incorreto: $_idCategoria");

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
            throw new Exception ("erro ao criar a categoria: " . $this->descricaoCategoria);

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
            throw new Exception ("erro ao atualizar a categoria: " . $this->idCategoria . ": " . $this->descricaoCategoria);

        return $this->hpDB->getAffectedRows();
    }

    function excluir()
    {
        $result = true;

        $this->lerElementos();

        $result = $result and $this->hpDB->begin();

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
            throw new Exception('erro em categoria::getArray(). Não inicializado!');
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
            throw new Exception("não consegui ler os elementos da categoria " . $this->descricaoCategoria);

        $_elementos = $_sql->get_result();
        if (!$_elementos)
            return array();

        while ($_el = $_elementos->fetch_assoc())
            $this->elementos[] = grupo::newFromArray($_el);
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
           throw new Exception("Erro ao ler não elementos da categoria " . $this->hpDB->real_escape_string($this->descricaoCategoria) ."!");

        $_naoElementos = $_sql->get_result();
        while ($_nel = $_naoElementos->fetch_assoc())
        {
            $_grupo = grupo::newFromArray($_nel);
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
            throw new Exception("Não consegui ler a posição do elemento na categoria");

        $_result = $_sql->get_result()->fetch_assoc();
        if (!$_result or $_result['posCategoria'] == 0)
            throw new Exception ("Não há qualquer grupo na categoria : $this->idCategoria :  " . $this->hpDB->real_escape_string($this->descricaoCategoria) . " com chave $_idElemento");

        $_ProxPosCategoria = $_result['posCategoria']-1;
        if ($_ProxPosCategoria > 0) {
            // desloca o grupo anterior para baixo (se ele não existir, não tem problema).
            $_sql = $this->hpDB->prepare("UPDATE hp_gruposxcategorias set posCategoria = posCategoria + 1 WHERE idCategoria = ? AND posCategoria = ?");
            $_sql->bind_param("ii", $this->idCategoria, $_ProxPosCategoria);
            if (!$_sql->execute())
                throw new Exception("Erro ao deslocar elemento anterior para baixo");

            // desloca para cima o grupo solicitado...
            $_sql = $this->hpDB->prepare("UPDATE hp_gruposxcategorias set posCategoria = ? WHERE idCategoria = ? AND idGrupo = ?");
            $_sql->bind_param("iii", $_ProxPosCategoria, $this->idCategoria, $_idElemento);
            if (!$_sql->execute())
                throw new Exception("Erro ao deslocar elemento para cima");
        }
        return $this->hpDB->getAffectedRows();
    }

    public function deslocarElementoParaBaixo($_idElemento)
    {
        // calcula a posição do elemento posterior.
        $_sql = $this->hpDB->prepare("SELECT posCategoria FROM hp_gruposxcategorias WHERE idCategoria = ? AND idGrupo = ?");
        $_sql->bind_param("ii", $this->idCategoria, $_idElemento);
        if (!$_sql->execute())
            throw new Exception ("Não há qualquer grupo na categoria : $this->idCategoria :  " . $this->hpDB->real_escape_string($this->descricaoCategoria) . " com chave $_idElemento");
        $_result = $_sql->get_result()->fetch_assoc();
        $_ProxPosCategoria = $_result['posCategoria']+1;

        // obtém o total de elementos deste grupo.
        $_sql = $this->hpDB->prepare("SELECT Count(1) as numGrupos from hp_gruposxcategorias WHERE idCategoria = ?");
        $_sql->bind_param("i", $this->idCategoria);
        if (!$_sql->execute())
            throw new Exception ("Não há qualquer grupo na categoria : $this->idCategoria :  " . $this->hpDB->real_escape_string($this->descricaoCategoria) . "!");
        $_result = $_sql->get_result()->fetch_assoc();
        $_numGrupos = $_result['numGrupos'];

        // desloca o elemento posterior para cima. (se ele não existir não tem problema)
        $_sql = $this->hpDB->prepare("UPDATE hp_gruposxcategorias set posCategoria = posCategoria - 1 WHERE idCategoria = ? AND posCategoria = ?");
        $_sql->bind_param("ii", $this->idCategoria, $_ProxPosCategoria);
        if (!$_sql->execute())
            throw new Exception("Erro ao deslocar elemento seguinte para cima");

        // desloca para baixo o elemento solicitado.
        $_sql = $this->hpDB->prepare("UPDATE hp_gruposxcategorias set posCategoria = ? WHERE idCategoria = ? AND idGrupo = ?");
        $_sql->bind_param("iii", $_ProxPosCategoria, $this->idCategoria, $_idElemento);
        if (!$_sql->execute())
            throw new Exception("Erro ao deslocar elemento para baixo");

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
                throw new Exception("Erro ao obter maior posição na categoria: " . $this->descricaoCategoria);
            $_row = $_sql->get_result()->fetch_assoc();
            $maxPos = $_row['maxPos']+1;

            $_sql = $this->hpDB->prepare("insert into hp_gruposxcategorias (idGrupo, idCategoria, posCategoria) VALUES (?, ?, ?)");
            $_sql->bind_param("iii", $_idElemento, $this->idCategoria, $maxPos);
        }

        if (!$_sql->execute())
            throw new Exception('erro ao inserir um grupo na categoria: ' . $this->idCategoria . ' : ' . $_idElemento);

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
        $_categorias = $global_hpDB->query($_sql);
        if (!$_categorias)
        {
            throw new Exception('erro: não consegui ler nenhuma categoria!');
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
        $_count = $global_hpDB->query($_sql);
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

class pagina extends elementoAgrupado
{#
    var $idPagina;
    var $tituloPagina;
    var $classPagina;
    var $tituloTabela;
    var $displayFortune;
    var $displayImagemTitulo;
    var $displaySelectColor;

    public function __construct($_idPagina)
    {
        parent::__construct();

        if (isset($_idPagina))
        {
            $_sql = $this->hpDB->prepare("SELECT * from hp_paginas where idPagina = ?");
            $_sql->bind_param("i", $_idPagina);
            if (!$_sql->execute())
                throw new Exception("idPagina incorreto: $_idPagina");

            $line = $_sql->get_result()->fetch_assoc();

            $this->tituloPagina = $line['TituloPagina'];
            if (isset($_REQUEST['class']) && $_REQUEST['class'] != '')
            {
                $this->classPagina = $_REQUEST['class'];
            }
            else
            {
                $this->classPagina = $line['classPagina'];
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
                                      (tituloPagina, tituloTabela, classPagina, displayFortune, displayImagemTitulo, displaySelectColor)
                                      VALUES (?, ?, ?, ?, ?, ?)");
        $_sql->bind_param("sssiii", $this->tituloPagina, $this->tituloTabela , $this->classPagina, $this->displayFortune, $this->displayImagemTitulo, $this->displaySelectColor);


        // executa o query e resgata o id criado.
        if (!$_sql->execute())
            throw new Exception ("erro ao gravar a página: " . $this->idPagina . ": " . $this->tituloPagina);

        return $this->hpDB->getLastInsertId();
    }

    public function atualizar ()
    {
        $_sql = $this->hpDB->prepare("UPDATE hp_paginas SET tituloPagina = ?, tituloTabela = ?, classPagina = ?,
                                                            displayFortune = ?, displayImagemTitulo = ?, displaySelectColor = ?
                                      WHERE idPagina = ?");
        $_sql->bind_param("sssiiii", $this->tituloPagina, $this->tituloTabela, $this->classPagina,
            $this->displayFortune, $this->displayImagemTitulo, $this->displaySelectColor, $this->idPagina);

        // executa o query e retorna o número de linhas afetadas (uma, se tudo der certo)
        if (!$_sql->execute())
            throw new Exception ("erro ao gravar a página: " . $this->idPagina . ": " . $this->tituloPagina);

        return $this->hpDB->getAffectedRows();
    }

    public function excluir()
    {
        $result = true;

        $this->lerElementos();

        $result = $result and $this->hpDB->begin();

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
                'classPagina' => $this->classPagina,
                'displayImagemTitulo' => $this->displayImagemTitulo,
                'displaySelectColor' => $this->displaySelectColor                );
        }
        else
        {
            throw new Exception('erro em pagina::getArray(). Não inicializado!');
        }
    }

    public function getBigArray( )
    {
        if ( !isset( $this->idPagina ) )
        {
            throw new Exception ( 'Não é possível ler esta página. ' );
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
           throw new Exception("Não consegui ler o BigArray para a página " . $this->hpDB->real_escape_string($this->tituloPagina) . "!");

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

    public function lerElementos()
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
            throw new Exception("Não consegui ler os elementos da página [" . $this->hpDB->real_escape_string($this->tituloPagina) . "]!");

        $_elementos = $_sql->get_result();
        if (!$_elementos)
        {
            return array();
        }

        foreach ($_elementos as $_el)
        {
            $this->elementos[] = categoria::newFromArray($_el);
        }
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
            throw new Exception("Não consegui ler as categorias não incluídas na página " . $this->hpDB->real_escape_string($this->tituloPagina) . "!]");

        $_naoElementos = $_sql->get_result();

        while ($_nel = $_naoElementos->fetch_assoc())
        {
            $_categoria = new categoria($_nel['idCategoria']);
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
            throw new Exception("Não consegui obter a posição da categoria $_idElemento!");

        $_return = $_sql->get_result()->fetch_assoc();

        if (!$_return)
            throw new Exception ("Não há qualquer categoria da página : $this->idPagina :  $this->tituloPagina com chave $_idElemento");

        $_ProxPosPagina = $_return['posPagina']-1;

        if ($_ProxPosPagina > 0) {
            // desloca o elemento anterior para baixo. (se ele não existir não tem problema)
            $_sql = $this->hpDB->prepare("UPDATE hp_categoriasxpaginas set posPagina = posPagina + 1 WHERE idPagina = ? AND posPagina = ?");
            $_sql->bind_param("ii", $this->idPagina, $_ProxPosPagina);
            if (!$_sql->execute())
                throw new Exception("Não consegui deslocar o elemento anterior para baixo");

            // desloca para cima o elemento solicitado.
            $_sql = $this->hpDB->prepare("UPDATE hp_categoriasxpaginas set posPagina = ? WHERE idPagina = ? AND idCategoria = ?");
            $_sql->bind_param("iii", $_ProxPosPagina, $this->idPagina, $_idElemento);

            if (!$_sql->execute())
                throw new Exception("Não consegui deslocar o elemento para cima");
        }

        return $this->hpDB->getAffectedRows();
    }

    public function deslocarElementoParaBaixo($_idElemento)
    {
        // calcula a posição do elemento posterior.
        $_sql = $this->hpDB->prepare("SELECT posPagina FROM hp_categoriasxpaginas WHERE idPagina = ? AND idCategoria = ?");
        $_sql->bind_param("ii", $this->idPagina, $_idElemento);

        if (!$_sql->execute())
            throw new Exception("Não consegui obter a posição da categoria $_idElemento!");

        $_return = $_sql->get_result()->fetch_assoc();

        $_ProxPosPagina = $_return['posPagina']+1;

        // obtém o total de elementos deste grupo.
        $_sql = $this->hpDB->prepare("SELECT Count(1) as numCategorias from hp_categoriasxpaginas WHERE idPagina = ?");
        $_sql->bind_param("i", $this->idPagina);
        if (!$_sql->execute())
            throw new Exception("Não consegui obter o número de categorias da página [" . $this->hpDB->real_escape_string($this->tituloPagina) . "]!");

        $_row = $_sql->get_result()->fetch_assoc();

        if ($_row['numCategorias'] >= $_ProxPosPagina) {
            // desloca o elemento posterior para cima. (se ele não existir não tem problema)
            $_sql = $this->hpDB->prepare("UPDATE hp_categoriasxpaginas set posPagina = posPagina - 1 WHERE idPagina = ? AND posPagina = ?");
            $_sql->bind_param("ii", $this->idPagina, $_ProxPosPagina);
            if (!$_sql->execute())
                throw new Exception("Não consegui deslocar o elemento seguinte para cima");

            // desloca para baixo o elemento solicitado.
            $_sql = $this->hpDB->prepare("UPDATE hp_categoriasxpaginas set posPagina = ? WHERE idPagina = ? AND idCategoria = ?");
            $_sql->bind_param("iii", $_ProxPosPagina, $this->idPagina, $_idElemento);
            if (!$_sql->execute())
                throw new Exception("Não consegui deslocar o elemento para baixo");
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
                throw new Exception("Erro ao obter maior posição na páginas: " . $this->descricaoCategoria);

            $_row = $_sql->get_result()->fetch_assoc();
            $maxPos = $_row['maxPos']+1;

            $_sql = $this->hpDB->prepare("INSERT INTO hp_categoriasxpaginas (idCategoria, idPagina, posPagina) VALUES (?, ?, ?)");
            $_sql->bind_param("iii", $_idElemento, $this->idPagina, $maxPos);
        }

        if (!$_sql->execute())
            throw new Exception('erro ao inserir elemento na página: ' . $this->idPagina . ' : ' . $_idElemento);

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
        $_paginas = $global_hpDB->query($_sql);
        if (!$_paginas)
        {
            throw new Exception('erro: não consegui ler nenhuma página!');
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
        $_count = $global_hpDB->query($_sql);
        if (!$_count)
        {
            return 0;
        }
        else
        {
            return $_count[0]['numElementos'];
        }
    }

}#  pagina */
?>

