<?php

Namespace Shiresco\Homepage\Temas;

class Fonte {
    var $id;
    var $nome;
    var $descricao;
    var $tipo;
    var $cssfile;

    function __construct(int $_idFonte = Null) {
        if ($_idFonte) {
            global $global_hpDB;

            $_sql = $global_hpDB->prepare("select * from hp_fontes where id = ?");
            $_sql->bind_param("i", $_idFonte);
            if (!$_sql->execute()) 
                throw new Exception("Erro de banco de dados: $_sql->getMessage()");
            $line = $_sql->get_result->fetch_assoc();
            $this->id = $line['id'];
            $this->nome = $line['nome'];
            $this->descricao = $line['descricao'];
            $this->tipo = $line['tipo'];
            $this->cssfile = $line['cssfile'];
        } 
    }

    function existe() {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("Select id from hp_fontes where nome = ?");
        $_sql->bind_param("sss", $this->nome);
        if (!$_sql->execute())
            throw new Exception("Erro de banco de dados: $_sql->getMessage()");
        return $_sql->get_result()->num_rows;
    }

    function inserir() {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("Insert into hp_fontes (nome, descricao, tipo, cssfile) values (?, ?, ?, ?)
                                       on duplicate key update descricao = ?, tipo = ?, cssfile = ?");
        $_sql->bind_param("sssssss", $this->nome, $this->descricao, $this->tipo, $this->cssfile);
        if (!$_sql->execute())
            throw new Exception("Erro de banco de dados: $_sql->getMessage()");

        return $global_hpDB->getLastInsertID();
    }

    function atualizar () {
        return null;
    }

    function excluir() {
        return null;
    }

    function getArray() {
        Try {
            return array('id' => $this->id,
                         'nome' => $this->nome,
                         'descricao' => $this->descricao,
                         'tipo' => $this->tipo,
                         'cssfile' => $this->cssfile)
        } catch {
            return false;
        }
    }
}
