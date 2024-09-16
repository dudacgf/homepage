<?php

Namespace Shiresco\Homepage\Fortunes;

DEFINE('OKCREATE', 0);
DEFINE('FAILSIZE', 1);
DEFINE('FAILEXISTS', 2);
DEFINE('FAILNULL', 3);
DEFINE('MAX_TEXTO', 512);
DEFINE('MAX_AUTOR', 127 );

class Fortune {
    var $idFortune;
    var $autorFortune;
    var $textoFortune;
    var $hashQuote;

    static function obterFortune($_maxLen = null) {
        global $global_hpDB;

        if ($_maxLen != NULL) {
            $_sql = $global_hpDB->prepare("SELECT *  FROM hp_fortunes WHERE LENGTH(textoFortune) < ? ORDER BY RAND() LIMIT 1");
            $_sql->bind_param('i', $_maxLen);
        } else
            $_sql = $global_hpDB->prepare("SELECT *  FROM hp_fortunes ORDER BY RAND() LIMIT 1");

        if (!$_sql->execute())
            return array('autorFortune' => 'o administrador',
                         'textoFortune' => 'sem fortunes. erro na leitura do banco de dados');
        else {
            $result = $_sql->get_result()->fetch_assoc();
            return array('textoFortune' => $result['textoFortune'],
                         'autorFortune' => $result['autorFortune']);
        }
    }

    function existe() {
        global $global_hpDB;

        $this->hashQuote = md5($this->textoQuote . $this->autorQuote);

        $_sql = $global_hpDB->prepare("select idFortune from hp_fortunes where hashfortune = ?");
        $_sql->bind_param('s', $this->hashQuote);
        if (!$_sql->execute())
            throw new Exception("MySQL Error: $_sql->error");

        return $_sql->get_result()->num_rows;
    }

    function inserir() {
        global $global_hpDB;

        // verifica se o texto não veio vazio
        if (strlen($this->textoQuote) == 0)
            return FAILNULL;

        // se forem muito grandes, recusa
        if (strlen($this->textoQuote) > MAX_TEXTO || strlen($this->autorQuote) > MAX_AUTOR) 
            return FAILSIZE;

        // verifica se já existe fortune com esse mesmo texto
        if ($this->existe()) {
            return FAILEXISTS;
        }

        // insere o novo valor na tabela.
        $_sql = $global_hpDB->prepare("insert into hp_fortunes (hashFortune, autorFortune, textoFortune) values (?, ?, ?)");
        $_sql->bind_param('sss', $this->hashQuote, $this->autorQuote, $this->textoQuote);
        if (!$_sql->execute())
            throw new Exception("erro ao gravar o fortune: " . $this->textoFortune);
        else {
            $this->idFortune = $global_hpDB->getLastInsertId();
            return OKCREATE;
        }
    }
}
?>

