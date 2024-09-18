<?php

Namespace Shiresco\Homepage\Temas;

class Temas
{
    var $hpDB;
    var $id;
    var $nome;
    var $comentario;

    public function __construct($_id = null) {
        global $global_hpDB;
        $this->hpDB = $global_hpDB;

        if ($_id == null)
            return;

        $_sql = $this->hpDB->prepare('select * from hp_temas where id = ?');
        $_sql->bind_param('i', $_id);
        if (!$_sql->execute())
            return;

        $_result = $_sql->get_result()->fetch_assoc();
        $this->id = $_result['id'];
        $this->nome = $_result['nome'];
        $this->comentario = $_result['comentario'];

        return;
    }
    
    public static function obterPorNome($_nome) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("select * from hp_temas where nome = ?");
        $_sql->bind_param('s', $_nome);
        if (!$_sql->execute())
            throw new Exception('Erro ao obter tema por nome: ' . $_sql->getMessage());

        $instance = new self();
        $result = $_sql->get_result()->fetch_assoc();
        $instance->id = $result['id'];
        $instance->nome = $result['nome'];
        $instance->comentario = $result['comentario'];

        return $instance;
    }

    public function inserir() {
        $_sql = $this->hpDB->prepare('insert into hp_temas (nome, comentario) values (?, ?)');
        $_sql->bind_param('ss', $this->nome, $this->comentario);
        if (!$_sql->execute())
            throw new Exception('erro ao inserir tema: ' . $this->hpDB->real_escape_string($_sql->error));
        $this->id = $this->hpDB->getLastInsertId();

        return $this->id;
    }

    public function atualizar() {
        $_sql = $this->hpDB->prepare('update hp_temas set comentario = ? where nome = ?');
        $_sql->bind_param('ss', $this->comentario, $this->nome);
        if (!$_sql->execute())
            throw new Exception('erro ao atualizar tema: ' . $this->hpDB->real_escape_string($_sql->error));

        return true;
    }


    public function excluir() {
        $_sql = $this->hpDB->prepare('delete from hp_temas where id = ?');
        $_sql->bind_param('i', $this->id);
        if (!$_sql->execute())
            throw new Exception('erro ao excluir tema.');

        return $this->hpDB->getAffectedRows();
    }

    static function getArray()
    {
        global $global_hpDB;

        $_sql = "select * from hp_temas";
        $temas = $global_hpDB->query($_sql);
        if (!$temas)
        {
            throw new Exception('não consegui ler a tabela de temas!');
        }
        else
        {
            foreach ($temas as $tema)
            {
                $estilos[] = array(
                        'id' => $tema['id'],
                        'nome' => $tema['nome'],
                        'comentario' => $tema['comentario']
                        );
            }
        }

        return isset($estilos) ? $estilos : FALSE;

    }

    static function ObterNomes()
    {
        global $global_hpDB;

        $_sql = "select nome from hp_temas";
        $temas = $global_hpDB->query($_sql);
        if (!$temas)
        {
            throw new Exception('não consegui ler a tabela de temas!');
        }
        else
        {
            foreach ($temas as $tema)
            {
                $nomes[] = $tema['nome'];
            }
        }

        return isset($nomes) ? $nomes : FALSE;

    }

    static function temaExiste($_nome = null) {
        if (!$_nome)
            throw new Exception('Não posso verificar se um tema existe se não me passarem seu nome');

        global $global_hpDB;

        $_sql = $global_hpDB->prepare('SELECT * FROM hp_temas WHERE nome = ?');
        $_sql->bind_param('s', $_nome);

        try {
            if (!$_sql->execute())
                throw new Exception('Erro na consulta ao banco de dados');
        } catch (Exception $e) {
            throw new Exception('Erro de banco de dados: ' . $e->getMessage());
        }

        return $_sql->get_result()->num_rows;
    }

}


