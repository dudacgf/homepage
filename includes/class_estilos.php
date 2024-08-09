<?php

class elementoColorido
{
	var $idElementoColorido;
	var $descricaoElementoColorido;
	var $atributoCorElemento;
	var $criterioBuscaElemento;
	var $termoBuscaElemento;
	var $cookieElemento;
	var $hpDB;

    public function __construct($_idElementoColorido = -1) 
		{
			global $global_hpDB;
			$this->hpDB = $global_hpDB;

			if ($_idElementoColorido != -1) 
			{
				$_sql = "select * from hp_elementoscoloridos where idElementoColorido = '". $_idElementoColorido ."'";
				$elemento = $this->hpDB->query($_sql);
			if (!$elemento)
			{
				return false;
			}
			else
			{
					$this->idElementoColorido = $elemento[0]['idElementoColorido'];
					$this->descricaoElemento = $elemento[0]['descricaoElemento'];
					$this->atributoCorElemento = $elemento[0]['atributoCorElemento'];
					$this->criterioBuscaElemento = $elemento[0]['criterioBuscaElemento'];
					$this->termoBuscaElemento = $elemento[0]['termoBuscaElemento'];
					$this->cookieElemento = $elemento[0]['cookieElemento'];
			}
		}
		return;
	}

	static function getElementoColorido($nomeCookie = '') 
	{
		global $global_hpDB;

		$_sql = "select * from hp_elementoscoloridos where cookieElemento = '". $nomeCookie ."'";
		$elemento = $global_hpDB->query($_sql);
		if (!$elemento)
		{
			return false;
		}
		else
		{
			$elementoColorido = array (
				'idElementoColorido' => $elemento[0]['idElementoColorido'],
				'descricaoElemento' => $elemento[0]['descricaoElemento'],
				'atributoCorElemento' => $elemento[0]['atributoCorElemento'],
				'criterioBuscaElemento' => $elemento[0]['criterioBuscaElemento'],
				'termoBuscaElemento' => $elemento[0]['termoBuscaElemento'],
				'cookieElemento' => $elemento[0]['cookieElemento']
			);
		}

		return isset($elementoColorido) ? $elementoColorido : FALSE;

	}

	static function getArray()
	{
		global $global_hpDB;

		$_sql = "select * from hp_elementoscoloridos order by descricaoElemento";
		$elementos = $global_hpDB->query($_sql);
		if (!$elementos)
		{
			die('não consegui ler a tabela de elementos coloridos!');
		}
		else
		{
			foreach ($elementos as $elemento)
			{
				$elementosColoridos[] = array (
						'idElementoColorido' => $elemento['idElementoColorido'],
						'descricaoElemento' => $elemento['descricaoElemento'],
						'atributoCorElemento' => $elemento['atributoCorElemento'],
						'criterioBuscaElemento' => $elemento['criterioBuscaElemento'],
						'termoBuscaElemento' => $elemento['termoBuscaElemento'],
						'cookieElemento' => $elemento['cookieElemento']
				);
			}
		}

		return isset($elementosColoridos) ? $elementosColoridos : FALSE;

	}

}

class RGBColor
{
	var $r;
	var $g;
	var $b;
	var $ok = false;

	public function __construct($nomeCor = '') 
	{

		if ($nomeCor[0] === '#') { // é uma cor no formato #RRGGBB - trunca no tamanho correto.
			$valorCor = substr($nomeCor, 0, 7);
		}
		else
		{
			global $global_hpDB;

			$_sql = "select * from hp_parescores where nomeCor = '". strtolower($nomeCor) ."'";
			$parCor = $global_hpDB->query($_sql);
			if (!$parCor)
			{
				die('não consegui ler a tabela de pares de cores!');
			}
			$valorCor = $parCor[0]['valorCor'];
		}

		$this->r = hexdec(substr($valorCor, 1, 2));
		$this->g = hexdec(substr($valorCor, 3, 2));
		$this->b = hexdec(substr($valorCor, 5, 2));
		$this->ok = true;

	}

	static function getIdPar($valorCor = '')
	{
		if ($valorCor == '' || $valorCor[0] !== '#')
		{
			return false;
		}
		else
		{
			global $global_hpDB;

			$_sql = "select idPar from hp_parescores where valorCor = '$valorCor'";
			$result = $global_hpDB->query($_sql);
			if (!$result) 
			{
				return false;
			}
			else
			{
				return $result[0]['idPar'];
			}
		}
	}

	static function getArray()
	{
		global $global_hpDB;

		$_sql = "select * from hp_parescores";
		$pares = $global_hpDB->query($_sql);
		if (!$pares)
		{
			die('não consegui ler a tabela de pares de cores!');
		}
		else
		{
			foreach ($pares as $parCor)
			{
				$paresCores[] = array (
						'idPar' => $parCor['idPar'],
						'nomeCor' => $parCor['nomeCor'],
						'valorCor' => $parCor['valorCor']
				);
			}
		}

		return isset($paresCores) ? $paresCores : FALSE;

	}

}

class cookedStyle 
{
	var $hpDB;
	var $idPagina;
	var $idElementoColorido;
	var $idPar;

	public function __construct() {
		global $global_hpDB;
		$this->hpDB = $global_hpDB;
	}

	function inserirCookedStyle($idPagina, $root_var, $color) {
		global $global_hpDB;

        $_sql = $global_hpDB->prepare("insert into hp_cookedstyles values (?, ?, ?)");
        $_sql->bind_param("iss", $idPagina, $root_var, $color);

        try {
            if (!$_sql->execute()) 
                throw new Exception("Não consegui gravar o cookedStyle");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao gravar o cookedStyle: " . $e->getMessage());
        }
	}
	function atualizarCookedStyle($idPagina, $root_var, $color) {
		global $global_hpDB;

        $_sql = $global_hpDB->prepare("update hp_cookedstyles set color = ? where idPagina = ? and root_var = ?");
        $_sql->bind_param("sis", $color, $idPagina, $root_var);

        try {
            if (!$_sql->execute()) 
                throw new Exception("Não consegui atualizar o cookedStyle");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar o cookedStyle: " . $e->getMessage());
        }
	}

	function eliminarCookedStyle($idPagina, $root_var) {
		global $global_hpDB;

        $_sql = $global_hpDB->prepare("delete from hp_cookedstyles where idPagina = ? and root_var = ?");
        $_sql->bind_param("is", $idPagina, $root_var);
        try {
            if (!$_sql->execute())
                throw new Exception("Não consegui remover o cookedStyle");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao remove o cookedStyle: " . $e_>getMessage());
        }
	}

	function restaurarPagina($idPagina) {
		global $global_hpDB;

		$_sql = $global_hpDB->prepare("delete from hp_cookedstyles where idPagina = ?");
        $_sql->bind_param("i", $idPagina);
        try {
            if (!$_sql->execute())
                throw new Exception("Não consegui remover os cookedStyles");
            else
                return true;
        } catch (Exception $e) {
            throw new Exception("Erro ao remove os cookedStyles: " . $e_>getMessage());
        }
		return true;
	}

	// verifica se há cookies e, neste caso, cria os estilos adicionais.
	public static function getArray($_idPagina = 1)
	{

		global $global_hpDB;
        $_sql = $global_hpDB->prepare('SELECT root_var, color
                                       FROM hp_cookedstyles 
                                       WHERE idPagina = ?');
        $_sql->bind_param('i', $_idPagina);
        if (!$_sql->execute()) 
           throw new Exception("Não consegui ler cookies da página $_idPagina");

        try {
            $cookies = $_sql->get_result();
            while ($cookie = $cookies->fetch_assoc()) {
                if ($cookie['root_var'] != 'picColor')
                    $colorCookies[] = array('root_var' => '--theme-' . $cookie['root_var'], 'color' => $cookie['color']);
                else {
                    $rgb = new RGBColor($cookie['color']);
                    $drawUrl = "url('../drawing/background.php?r=" . $rgb->r . "&g=" . $rgb->g . "&b=" . $rgb->b . "')";
                    $colorCookies[] = array('root_var' => '--theme-' . $cookie['root_var'], 'color' => $drawUrl);
                }
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao obter cores dinâmicas para essa página: $e->getMessage()");
        }
		return isset($colorCookies) ? $colorCookies : false ;
	}
}
?>
