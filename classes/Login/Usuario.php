<?php

Namespace ShiresCo\Homepage\Login;

class Usuario {
    var $idUsuario;
    var $usuario;
    var $senha;
    var $email;
    private $hpDB;


    public function __construct ($_idUsuario = null) {
        global $global_hpDB;
        $this->hpDB = $global_hpDB;

        if ($_idUsuario) {
            $_sql = $this->hpDB->prepare("SELECT * FROM hp_usuarios where idUsuario = ?");
            $_sql->bind_param("i", $_idUsuario);
            if (!$_sql->execute())
                throw new Exception("Erro ao consultar tabela de usuários");
            
            try {
                $line = $_sql->get_result()->fetch_assoc();

                $this->alimentaCampos($line);

            } catch (Exception $e) {
                throw new Exception('Erro de banco de dados: ' . $this->hpDB->real_escape_string($e->getMessage()));
            }
        }
    }

    static function comNome($_usuario = null) {
        global $global_hpDB;

        if (!$_usuario) 
            throw new Exception('Não posso recuperar usuário pelo nome sem seu nome');

        $_sql = $global_hpDB->prepare("SELECT * FROM hp_usuarios where usuario = ?");
        $_sql->bind_param("s", $_usuario);
        if (!$_sql->execute())
            throw new Exception("Erro ao consultar tabela de usuários");
        
        try {
            $result = $_sql->get_result();
            if ($result->num_rows <= 0) 
                return null;
            else 
                $line = $result->fetch_assoc();

            $_instance = new self();
            $_instance->alimentaCampos($line);

            return $_instance;

        } catch (Exception $e) {
            throw new Exception('Erro de banco de dados: ' . $this->hpDB->real_escape_string($e->getMessage()));
        }
    }

    protected function alimentaCampos($_sql_row) {
        $this->idUsuario = $_sql_row['idUsuario'];
        $this->usuario = $_sql_row['usuario'];
        $this->senha = $_sql_row['senha'];
        $this->email = $_sql_row['email'];
    }
}

?>


