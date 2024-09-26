<?php
/**
 * TemaCSS
 *
 * representa os atributos de um tema (Cores de elementos da página e no futuro, fontSize e fontFamily)
 *
 * Há dois tipos de atributos de um tema: estáveis e em edição. Esta classe oferece métodos para 
 * edição dos atributos temporários (inclusão/atualização/exclusão) e para salvar os atributos temporários
 * na lista de estáveis do tema.
 *
 */

Namespace Shiresco\Homepage\Temas;


class TemaCSS {
    var $hpDB;

    public static function inserirTemp($idTema, $rootvar, $valor) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("insert into hp_temacss (idTema, rootvar, uso, valor) values (?, ?, 'temp', ?) on duplicate key update valor = ?");
        $_sql->bind_param("isss", $idTema, $rootvar, $valor, $valor);

        try {
            if (!$_sql->execute()) 
                throw new Exception("Não consegui gravar a alteração temporária no tema");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao gravar a alteração temporária no tema: " . $e->getMessage());
        }
    }

    public static function atualizarTemp($idTema, $rootvar, $valor) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("update hp_temacss set valor = ? where idTema = ? and rootvar = ? and uso = 'temp'");
        $_sql->bind_param("sis", $valor, $idTema, $rootvar);

        try {
            if (!$_sql->execute()) 
                throw new Exception("Não consegui atualizar a alteração temporária no tema");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar a alteração temporária no tema: " . $e->getMessage());
        }
    }

    public static function eliminarTemp($idTema, $rootvar) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("delete from hp_temacss where idTema = ? and rootvar = ? and uso = 'temp'");
        $_sql->bind_param("is", $idTema, $rootvar);
        try {
            if (!$_sql->execute())
                throw new Exception("Não consegui remover a alteração temporária no tema");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao remover a alteração temporária no tema: " . $e_>getMessage());
        }
    }

    public static function restaurarPagina($idTema) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("delete from hp_temacss where idTema = ? and uso = 'temp'");
        $_sql->bind_param("i", $idTema);
        try {
            if (!$_sql->execute())
                throw new Exception("Não consegui remover as alterações temporárias deste tema");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao remover as alterações temporárias deste tema: " . $e_>getMessage());
        }
        return true;
    }

    public static function obterTempCSS($_idTema = 1)
    {

        // obtém o tema estável
        global $global_hpDB;
        $_sql = $global_hpDB->prepare("SELECT r.tipo as tipo, t.rootvar, t.valor
                                       FROM hp_temacss t, hp_rootvars r
                                       WHERE r.rootvar = t.rootvar 
                                         AND t.idTema = ? and t.uso = 'main'");
        $_sql->bind_param('i', $_idTema);
        try {
            if (!$_sql->execute()) 
               throw new Exception("Não consegui ler as alterações do tema $_idTema");

            $rvs = $_sql->get_result();
            while ($rv = $rvs->fetch_assoc()) {
                $temaMain[] = array($rv['rootvar'], $rv['valor'], $rv['tipo']);
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao obter o tema: $e->getMessage()");
        }

        // obtém os atributos alterados
        global $global_hpDB;
        $_sql = $global_hpDB->prepare("SELECT r.tipo as tipo, t.rootvar, t.valor
                                       FROM hp_temacss t, hp_rootvars r
                                       WHERE r.rootvar = t.rootvar 
                                         AND t.idTema = ? and t.uso = 'temp'");
        $_sql->bind_param('i', $_idTema);
        try {
            if (!$_sql->execute()) 
               throw new Exception("Não consegui ler as alterações do tema $_idTema");

            $rvs = $_sql->get_result();
            while ($rv = $rvs->fetch_assoc()) {
                $temaTemp[] = array($rv['rootvar'], $rv['valor'], $rv['tipo']);
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao obter o tema: $e->getMessage()");
        }

        // combina os arrays
        if (isset($temaTemp))
            $tema = array_merge($temaMain, $temaTemp);
        else
            $tema = $temaMain;

        // prepara o resultado
        foreach ($tema as $t)
            $temaCSS[] = array('rootvar' => $t[0], 'valor' => $t[1], 'tipo' => $t[2]);

        return isset($temaCSS) ? $temaCSS : false ;
    }

    public static function obterCSS($_idTema = 1)
    {
        // obtém o tema estável
        global $global_hpDB;
        $_sql = $global_hpDB->prepare("SELECT r.tipo as tipo, t.rootvar, t.valor
                                       FROM hp_temacss t, hp_rootvars r
                                       WHERE r.rootvar = t.rootvar 
                                         AND t.idTema = ? and t.uso = 'main'");
        $_sql->bind_param('i', $_idTema);
        try {
            if (!$_sql->execute()) 
               throw new Exception("Não consegui ler o tema $_idTema");

            $rvs = $_sql->get_result();
            while ($rv = $rvs->fetch_assoc()) {
                $temaCSS[] = array('rootvar' => $rv['rootvar'], 'valor' => $rv['valor'], 'tipo' => $rv['tipo']);
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao obter o tema: $e->getMessage()");
        }

        return isset($temaCSS) ? $temaCSS : false ;
    }

    public static function obterValor($_idTema = 1, $rootvar) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("select valor from hp_temacss where idTema = ? and rootvar = ? and uso = 'main'");
        $_sql->bind_param("is", $_idTema, $rootvar);
        if (!$_sql->execute())
            throw new Exception('Erro ao ler valor de tema');
        $result = $_sql->get_result();

        if ($result->num_rows > 0)
            return $result->fetch_assoc()['valor'];
        else 
            return FALSE;
    }

    public static function obterTempValor($_idTema = 1, $rootvar) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("select valor from hp_temacss where idTema = ? and rootvar = ? and uso = 'temp'");
        $_sql->bind_param("is", $_idTema, $rootvar);
        if (!$_sql->execute())
            throw new Exception('Erro ao ler valor de tema');
        $result = $_sql->get_result();
        if ($result->num_rows > 0) 
            return $result->fetch_assoc()['valor'];

        $_sql = $global_hpDB->prepare("select valor from hp_temacss where idTema = ? and rootvar = ? and uso = 'main'");
        $_sql->bind_param("is", $_idTema, $rootvar);
        if (!$_sql->execute())
            throw new Exception('Erro ao ler valor de tema');
        $result = $_sql->get_result();
        if ($result->num_rows > 0) 
            return $result->fetch_assoc()['valor'];
        else
            return FALSE;
    }

    public static function criarDeArray($_idTema = 1, $rootvars) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("insert into hp_temacss (idTema, rootvar, uso, valor) values (?, ?, 'main', ?)");
        
        foreach ($rootvars as $rv) {
            $_sql->bind_param('iss', $_idTema, $rv['rootvar'], $rv['valor']);
            if (!$_sql->execute())
                throw new Exception('Erro ao criar tema a partir de array: ' . $_sql->getMessage());
        }
    }

    public static function salvar($_idTema = 1) {
        global $global_hpDB;

        // executa o update dos atributos 'main'
        $_sql = $global_hpDB->prepare("UPDATE hp_temacss t1 
                                       INNER JOIN hp_temacss t2 on t1.idTema = t2.idTema and t1.rootvar = t2.rootvar 
                                       and t1.uso = 'main' and t2.uso = 'temp' and t1.idTema = ? SET t1.valor = t2.valor;");
        $_sql->bind_param('i', $_idTema);

        if (!$_sql->execute()) 
           throw new Exception("Erro ao atualizar os atributos do tema: " . $global_hpDB->error);

        // se não houve nenhuma atualização, retorna
        if ($global_hpDB->getAffectedRows() == 0) 
            return TRUE; 

        // deleta os atributos temporários
        $_sql = $global_hpDB->prepare("delete from hp_temacss where idTema =  ? and uso = 'temp'");
        $_sql->bind_param('i', $_idTema);
        if (!$_sql->execute()) 
            throw new Exception('Erro ao remover atributos temporarios do tema: ' . $global_hpDB->error);

        return TRUE;
    }

    public static function duplicar($_idTemaFrom, $_idTemaTo) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare("insert into hp_temacss (idTema, rootvar, uso, valor) 
                                       select ?, rootvar, 'main', valor from hp_temacss where idTema = ?");
        $_sql->bind_param("ii", $_idTemaTo, $_idTemaFrom);

        try {
            if (!$_sql->execute())
                throw new Exception('Erro ao duplicar tema: ' . $_sql->getMessage());
        } catch (Exception $e) {
            throw new Exception('Erro ao duplicar tema: ' . $_sql->getMessage());
        }
        return TRUE;
    }

    public static function excluir($_idTema = 1) {
        global $global_hpDB;

        $_sql->prepare("delete hp_temascss where idTema = ?");
        $_sql->bind_param('i', $_idTema);
        if (!$_sql->execute())
            throw new Exception('Erro ao excluir atributos do tema: ' . $_sql->getMessage());

        return $global_hpDB->getAffectedRows();
    }

}
?>
