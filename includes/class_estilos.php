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

	function inserirCookedStyle($idPagina, $idElementoColorido, $idPar) {
		global $global_hpDB;

		$_sql = "insert into hp_cookedstyles values ($idPagina, $idElementoColorido, $idPar)
					on duplicate key update idPar = $idPar";
		$result = $global_hpDB->query($_sql);
		if (!$result) {
			return false;
		} else {
			$this->idPagina = $idPagina;
			$this->idElementoColorido = $idElementoColorido;
			$this->idPar = $this->idPar;
			return true;
		}
	}

	function eliminarCookedStyle($idPagina, $idElementoColorido) {
		global $global_hpDB;

		$_sql = "delete from hp_cookedstyles where idPagina = $idPagina and idElementoColorido = $idElementoColorido";
		$result = $global_hpDB->query($_sql);
		if (!$result) {
			return false;
		} else {
			unset($this->idPagina);
			unset($this->idElementoColorido);
			unset($this->idPar);
			return true;
		}
	}

	function restaurarPagina($idPagina) {
		global $global_hpDB;

		$_sql = "delete from hp_cookedstyles where idPagina = $idPagina";
		$result = $global_hpDB->query($_sql);
		if (!$result) {
			return false;
		} else {
			unset($this->idPagina);
			unset($this->idElementoColorido);
			unset($this->idPar);
			return true;
		}
	}

	// verifica se há cookies e, neste caso, cria os estilos adicionais.
	public static function getArray($_idPagina = 1)
	{

		global $global_hpDB;
		
		// caceta! olha o tamanho deste query! acho que normalizei demais! ;D
		$_sql = "SELECT ec.idElementoColorido, p.classPagina, p.idPagina, cs.descricaoSelector as termoBuscaElemento, ec.criterioBuscaElemento, ca.termoBuscaAtributo as atributoCorElemento, pc.valorCor
				   FROM hp_elementoscoloridos ec, hp_cookedatributos cka, hp_cookedselectors cks,
				 		hp_cssselectors cs, hp_cssatributos ca, hp_paginas p, hp_parescores pc, hp_cookedstyles cky
				  WHERE ec.idElementoColorido = cks.idElementoColorido
				 	AND cks.idSelector = cs.idSelector
					AND cks.idCooked = cka.idCooked
					AND cka.idAtributo = ca.idAtributo
					AND ec.idElementoColorido = cky.idElementoColorido
					AND cky.idPar = pc.idPar
					AND cky.idPagina = p.idPagina
					AND p.idPagina = $_idPagina
				  ORDER BY cs.idSelector";
		$_cookies = $global_hpDB->query($_sql);
		if (!isset($_cookies))
		{
			die('não consegui ler o array de cookies coloridos!');
		}
		else
		{
			if ($_cookies) {
				foreach ($_cookies as $elementoColorido) {
					$classPagina = $elementoColorido['classPagina'];
					$color = $elementoColorido['valorCor'];
					$selectors = explode(';', $elementoColorido['termoBuscaElemento']);
					foreach ($selectors as $selector) {
						if (!isset($colorCookies[$selector])) {
							$colorCookies[$selector] = Array();
							if ($elementoColorido['criterioBuscaElemento'] == 'id') 
							{
								$colorCookies[$selector][] = "#$selector {\n";
							}
							else
							{
								$colorCookies[$selector][] = "body.$classPagina $selector {\n ";
							}
						}
						$atributes = explode(';', $elementoColorido['atributoCorElemento']);
						foreach($atributes as $atribute) 
						{
							switch ($atribute)
							{
							  case 'color':
								$colorCookies[$selector][] = "color: $color;\n";
								break;
							  case 'bcolor':
								$colorCookies[$selector][] = "background-color: $color;\n";
								break;
							  case 'tborder':
								$colorCookies[$selector][] = "border-top-color: $color;\n";
								break;
							  case 'lborder':
								$colorCookies[$selector][] = "border-left-color: $color;\n";
								break;
							  case 'bborder':
								$colorCookies[$selector][] = "border-bottom-color: $color;\n";
								break;
							  case 'rborder':
								$colorCookies[$selector][] = "border-right-color: $color;\n";
								break;
							  case 'bimg':
								$rgbcolor = new RGBColor($color);
								if ($rgbcolor->ok) { // 'ok' is true when the parsing was a success
									$colorCookies[$selector][] = "background-image: url(\"" . INCLUDE_PATH . "/drawing/background.php?r=" . 
										$rgbcolor->r . "&g=" . $rgbcolor->g . "&b=" . $rgbcolor->b . "\");\n" ; 
								}
								break;
							  case 'fsize': // este foi feito para a cacau
								$colorCookies[$selector][] = "font-size: 1.5em;\n";
								break;
							}
						}
					}
				}
			}
		}
		return isset($colorCookies) ? $colorCookies : false ;
	}

}

//-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: 
	
?>
