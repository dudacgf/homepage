<?php

Namespace Shiresco\Homepage\Temas;


/**
 * TemaRootVars 
 *
 * representam mudanças não permanentes nas cores de um tema
 *
 */
class TemaRootVars {
    var $hpDB;
    var $idTema;
    var $rootvar;
    var $cor;

    public function __construct() {
        global $global_hpDB;
        $this->hpDB = $global_hpDB;
    }

    function inserir($idTema, $rootvar, $cor) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("insert into hp_temasxrootvars values (?, ?, ?)");
        $_sql->bind_param("iss", $idTema, $rootvar, $cor);

        try {
            if (!$_sql->execute()) 
                throw new Exception("Não consegui gravar a alteração no tema");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao gravar a alteração no tema: " . $e->getMessage());
        }
    }
    function atualizar($idTema, $rootvar, $cor) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("update hp_temasxrootvars set cor = ? where idTema = ? and rootvar = ?");
        $_sql->bind_param("sis", $cor, $idTema, $rootvar);

        try {
            if (!$_sql->execute()) 
                throw new Exception("Não consegui atualizar a alteração no tema");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar a alteração no tema: " . $e->getMessage());
        }
    }

    function eliminar($idTema, $rootvar) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("delete from hp_temasxrootvars where idTema = ? and rootvar = ?");
        $_sql->bind_param("is", $idTema, $rootvar);
        try {
            if (!$_sql->execute())
                throw new Exception("Não consegui remover a alteração no tema");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao remove a alteração no tema: " . $e_>getMessage());
        }
    }

    function restaurarPagina($idTema) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("delete from hp_temasxrootvars where idTema = ?");
        $_sql->bind_param("i", $idTema);
        try {
            if (!$_sql->execute())
                throw new Exception("Não consegui remover as alterações deste tema");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao remover as alterações desde tema: " . $e_>getMessage());
        }
        return true;
    }

    public static function getArray($_idTema = 1)
    {

        global $global_hpDB;
        $_sql = $global_hpDB->prepare('SELECT rootvar, cor
                                       FROM hp_temasxrootvars 
                                       WHERE idTema = ?');
        $_sql->bind_param('i', $_idTema);
        if (!$_sql->execute()) 
           throw new Exception("Não consegui ler as alterações do tema $_idTema");

        try {
            $rvs = $_sql->get_result();
            while ($rv = $rvs->fetch_assoc()) {
                $temaRootVars[] = array('rootvar' => '--cor-' . $rv['rootvar'], 'cor' => $rv['cor']);
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao obter alterações para esse tema: $e->getMessage()");
        }
        return isset($temaRootVars) ? $temaRootVars : false ;
    }
}
?>
