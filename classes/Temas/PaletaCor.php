<?php

Namespace Shiresco\Homepage\Temas;

class PaletaCor {
    var $id;
    var $nome;
    var $cor;
    var $paleta;

    function existe() {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("Select id from hp_paletasdecor where nome = ? and cor = ? and paleta = ?");
        $_sql->bind_param("sss", $this->nome, $this->cor, $this->paleta);
        if (!$_sql->execute())
            throw new Exception("Erro de banco de dados: $_sql->getMessage()");
        return $_sql->get_result()->num_rows;
    }

    function inserir() {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("Insert into hp_paletasdecor (nome, cor, paleta) values (?, ?, ?)");
        $_sql->bind_param("sss", $this->nome, $this->cor, $this->paleta);
        if (!$_sql->execute())
            throw new Exception("Erro de banco de dados: $_sql->getMessage()");

        return $global_hpDB->getLastInsertID();
    }
}
