<?php

Namespace Shiresco\Homepage\Temas;

class VariaveisRoot {
	var $hpDB;
	var $id;
	var $rootvar;
	var $descricao;

	static function obterPorNome($_rootVar = null) {
        global $global_hpDB;

		if ($_rootVar == null) 
			return false;

		$_sql = $global_hpDB->prepare('SELECT * FROM hp_rootvars where rootvar = ?');
		$_sql->bind_param('s', $_rootVar);
		if (!$_sql->execute())
			throw new \Exception("Não encontrei variável de tema com esse nome: {$_rootVar}.");

        try {
		    $line = $_sql->get_result()->fetch_assoc();
			$rootVar['id'] = $line['id'];
            $rootVar['tipo'] = $line['tipo'];
			$rootVar['descricao'] = $line['descricao'];
			$rootVar['rootvar'] = $line['rootvar'];
        } catch (\Exception $e) {
            throw new \Exception('erro no get_result->fetch_assoc'. $e->getMessage());
        }
        return $rootVar;
	}

	static function obterTodas() {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare('SELECT * FROM hp_rootvars ORDER BY descricao');
        if (!$_sql->execute()) 
            throw new \Exception('Erro no acesso ao banco de dados');

        $rootVars = [];
        $results = $_sql->get_result();

        while ($result = $results->fetch_assoc()) 
            $rootVars[] = array('id' => $result['id'],
                                'rootvar' => $result['rootvar'],
                                'descricao' => $result['descricao'],
                                'tipo' => $result['tipo'],
                          );
        return $rootVars;
    }

    static function obterTodasDeTipo(string $_tipo) {
        global $global_hpDB;

        $_sql = $global_hpDB->prepare('SELECT * FROM hp_rootvars WHERE tipo = ? ORDER BY descricao');
        $_sql->bind_param('s', $_tipo);
        if (!$_sql->execute())
            throw new \Exception('Erro no acesso ao banco de dados');

        $rootVars = [];
        $results = $_sql->get_result();

        while ($result = $results->fetch_assoc()) 
            $rootVars[] = array('id' => $result['id'],
                                'rootvar' => $result['rootvar'],
                                'descricao' => $result['descricao'],
                                'tipo' => $result['tipo'],
                          );
        return $rootVars;
    }

}
