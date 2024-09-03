<?php

Namespace Shiresco\Homepage\Temas;


/**
 * RootVarsPagina 
 *
 * representam mudanças não permanentes nas cores de um tema para uma determinada página
 *
 */
class RootVarsPagina {
    var $hpDB;
    var $idPagina;
    var $rootvar;
    var $cor;

    public function __construct() {
        global $global_hpDB;
        $this->hpDB = $global_hpDB;
    }

    function inserir($idPagina, $rootvar, $cor) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("insert into hp_rootvarsxpaginas values (?, ?, ?)");
        $_sql->bind_param("iss", $idPagina, $rootvar, $cor);

        try {
            if (!$_sql->execute()) 
                throw new Exception("Não consegui gravar a alteração no tema desta página");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao gravar a alteração no tema desta página: " . $e->getMessage());
        }
    }
    function atualizar($idPagina, $rootvar, $cor) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("update hp_rootvarsxpaginas set cor = ? where idPagina = ? and rootvar = ?");
        $_sql->bind_param("sis", $cor, $idPagina, $rootvar);

        try {
            if (!$_sql->execute()) 
                throw new Exception("Não consegui atualizar a alteração no tema desta página");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar a alteração no tema desta página: " . $e->getMessage());
        }
    }

    function eliminar($idPagina, $rootvar) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("delete from hp_rootvarsxpaginas where idPagina = ? and rootvar = ?");
        $_sql->bind_param("is", $idPagina, $rootvar);
        try {
            if (!$_sql->execute())
                throw new Exception("Não consegui remover a alteração no tema desta página");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao remove a alteração no tema desta página: " . $e_>getMessage());
        }
    }

    function restaurarPagina($idPagina) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("delete from hp_rootvarsxpaginas where idPagina = ?");
        $_sql->bind_param("i", $idPagina);
        try {
            if (!$_sql->execute())
                throw new Exception("Não consegui remover as alterações de tema desta página");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao remover as alterações de tema desta página: " . $e_>getMessage());
        }
        return true;
    }

    public static function getArray($_idPagina = 1)
    {

        global $global_hpDB;
        $_sql = $global_hpDB->prepare('SELECT rootvar, cor
                                       FROM hp_rootvarsxpaginas 
                                       WHERE idPagina = ?');
        $_sql->bind_param('i', $_idPagina);
        if (!$_sql->execute()) 
           throw new Exception("Não consegui ler as alterações de tema da página $_idPagina");

        try {
            $rvs = $_sql->get_result();
            while ($rv = $rvs->fetch_assoc()) {
                $rootvarsPag[] = array('rootvar' => '--theme-' . $rv['rootvar'], 'cor' => $rv['cor']);
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao obter cores dinâmicas para essa página: $e->getMessage()");
        }
        return isset($rootvarsPag) ? $rootvarsPag : false ;
    }
}
?>
