<?php

DEFINE('OKCREATE', 0);
DEFINE('FAILSIZE', 1);
DEFINE('FAILEXISTS', 2);

class Fortune {
	var $fortune = '';

	public function __construct($_maxLen = NULL) {
		global $global_hpDB;

		if ($_maxLen != NULL) {
			$_sql = "SELECT *  FROM hp_fortunes WHERE LENGTH(textoFortune) < $_maxLen ORDER BY RAND() LIMIT 1";
		}
		else
		{
			$_sql = "SELECT MAX(idFortune) FROM hp_fortunes WHERE 1=1";
			$result = $global_hpDB->query($_sql);
			if ($result) {
				$max_id = $result[0][0];
				$rand_id = rand(1, $max_id);
				$_sql = "SELECT *  FROM hp_fortunes WHERE idFortune >= $rand_id LIMIT 1";
				$result = $global_hpDB->query($_sql);
			}
		}
		$result = $global_hpDB->query($_sql);
		if (!$result) {
			$fortune = "sem fortunes. erro na leitura do banco de dados<br><b>-- o administrador</b>";
		}
		else {
			$fortune = $result[0]['textoFortune'];
			if (trim($result[0]['autorFortune']) != '') 
				$fortune .= "<br /><b>--" . $result[0]['autorFortune'];
		}
		$this->fortune = $fortune;
	}

	/* 
	   writes a quote in the homepage database
    */
	function adicionaFortune($aQuote = '') 
	{
		global $global_hpDB;

		$posSeparador = strpos($aQuote, '--');
		if ($posSeparador !== FALSE) {
			$textoQuote = substr($aQuote, 0, $posSeparador-1);
			$autorQuote = substr($aQuote, $posSeparador+2);
		}
		else
		{
			$textoQuote = $aQuote;
			$autorQuote = '';
		}
		$textoQuote = trim(preg_replace(array('/\r\n/', '/\n\r/', '/\n/',), '', $textoQuote));
		$autorQuote = trim(preg_replace(array('/\r\n/', '/\n\r/', '/\n/',), '', $autorQuote));
		// se forem muito grandes, recusa
		if (strlen($textoQuote) > 512 || strlen($autorQuote) > 127) 
			return FAILSIZE;
		$hashQuote = md5($textoQuote . $autorQuote);
		// verifica se já existe um fortune com este mesmo hash
		$_sql = "select idFortune from hp_fortunes where hashfortune = '" . $global_hpDB->real_escape_string($hashQuote) . "'";
		$result = $global_hpDB->query($_sql);
		if ($result) {
			return FAILEXISTS;
		}

		// insere o novo valor na tabela.
		$_sql = "insert into hp_fortunes (hashFortune, autorFortune, textoFortune) values 
				('" . $global_hpDB->real_escape_string($hashQuote) . "', '" . $global_hpDB->real_escape_string($autorQuote) . "', '" . 
				 	  $global_hpDB->real_escape_string($textoQuote) . "')";
		$result = $global_hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar o fortune: " . $this->descricaofortune);
		}
		else
		{
			$this->idFortune = $global_hpDB->getLastInsertId();
			return OKCREATE;
		}
	}

	function criarFortunes($file, $repeat = FALSE) {
		global $hp_DB;

		echo $file ;
		$fd = @fopen($file, "r");
		if ($fd == false) {
			die("File error: $file");
		}

		$totalQuotes = 0; $totalCreated = 0; $totalFailSize = 0; $totalFailExists = 0;
		$aQuote = '';
		while (!feof($fd)) {
			$line = fgets($fd);
			if ($line[0] == "%") {
				if ($totalQuotes++ % 150 == 0) 
					echo "<br />"; 
				switch ($this->adicionaFortune($aQuote)) {
					case OKCREATE:
						echo "."; 
						$totalCreated++;
						break;
					case FAILSIZE:
						echo "?";
						$totalFailSize++;
						break;
					case FAILEXISTS;
						echo "!";
						$totalFailExists++;
						break;
				}
				flush();
				$aQuote = '';
			} else {
				$aQuote .= $line;
			}
		}

		fclose($fd);

		if ($repeat) {
			echo "<br />" . str_repeat(".", 30) . " $totalQuotes fortunes indexados!<br />"; flush();
			echo str_repeat(".", 30) . " $totalCreated fortunes inseridos na base de dados<br />"; flush();
			echo str_repeat(".", 30) . " $totalFailSize fortunes recusados por ter tamanho muito grande<br />"; flush();
			echo str_repeat(".", 30) . " $totalFailExists fortunes recusados por já existirem na base<br /><hr />"; flush();
		}

		return array('totalQuotes' => $totalQuotes, 
				'totalCreated' => $totalCreated,
				'totalFailSize' => $totalFailSize,
				'totalFailExists' => $totalFailExists);
	}

	public static function getCount()
	{
		global $global_hpDB;

		$_sql = "select count(*) as nFortunes from hp_fortunes where 1 = 1";
		$result = $global_hpDB->query($_sql);
		if (!$result)
			return false;
		else 
			return $result[0]['nFortunes'];
	}

} // End of class

//-- vim: set shiftwidth=4 tabstop=4 showmatch nowrap: 

?>

