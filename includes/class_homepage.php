<?php

// define os tipos de elementos possíveis
define('ELEMENTO_SIMPLES', 1);
define('ELEMENTO_AGRUPADO', 2);

// define os tipos de elementos simples
define('ELEMENTO_LINK', 1);
define('ELEMENTO_FORM', 2);
define('ELEMENTO_SEPARADOR', 3);
define('ELEMENTO_IMAGEM', 4);
define('ELEMENTO_RSSFEED', 5);
define('ELEMENTO_TEMPLATE', 6);

class tiposElementos 
{ /*{{{*/
	static function getArray()
	{
		global $global_hpDB;

		$_sql = "select * from hp_tiposelementos";
		$tipos = $global_hpDB->query($_sql);
		if (!$tipos)
		{
			die('não consegui ler a tabela de tipos de elementos!');
		}
		else
		{
			foreach ($tipos as $tipoElemento)
			{
				$tiposelementos[$tipoElemento['idTipoElemento']] = $tipoElemento['descricaoTipoElemento'];
			}
		}

		return isset($tiposelementos) ? $tiposelementos : FALSE;

	}

} /*}}} tiposElementos */

class tiposGrupos
{ /*{{{*/
	static function getArray()
	{
		global $global_hpDB;

		$_sql = "select * from hp_tiposgrupos";
		$tipos = $global_hpDB->query($_sql);
		if (!$tipos)
		{
			die('não consegui ler a tabela de tipos de grupos!');
		}
		else
		{
			foreach ($tipos as $tipoGrupo)
			{
				$tiposgrupos[$tipoGrupo['idTipoGrupo']] = $tipoGrupo['descricaoTipoGrupo'];
			}
		}

		return isset($tiposgrupos) ? $tiposgrupos : FALSE;

	}

} /*}}} tiposGrupos*/

class cssEstilos 
{ /*{{{*/
	static function getArray()
	{
		global $global_hpDB;

		$_sql = "select * from hp_cssestilos";
		$cssestilos = $global_hpDB->query($_sql);
		if (!$cssestilos)
		{
			die('não consegui ler a tabela de tipos de grupos!');
		}
		else
		{
			foreach ($cssestilos as $cssestilo)
			{
				$estilos[] = array(
                        'idEstilo' => $cssestilo['idEstilo'],
                        'nomeEstilo' => $cssestilo['nomeEstilo'],
                        'comentarioEstilo' => $cssestilo['comentarioEstilo']
                        );
			}
		}

		return isset($estilos) ? $estilos : FALSE;

	}

	static function getClassNames()
	{
		global $global_hpDB;

		$_sql = "select nomeEstilo from hp_cssestilos";
		$cssestilos = $global_hpDB->query($_sql);
		if (!$cssestilos)
		{
			die('não consegui ler a tabela de tipos de grupos!');
		}
		else
		{
			foreach ($cssestilos as $cssestilo)
			{
				$estilos[] = $cssestilo['nomeEstilo'];
			}
		}

		return isset($estilos) ? $estilos : FALSE;

	}
} /*}}} cssEstilos */

abstract class elemento
{ /*{{{*/
	var $hpDB;
	var $idGrupo;
	var $posGrupo;
	var $comportamentoElemento;
	var $idElemento;
	var $descricaoElemento;
	var $tipoElemento;

	// finalmente resolvido o problema da conexão única com a utilização da variáveil $global_hpDB;
	function elemento()
	{
		global $global_hpDB;
		
		$this->comportamentoElemento = ELEMENTO_SIMPLES;

		$this->hpDB = $global_hpDB;
	}

	abstract function inserir();
	
	abstract function atualizar();
	
	abstract function excluir();

	abstract function getArray();

	#abstract static function newFromArray($_array);
	
	#abstract static function getCount();

} /*}}} elemento*/

abstract class elementoAgrupado extends elemento
{ /*{{{*/
	public $elementos = array();

	function elementoAgrupado()
	{
		parent::elemento();
		$this->comportamentoElemento = ELEMENTO_AGRUPADO;
	}

	abstract function numeroElementos();

	abstract function lerElementos();

	abstract function elementoNaPosicao($_posElemento);

	abstract function elementoDeCodigo($_idElemento);
	
	abstract function lerNaoElementos();
	
	abstract function incluirElemento($_idElemento, $_posElemento = 0);
	
	abstract function excluirElemento($_idElemento);

	abstract function deslocarElementoParaCima($_idElemento);

	abstract function deslocarElementoParaBaixo($_idElemento);

} /*}}} elementoAgrupado */

class wLink extends elemento
{ /*{{{*/
	var $idLink;
	var $linkURL;
	var $descricaoLink;
	var $dicaLink;
	var $localLink;
	var $urlElementoSSL;
	var $targetLink;
	var $urlElementoSVN;
	
	public function __construct($_idLink) // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
	{

		$this->tipoElemento = ELEMENTO_LINK;
		parent::elemento();

		// se não passou um id para este link, é porque ele está sendo criado.
		// se passou, ele já existe e vai ser lido.
		if (isset($_idLink) && $_idLink != NULL) {

			$line = $this->hpDB->query("SELECT * from hp_elementos where idElemento = $_idLink");
			if (!$line) 
			{
				die("idLink incorreto: $_idLink");
			}
			
			$this->idLink = $line[0]['idElemento'];
			$this->idGrupo = $line[0]['idGrupo'];
			$this->posGrupo = $line[0]['posGrupo'];
			$this->linkURL = $line[0]['urlElemento'];
			$this->descricaoLink = $line[0]['descricaoElemento'];
			$this->dicaLink = $line[0]['dicaElemento'];
			$this->localLink = $line[0]['urlElementoLocal'];
			$this->targetLink = $line[0]['urlElementoTarget'];
			$this->urlElementoSSL = $line[0]['urlElementoSSL'];
			$this->urlElementoSVN = $line[0]['urlElementoSVN'];
			$this->idElemento = $this->idLink;
			$this->descricaoElemento = $this->descricaoLink;
		}
		
	}
	
	function setValues($_idGrupo, $_posGrupo, $_linkURL, $_descricaoLink, $_dicaLink, 
			$_urlElementoSSL = 0, $_urlElementoSVN = 0, $_targetLink = 0)
	{
		$this->idGrupo = $_idGrupo;
		$this->posGrupo = $_posGrupo;
		$this->linkURL = $_linkURL;
		$this->descricaoLink = $_descricaoLink;
		$this->dicaLink = $_dicaLink;
		$this->urlElementoSSL = $_urlElementoSSL;
		$this->urlElementoSVN = $_urlElementoSVN;
		$this->targetLink = $_targetLink;
	}

	function inserir ()
	{

			$_sql = "INSERT INTO hp_elementos (idTipoElemento, idGrupo, posGrupo, 
						urlELemento, descricaoElemento, dicaElemento, 
						urlElementoLocal, urlElementoSSL, urlElementoSVN, urlElementoTarget)
					 VALUES (" . ELEMENTO_LINK . ", $this->idGrupo, $this->posGrupo , '" .
					 $this->hpDB->real_escape_string ($this->linkURL). "', '" .
					 $this->hpDB->real_escape_string ($this->descricaoLink) . "', '" .
					 $this->hpDB->real_escape_string ($this->dicaLink) . "', $this->localLink, $this->urlElementoSSL, " .
					 "$this->urlElementoSVN, '" . $this->hpDB->real_escape_string ($this->targetLink) . "')";
		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar o link: " . $this->idLink . ": " . $this->descricaoLink);
		}
		else
		{
			$this->idLink = $this->hpDB->getLastInsertId();
			return $this->idLink;
		}
	}
	
	function atualizar ()
	{

		$_sql = "UPDATE hp_elementos 
					SET urlElemento = '" . $this->hpDB->real_escape_string ($this->linkURL) . "',
						descricaoElemento = '" . $this->hpDB->real_escape_string ($this->descricaoLink) . "',
						dicaElemento = '" . $this->hpDB->real_escape_string ($this->dicaLink) . "',
						urlElementoLocal = $this->localLink,
						urlElementoTarget = '" . $this->hpDB->real_escape_string ($this->targetLink) . "',
						urlElementoSSL = $this->urlElementoSSL,
						urlElementoSVN = $this->urlElementoSVN
				  WHERE idElemento = $this->idLink";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar o link: " . $this->idLink . ": " . $this->descricaoLink);
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}

	}
	
	public function excluir()
	{

		if (isset($this->idLink)) {
			$_sql = "DELETE FROM hp_elementos where idElemento = $this->idLink";
			$result = $this->hpDB->query($_sql);
			if (!$result)
			{
				die ("erro ao excluir o link: " . $this->idLink . ": " . $this->descricaoLink);
			}
			else
			{
				return $this->hpDB->getAffectedRows();
			}
		}

	}

	public function getArray()
	{
		if (isset($this->idLink))
		{
			return array(
					'idElemento' => $this->idLink,
					'descricaoElemento' => $this->descricaoLink,
					'idGrupo' => $this->idGrupo,
					'posGrupo' => $this->posGrupo,
					'tipoElemento' => ELEMENTO_LINK,
					'linkURL' => htmlentities($this->linkURL),
					'descricaoLink' => $this->descricaoLink,
					'dicaLink' => $this->dicaLink,
					'localLink' => $this->localLink,
					'targetLink' => $this->targetLink,
					'urlElementoSSL' => $this->urlElementoSSL,
					'urlElementoSVN' => $this->urlElementoSVN);
		}
		else
		{
			die('erro em wLink::getArray(). Não inicializado!');
		}
	}

	static function newFromArray($_array)
	{

		$newLink = new wLink(NULL);
		
		$newLink->idLink = $_array['idElemento'];
		$newLink->idGrupo = $_array['idGrupo'];
		$newLink->posGrupo = $_array['posGrupo'];
		$newLink->linkURL = $_array['urlElemento'];
		$newLink->descricaoLink = $_array['descricaoElemento'];
		$newLink->dicaLink = $_array['dicaElemento'];
		$newLink->localLink = $_array['urlElementoLocal'];
		$newLink->targetLink = $_array['urlElementoTarget'];
		$newLink->urlElementoSSL = $_array['urlElementoSSL'];
		$newLink->urlElementoSVN = $_array['urlElementoSVN'];
		$newLink->idElemento = $newLink->idLink;
		$newLink->descricaoElemento = $newLink->descricaoLink;

		return $newLink;
	}
		
	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = " . ELEMENTO_LINK;
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	
} /*}}} wLink */

class wForm extends elemento
{ /*{{{*/
	var $idForm;
	var $nomeForm;
	var $acao;
	var $nomeCampo;
	var $tamanhoCampo;
	var $descricaoForm;
	
	public function __construct($_idForm) // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
	{

		$this->tipoElemento = ELEMENTO_FORM;
		parent::elemento();

		if (isset($_idForm) && $_idForm != NULL) 
		{
			$line = $this->hpDB->query("SELECT * from hp_elementos where idElemento = $_idForm");
			if (!$line) 
			{
				die("idForm incorreto: $_idForm");
			}
			
			$this->idForm = $line[0]['idElemento'];
			$this->idGrupo = $line[0]['idGrupo'];
			$this->posGrupo = $line[0]['posGrupo'];
			$this->nomeForm = $line[0]['formNome'];
			$this->acao = $line[0]['urlElemento'];
			$this->nomeCampo = $line[0]['formNomeCampo'];
			$this->tamanhoCampo = $line[0]['formTamanhoCampo'];
			$this->descricaoForm = $line[0]['descricaoElemento'];
			$this->idElemento = $this->idForm;
			$this->descricaoElemento = $this->descricaoForm;
		}
	}

	function inserir()
	{

		$_sql = "INSERT INTO hp_elementos 
					(idTipoElemento, idGrupo, posGrupo, formNome, urlElemento, formNomeCampo, formTamanhoCampo, descricaoElemento)
				 VALUES (" . ELEMENTO_FORM . ", $this->idGrupo, $this->posGrupo , '$this->nomeForm', 
				 '$this->acao', '$this->nomeCampo', $this->tamanhoCampo, '$this->descricaoForm')";
		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao criar o Form: '$this->descricaoForm'!");
		}
		else
		{
			$this->idForm = $this->hpDB->getLastInsertId();
			return $this->idForm;
		}
	}
	
	function atualizar ()
	{

		$_sql = "UPDATE hp_elementos 
					SET urlElemento = '$this->acao',
						descricaoElemento = '$this->descricaoForm',
						formNome = '$this->nomeForm',
						formNomeCampo = '$this->nomeCampo',
						formTamanhoCampo = '$this->tamanhoCampo'
				  WHERE idElemento = $this->idForm";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar o form: '$this->descricaoForm'!");
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}

	}
	
	public function excluir()
	{

		if (isset($this->idForm)) {
			$_sql = "DELETE FROM hp_elementos where idElemento = $this->idForm";
			$result = $this->hpDB->query($_sql);
			if (!$result)
			{
				die ("erro ao excluir o form: '$this->descricaoForm'!");
			}
			else
			{
				return $this->hpDB->getAffectedRows();
			}
		}

	}

	public function getArray()
	{
		if (isset($this->idForm))
		{
			return array(
					'idElemento' => $this->idForm,
					'descricaoElemento' => $this->descricaoForm,
					'idGrupo' => $this->idGrupo,
					'posGrupo' => $this->posGrupo,
					'tipoElemento' => ELEMENTO_FORM,
					'nomeForm' => $this->nomeForm,
					'acao' => $this->acao,
					'nomeCampo' => $this->nomeCampo,
					'tamanhoCampo' => $this->tamanhoCampo,
					'descricaoForm' => $this->descricaoForm);
		}
		else
		{
			die('erro em wForm::getArray(). Não inicializado!');
		}
	}

	static function newFromArray($_array)
	{
		$newForm = new wForm(NULL);

		$newForm->idForm = $_array['idElemento'];
		$newForm->idGrupo = $_array['idGrupo'];
		$newForm->posGrupo = $_array['posGrupo'];
		$newForm->nomeForm = $_array['formNome'];
		$newForm->acao = $_array['urlElemento'];
		$newForm->nomeCampo = $_array['formNomeCampo'];
		$newForm->tamanhoCampo = $_array['formTamanhoCampo'];
		$newForm->descricaoForm = $_array['descricaoElemento'];
		$newForm->idElemento = $newForm->idForm;
		$newForm->descricaoElemento = $newForm->descricaoForm;

		return $newForm;
	}
		
	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = " . ELEMENTO_FORM;
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	
} /*}}} wForm */

class wSeparador extends elemento
{ /*{{{*/
	var $idSeparador;
	var $descricaoSeparador;
	var $breakBefore;
	
	public function __construct($_idSeparador) // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
	{

		$this->tipoElemento = ELEMENTO_SEPARADOR;
		parent::elemento();

		if (isset($_idSeparador) && $_idSeparador != NULL)
		{
			$line = $this->hpDB->query("SELECT * from hp_elementos where idElemento = $_idSeparador");
			if (!$line) 
			{
				die("idSeparador incorreto: $_idSeparador");
			}
			
			$this->idSeparador = $line[0]['idElemento'];
			$this->idGrupo = $line[0]['idGrupo'];
			$this->posGrupo = $line[0]['posGrupo'];
			$this->descricaoSeparador = $line[0]['descricaoElemento'];
			$this->breakBefore = $line[0]['separadorBreakBefore'];
			$this->idElemento = $this->idSeparador;
			$this->descricaoElemento = $this->descricaoSeparador;
		}
	}

	function inserir()
	{

		$_sql = "INSERT INTO hp_elementos 
					(idTipoElemento, idGrupo, posGrupo, descricaoElemento, separadorBreakBefore)
				 VALUES 
				 	(" . ELEMENTO_SEPARADOR . ", $this->idGrupo, $this->posGrupo , '$this->descricaoSeparador', $this->breakBefore)";
		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao criar o separador: [$this->descricaoSeparador]!");
		}
		else
		{
			$this->idSeparador = $this->hpDB->getLastInsertId();
			return $this->idSeparador;
		}
	}
	
	function atualizar ()
	{

		$_sql = "UPDATE hp_elementos 
					SET descricaoElemento = '$this->descricaoSeparador',
						separadorBreakBefore = $this->breakBefore
				  WHERE idElemento = $this->idSeparador";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar o separador: '$this->descricaoSeparador'!");
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}

	}
	
	public function excluir()
	{

		if (isset($this->idSeparador)) {
			$_sql = "DELETE FROM hp_elementos where idElemento = $this->idSeparador";
			$result = $this->hpDB->query($_sql);
			if (!$result)
			{
				die ("erro ao excluir o separador: '$this->descricaoSeparador'!");
			}
			else
			{
				return $this->hpDB->getAffectedRows();
			}
		}

	}

	public function getArray()
	{
		if (isset($this->idSeparador))
		{
			return array(
					'idElemento' => $this->idSeparador,
					'descricaoElemento' => $this->descricaoSeparador,
					'idGrupo' => $this->idGrupo,
					'posGrupo' => $this->posGrupo,
					'tipoElemento' => ELEMENTO_SEPARADOR,
					'descricaoSeparador' => $this->descricaoSeparador,
					'breakBefore' => $this->breakBefore	);
		}
		else
		{
			die('erro em wSeparador::getArray(). Não inicializado!');
		}
	}

	static function newFromArray($_array)
	{
		$newSeparador = new wSeparador(NULL);

		$newSeparador->idSeparador = $_array['idElemento'];
		$newSeparador->idGrupo = $_array['idGrupo'];
		$newSeparador->posGrupo = $_array['posGrupo'];
		$newSeparador->descricaoSeparador = $_array['descricaoElemento'];
		$newSeparador->breakBefore = $_array['separadorBreakBefore'];
		$newSeparador->idElemento = $newSeparador->idSeparador;
		$newSeparador->descricaoElemento = $newSeparador->descricaoSeparador;

		return $newSeparador;
	}
		
	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = " . ELEMENTO_SEPARADOR;
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	

} /*}}} wSeparador */

class wImagem extends elemento
{ /*{{{*/
	var $idImagem;
	var $descricaoImagem;
	var $ImagemURL;
	
	public function __construct($_idImagem)  // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
	{

		$this->tipoElemento = ELEMENTO_IMAGEM;
		parent::elemento();

		if (isset($_idImagem) && $_idImagem != NULL)
		{
			$line = $this->hpDB->query("SELECT * from hp_elementos where idElemento = $_idImagem");
			if (!$line) 
			{
				die("idImagem incorreto: $_idImagem");
			}
			
			$this->idImagem = $line[0]['idElemento'];
			$this->idGrupo = $line[0]['idGrupo'];
			$this->posGrupo = $line[0]['posGrupo'];
			$this->urlImagem = $line[0]['urlElemento'];
			$this->localLink = $line[0]['urlElementoLocal'];
			$this->descricaoImagem = $line[0]['descricaoElemento'];
			$this->idElemento = $this->idImagem;
			$this->descricaoElemento = $this->descricaoImagem;
		}
	}

	function inserir()
	{

		$_sql = "INSERT INTO hp_elementos 
					(idTipoElemento, idGrupo, posGrupo, descricaoElemento, urlElemento, urlElementoLocal)
				 VALUES 
				 (" . ELEMENTO_IMAGEM . ", $this->idGrupo, $this->posGrupo , '$this->descricaoImagem', '$this->urlImagem', $this->localLink)";
		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao criar a imagem: [$this->descricaoImagem]!");
		}
		else
		{
			$this->idImagem = $this->hpDB->getLastInsertId();
			return $this->idImagem;
		}
	}
	
	function atualizar ()
	{

		$_sql = "UPDATE hp_elementos 
					SET descricaoElemento = '$this->descricaoImagem',
						urlElemento = '$this->urlImagem',
						urlElementoLocal = $this->localLink
				  WHERE idElemento = $this->idImagem";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar a imagem: '$this->descricaoImagem'!");
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}

	}
	
	public function excluir()
	{

		if (isset($this->idImagem)) {
			$_sql = "DELETE FROM hp_elementos where idElemento = $this->idImagem";
			$result = $this->hpDB->query($_sql);
			if (!$result)
			{
				die ("erro ao excluir a imagem: '$this->descricaoImagem'!");
			}
			else
			{
				return $this->hpDB->getAffectedRows();
			}
		}

	}

	public function getArray()
	{
		if (isset($this->idImagem))
		{
			return array(
					'idElemento' => $this->idImagem,
					'descricaoElemento' => $this->descricaoImagem,
					'idGrupo' => $this->idGrupo,
					'posGrupo' => $this->posGrupo,
					'tipoElemento' => ELEMENTO_IMAGEM,
					'urlImagem' => $this->urlImagem,
					'localLink' => $this->localLink,
					'descricaoImagem' => $this->descricaoImagem);
		}
		else
		{
			die('erro em wImagem::getArray(). Não inicializado!');
		}
	}

	static function newFromArray($_array)
	{
		$newImagem = new wImagem(NULL);

		$newImagem->idImagem = $_array['idElemento'];
		$newImagem->idGrupo = $_array['idGrupo'];
		$newImagem->posGrupo = $_array['posGrupo'];
		$newImagem->urlImagem = $_array['urlElemento'];
		$newImagem->localLink = $_array['urlElementoLocal'];
		$newImagem->descricaoImagem = $_array['descricaoElemento'];
		$newImagem->idElemento = $newImagem->idImagem;
		$newImagem->descricaoElemento = $newImagem->descricaoImagem;

		return $newImagem;
	}
		
	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = " . ELEMENTO_IMAGEM;
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	

} /*}}} wImagem */

class wRssFeed extends elemento
{ /*{{{*/
	var $idRssFeed;
	var $rssURL;
	var $rssItemNum;
	
	public function __construct($_idRssFeed)  // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
	{

		$this->tipoElemento = ELEMENTO_RSSFEED;
		parent::elemento();
		$this->tipoElemento = ELEMENTO_RSSFEED;

		if (isset($_idRssFeed) && $_idRssFeed != NULL)
		{
			$line = $this->hpDB->query("SELECT * from hp_elementos where idElemento = $_idRssFeed");
			if (!$line) 
			{
				die("idRssFeed incorreto: $_idRssFeed");
			}
			
			$this->idRssFeed = $line[0]['idElemento'];
			$this->idGrupo = $line[0]['idGrupo'];
			$this->posGrupo = $line[0]['posGrupo'];
			$this->rssURL = $line[0]['urlElemento'];
			$this->rssItemNum = $line[0]['rssItemNum'];
			$this->idElemento = $this->idRssFeed;
			$this->descricaoElemento = $this->rssURL;
		}
	}

	function inserir()
	{

		$_sql = "INSERT INTO hp_elementos 
					(idTipoElemento, idGrupo, posGrupo, urlElemento, rssItemNum)
				 VALUES 
				 (" . ELEMENTO_RSSFEED . ", $this->idGrupo, $this->posGrupo , '$this->rssURL', $this->rssItemNum)";
		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao criar o rss feed: [$this->rssURL]!");
		}
		else
		{
			$this->idRssFeed = $this->hpDB->getLastInsertId();
			return $this->idRssFeed;
		}
	}
	
	function atualizar ()
	{

		$_sql = "UPDATE hp_elementos 
					SET urlElemento = '$this->rssURL',
						rssItemNum = $this->rssItemNum
				  WHERE idElemento = $this->idRssFeed";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar rss feed: '$this->rssURL'!");
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}

	}
	
	public function excluir()
	{

		if (isset($this->idRssFeed)) {
			$_sql = "DELETE FROM hp_elementos where idElemento = $this->idRssFeed";
			$result = $this->hpDB->query($_sql);
			if (!$result)
			{
				die ("erro ao excluir o Rss Feed: '$this->rssURL'!");
			}
			else
			{
				return $this->hpDB->getAffectedRows();
			}
		}

	}

	public function getArray()
	{
		if (isset($this->idRssFeed))
		{
			return array(
					'idElemento' => $this->idRssFeed,
					'descricaoElemento' => $this->rssURL,
					'idGrupo' => $this->idGrupo,
					'posGrupo' => $this->posGrupo,
					'tipoElemento' => ELEMENTO_RSSFEED,
					'rssURL' => $this->rssURL,
					'rssItemNum' => $this->rssItemNum );
		}
		else
		{
			die('erro em wRssFeed::getArray(). Não inicializado!');
		}
	}

	static function newFromArray($_array)
	{
		$newRssFeed = new wRssFeed(NULL);

		$newRssFeed->idRssFeed = $_array['idElemento'];
		$newRssFeed->idGrupo = $_array['idGrupo'];
		$newRssFeed->posGrupo = $_array['posGrupo'];
		$newRssFeed->rssURL = $_array['urlElemento'];
		$newRssFeed->rssItemNum = $_array['rssItemNum'];
		$newRssFeed->idElemento = $newRssFeed->idRssFeed;
		$newRssFeed->descricaoElemento = $newRssFeed->rssURL;

		return $newRssFeed;
	}
		
	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = " . ELEMENTO_RSSFEED;
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	

} /*}}} wRssFeed */

class wTemplate extends elemento
{ /*{{{*/
	var $idTemplate;
	var $descricaoTemplate;
	var $nomeTemplate;
	
	public function __construct($_idTemplate)  // < 0 -> só cria e volta. NULL -> cria, conecta ao bd e volta. > 0 -> lê
	{

		$this->tipoElemento = ELEMENTO_TEMPLATE;
		parent::elemento();

		if (isset($_idTemplate) && $_idTemplate != NULL)
		{
			$line = $this->hpDB->query("SELECT * from hp_elementos where idElemento = $_idTemplate");
			if (!$line) 
			{
				die("idTemplate incorreto: $_idTemplate");
			}
			
			$this->idTemplate = $line[0]['idElemento'];
			$this->idGrupo = $line[0]['idGrupo'];
			$this->posGrupo = $line[0]['posGrupo'];
			$this->descricaoTemplate = $line[0]['descricaoElemento'];
			$this->nomeTemplate = $line[0]['templateFileName'];
			$this->idElemento = $this->idTemplate;
			$this->descricaoElemento = $this->descricaoTemplate;
		}
	}

	function inserir()
	{

		$_sql = "INSERT INTO hp_elementos 
					(idTipoElemento, idGrupo, posGrupo, templateFileName, descricaoElemento)
				VALUES 
					(" . ELEMENTO_TEMPLATE . ", $this->idGrupo, $this->posGrupo , '$this->nomeTemplate', '$this->descricaoTemplate')";
		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao criar o template: '$this->descricaoTemplate'!");
		}
		else
		{
			$this->idTemplate = $this->hpDB->getLastInsertId();
			return $this->idTemplate;
		}
	}
	
	function atualizar ()
	{

		$_sql = "UPDATE hp_elementos 
					SET descricaoElemento = '$this->descricaoTemplate',
						templateFileName = '$this->nomeTemplate'
				  WHERE idElemento = $this->idTemplate";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao atualizar o template: '$this->descricaoTemplate'!");
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}

	}
	
	public function excluir()
	{

		if (isset($this->idTemplate)) {
			$_sql = "DELETE FROM hp_elementos where idElemento = $this->idTemplate";
			$result = $this->hpDB->query($_sql);
			if (!$result)
			{
				die ("erro ao excluir o template: '$this->descricaoTemplate'!");
			}
			else
			{
				return $this->hpDB->getAffectedRows();
			}
		}

	}

	public function getArray()
	{
		if (isset($this->idTemplate))
		{
			return array(
					'idElemento' => $this->idTemplate,
					'descricaoElemento' => $this->descricaoTemplate,
					'idGrupo' => $this->idGrupo,
					'posGrupo' => $this->posGrupo,
					'tipoElemento' => ELEMENTO_TEMPLATE,
					'descricaoTemplate' => $this->descricaoTemplate,
					'nomeTemplate' => $this->nomeTemplate );
		}
		else
		{
			die('erro em wTemplate::getArray(). Não inicializado!');
		}
	}

	static function newFromArray($_array)
	{
		$newTemplate = new wTemplate(NULL);

		$newTemplate->idTemplate = $_array['idElemento'];
		$newTemplate->idGrupo = $_array['idGrupo'];
		$newTemplate->posGrupo = $_array['posGrupo'];
		$newTemplate->descricaoTemplate = $_array['descricaoElemento'];
		$newTemplate->nomeTemplate = $_array['templateFileName'];
		$newTemplate->idElemento = $newTemplate->idTemplate;
		$newTemplate->descricaoElemento = $newTemplate->descricaoTemplate;

		return $newTemplate;
	}
		
	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_elementos where idTipoElemento = " . ELEMENTO_TEMPLATE;
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	

} /*}}} wTemplate */

class grupo extends elementoAgrupado
{ /*{{{*/	
	var $descricaoGrupo;
	var $idTipoGrupo;
	var $descricaoTipoGrupo;
	var $grupoRestrito;
	var $restricaoGrupo;
	var $idCategoria;
	var $posCategoria;

	public function __construct($_idGrupo, $_idCategoria = NULL) 
	{
		parent::elementoAgrupado();
		
		if ($_idGrupo != NULL)
		{
			if ($_idCategoria != NULL)
			{
				$_sql = "SELECT g.*, gc.posCategoria, tg.descricaoTipoGrupo
						   from hp_grupos g, hp_gruposxcategorias gc, hp_tiposgrupos tg
						  where g.idGrupo = gc.idGrupo
							and g.idTipoGrupo = tg.idTipoGrupo
							and gc.idGrupo = $_idGrupo
							and gc.idCategoria = $_idCategoria";
			}
			else
			{
				$_sql = "SELECT g.*, tg.descricaoTipoGrupo
						   FROM hp_grupos g, hp_tiposgrupos tg
						  WHERE g.idTipoGrupo = tg.idTipoGrupo
							AND g.idGrupo = $_idGrupo";
			}
			
			$line = $this->hpDB->query($_sql);
			
			if (!$line) 
			{
				die("idGrupo incorreto: $_idGrupo");
			}
				
			$this->idGrupo = $line[0]['idGrupo'];
			$this->descricaoGrupo = $line[0]['descricaoGrupo'];
			$this->idTipoGrupo = $line[0]['idTipoGrupo'];
			$this->descricaoTipoGrupo = $line[0]['descricaoTipoGrupo'];
			$this->grupoRestrito = $line[0]['grupoRestrito'];
			$this->restricaoGrupo = $line[0]['restricaoGrupo'];
			if ($_idCategoria != NULL)
			{
				$this->idCategoria = $_idCategoria;
				$this->posCategoria = $line[0]['posCategoria'];
			}
		}

	}
	
	function inserir() 
	{ 
		$_sql = "INSERT INTO hp_grupos (descricaoGrupo, idTipoGrupo, grupoRestrito, restricaoGrupo)
		VALUES ('$this->descricaoGrupo', $this->idTipoGrupo, $this->grupoRestrito, '$this->restricaoGrupo')";

		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao criar o grupo: " . $this->descricaoGrupo);
		}
		else
		{
			$this->idGrupo = $this->hpDB->getLastInsertId();
			return $this->idGrupo;
		}
	}
	
	function atualizar() 
	{ 
		$_sql = "UPDATE hp_grupos SET descricaoGrupo = '$this->descricaoGrupo',
									 idTipoGrupo = $this->idTipoGrupo,
									 grupoRestrito = $this->grupoRestrito,
									 restricaoGrupo = '$this->restricaoGrupo'
				 WHERE idGrupo = $this->idGrupo";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao atualizar o grupo: " . $this->idGrupo . ": " . $this->descricaoGrupo);
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}
	}
	
	function excluir() 
	{ 
		$result = true;

		$this->lerElementos();
		
		$result = $result and $this->hpDB->begin();

		foreach ($this->elementos as $elemento) {
			$result = $result and $elemento->excluir();
		}

		$_sql = "delete from hp_grupos where idGrupo = $this->idGrupo";
		$result = $result and $this->hpDB->query($_sql);

		if ($result) {
			$result = $result and $this->hpDB->commit();
		}
		else
		{
			$result = $result and $this->hpDB->rollback();
		}

		return $result;
	}

	function getArray() 
	{ 
		if (isset($this->idGrupo))
		{
			return array(
				'idCategoria' => $this->idCategoria,
				'posCategoria' => $this->posCategoria,
				'idGrupo' => $this->idGrupo,
				'descricaoGrupo' => $this->descricaoGrupo,
				'idTipoGrupo' => $this->idTipoGrupo,
				'descricaoTipoGrupo' => $this->descricaoTipoGrupo,
				'grupoRestrito' => $this->grupoRestrito,
				'restricaoGrupo' => $this->restricaoGrupo);
		}
		else
		{
			die('erro em grupo::getArray(). Não inicializado!');
		}	
	}
	
	static function newFromArray($_array)
	{
		$newGrupo = new grupo(NULL);

		$newGrupo->idGrupo = $_array['idGrupo'];
		$newGrupo->descricaoGrupo = $_array['descricaoGrupo'];
		$newGrupo->idTipoGrupo = $_array['idTipoGrupo'];
		$newGrupo->descricaoTipoGrupo = $_array['descricaoTipoGrupo'];
		$newGrupo->grupoRestrito = $_array['grupoRestrito'];
		$newGrupo->restricaoGrupo = $_array['restricaoGrupo'];
		$newGrupo->idCategoria = $_array['idCategoria'];
		$newGrupo->posCategoria = $_array['posCategoria'];

		return $newGrupo;
	}
		
	function numeroElementos()
	{
		if (!isset($this->idGrupo))
		{
			die('não posso ler o numero de elementos de um grupo se não souber o grupo!');
		}

		$_count = $this->hpDB->query("select COUNT(*) as numElementos from hp_elementos where idGrupo = $this->idGrupo");
		if (!$_count)
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
		
	function lerElementos()
	{
		// lê tudo: links, forms e separadores
		// $_elementos = $this->hpDB->query("call obtemElementosdoGrupo($this->idGrupo)");
		$_elementos = $this->hpDB->query("select * from hp_elementos where idGrupo = $this->idGrupo order by posGrupo");

		if (!$_elementos) 
		{
			return array();
		}

		foreach ($_elementos as $_el)
		{
			switch ($_el['idTipoElemento'])
			{
				case ELEMENTO_LINK: 
					$this->elementos[] = wLink::newFromArray($_el);
					break;
					
				case ELEMENTO_FORM: 
					$this->elementos[] = wForm::newFromArray($_el);
					break;
					
				case ELEMENTO_SEPARADOR:
					$this->elementos[] = wSeparador::newFromArray($_el);
					break;

				case ELEMENTO_IMAGEM:
					$this->elementos[] = wImagem::newFromArray($_el);
					break;

				case ELEMENTO_RSSFEED:
					$this->elementos[] = wRssFeed::newFromArray($_el);
					break;

				case ELEMENTO_TEMPLATE:
					$this->elementos[] = wTemplate::newFromArray($_el);
					break;

				default:
					die ('tipo errado de elemento. socorro!');
			}
		}
	}

	public function elementoNaPosicao($_posElemento)
	{
		// lê tudo: links, forms e separadores
		$_sql = "select *
				   from hp_elementos 
				  where idGrupo = $this->idGrupo and posGrupo = $_posElemento";
		$_elemento = $this->hpDB->query($_sql);

		if (!$_elemento or count($_elemento) == 0) 
		{
			die ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo na posição $_posElemento");
		}

		
		switch ($_elemento[0]['idTipoElemento'])
		{
			case ELEMENTO_LINK: 
				$elemento = wLink::newFromArray($_elemento[0]);
			break;
				
			case ELEMENTO_FORM: 
				$elemento = wForm::newFromArray($_elemento[0]);
			break;
				
			case ELEMENTO_SEPARADOR:
	  			$elemento = wSeparador::newFromArray($_elemento[0]);
			break;

			case ELEMENTO_IMAGEM:
				$elemento = wImagem::newFromArray($_elemento[0]);
			break;

			case ELEMENTO_RSSFEED:
				$elemento = wRssFeed::newFromArray($_elemento[0]);
			break;

			case ELEMENTO_TEMPLATE:
				$elemento = wTemplate::newFromArray($_elemento[0]);
			break;

			default:
				die ('tipo errado de elemento. socorro!');
		}

		return $elemento;
	}

	function elementoDeCodigo($_idElemento)
	{
		// lê tudo: links, forms e separadores
		$_sql = "select *
				   from hp_elementos 
				  where idGrupo = $this->idGrupo and idElemento = $_idElemento";
		$_elemento = $this->hpDB->query($_sql);

		if (!$_elemento or count($_elemento) == 0) 
		{
			die ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
		}

		
		switch ($_elemento[0]['idTipoElemento'])
		{
			case ELEMENTO_LINK: 
				$elemento = wLink::newFromArray($_elemento[0]);
			break;
				
			case ELEMENTO_FORM: 
				$elemento = wForm::newFromArray($_elemento[0]);
			break;
				
			case ELEMENTO_SEPARADOR:
	  			$elemento = wSeparador::newFromArray($_elemento[0]);
			break;

			case ELEMENTO_IMAGEM:
				$elemento = wImagem::newFromArray($_elemento[0]);
			break;

			case ELEMENTO_RSSFEED:
				$elemento = wRssFeed::newFromArray($_elemento[0]);
			break;

			case ELEMENTO_TEMPLATE:
				$elemento = wTemplate::newFromArray($_elemento[0]);
			break;

			default:
				die ('tipo errado de elemento. socorro!');

		}

		return $elemento;
	}
	
	function lerNaoElementos() { } 

	function deslocarElementoParaCima($_idElemento)
	{ 
		// calcula a posição do elemento anterior.
		$_sql = "SELECT posGrupo FROM hp_elementos WHERE idGrupo = $this->idGrupo  AND idElemento = $_idElemento";
		$_return = $this->hpDB->query($_sql);
		if (!$_return or count($_return) == 0) 
		{
			die ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
		}
		$_ProxPosGrupo = $_return[0]['posGrupo']-1;
		if ($_ProxPosGrupo > 0) {
			// desloca o elemento anterior para baixo (se ele não existir, não tem problema).
		    $_sql = "UPDATE hp_elementos set posGrupo = posGrupo + 1 WHERE idGrupo = $this->idGrupo AND posGrupo = $_ProxPosGrupo";
			$_return = $this->hpDB->query($_sql);
		
			// desloca para cima o elemento solicitado...
			$_sql = "UPDATE hp_elementos set posGrupo = $_ProxPosGrupo WHERE idGrupo = $this->idGrupo AND idElemento = $_idElemento";
			$_return = $this->hpDB->query($_sql);
		}
		return $_return;
	}

	function deslocarElementoParaBaixo($_idElemento)
	{ 

		// calcula a posição do elemento anterior.
		$_sql = "SELECT posGrupo FROM hp_elementos WHERE idGrupo = $this->idGrupo AND idElemento = $_idElemento";
		$_return = $this->hpDB->query($_sql);
		if (!$_return or count($_return) == 0) 
		{
			die ("Não há qualquer elemento do grupo : $this->idGrupo :  $this->descricaoGrupo com chave $_idElemento");
		}
		$_ProxPosGrupo = $_return[0]['posGrupo']+1;

		// obtém o total de elementos deste grupo.
		$_sql = "SELECT Count(1) as numElementos from hp_elementos WHERE idGrupo = $this->idGrupo";
		$_return = $this->hpDB->query($_sql);

		if ($_return[0]['numElementos'] >= $_ProxPosGrupo) {
			// desloca o elemento posterior para cima. (se ele não existir não tem problema)
			$_sql = "UPDATE hp_elementos set posGrupo = posGrupo - 1 WHERE idGrupo = $this->idGrupo AND posGrupo = $_ProxPosGrupo";
			$_return = $this->hpDB->query($_sql);

			// desloca para baixo o elemento solicitado.
			$_sql = "UPDATE hp_elementos set posGrupo = $_ProxPosGrupo
					  WHERE idGrupo = $this->idGrupo 
					    AND idElemento = $_idElemento";
			$_return = $this->hpDB->query($_sql);
		}

		return $_return;

	}
	
	function incluirElemento($_idElemento, $_posElemento = 0) { }
	
	function excluirElemento($_idElemento) { } 
	
	static function getGrupos()
	{
		// como esta função é um método de classe, não posso usar nenhuma variável de instância, apenas locais e globais.
		// desta forma, tenho que usar uma nova conexão para a base de dados, ainda que já haja uma aberta.
		global $global_hpDB;

		$_sql = "SELECT * FROM hp_grupos order by descricaoGrupo";
		$_grupos = $global_hpDB->query($_sql);
		if (!$_grupos) 
		{
			die('erro: não consegui ler nenhum grupo!');
		}
		else
		{
			foreach ($_grupos as $_grp)
			{
				$grupos[] = array(
					'idGrupo' => $_grp['idGrupo'],
					'descricaoGrupo' => $_grp['descricaoGrupo'],
					'idTipoGrupo' => $_grp['idTipoGrupo'],
					'grupoRestrito' => $_grp['grupoRestrito'],
					'restricaoGrupo' => $_grp['restricaoGrupo']);
			}
		}
		return isset($grupos) ? $grupos : FALSE;
	}

	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_grupos";
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	
} /*}}} grupo */

class categoria extends elementoAgrupado
{ /*{{{*/
	var $idCategoria;
	var $descricaoCategoria;
	var $idPagina;
	var $posPagina;
	var $categoriaRestrita;
	var $restricaoCategoria;

	public function __construct($_idCategoria = NULL, $_idPagina = NULL)
	{

		parent::elementoAgrupado();

		// se não passou $_idCategoria, deve provavelmente estar criando uma categoria.
		if ($_idCategoria == NULL) 
		{
			return true;
		}

		if ($_idPagina != NULL)
		{
			$_sql = "SELECT c.*, cp.posPagina
				   from hp_categorias c, hp_categoriasxpaginas cp 
				  where c.idCategoria = cp.idCategoria
					and cp.idCategoria = $_idCategoria
					and cp.idPagina = $_idPagina";
		}
		else
		{
			$_sql = "SELECT * from hp_categorias where idCategoria = $_idCategoria";
		}

		$line = $this->hpDB->query($_sql);
		if (!$line) 
		{
			die("idCategoria incorreto: $_idCategoria");
		}
		
		$this->idCategoria = $_idCategoria;
		$this->descricaoCategoria = $line[0]['descricaoCategoria'];
		$this->categoriaRestrita = $line[0]['categoriaRestrita'];
		$this->restricaoCategoria = $line[0]['restricaoCategoria'];
		if ($_idPagina != NULL)
		{
			$this->idPagina = $_idPagina;
			$this->posPagina = $line[0]['posPagina'];
		}
	}

	function inserir() 
	{ 
		$_sql = "INSERT INTO hp_categorias (descricaoCategoria, categoriaRestrita, restricaoCategoria)
				 VALUES ('$this->descricaoCategoria', $this->categoriaRestrita, '$this->restricaoCategoria')";

		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao criar a categoria: " . $this->descricaoCategoria);
		}
		else
		{
			$this->idCategoria = $this->hpDB->getLastInsertId();
			return $this->idCategoria;
		}
		 
	}
	
	function atualizar() 
	{ 
		$_sql = "UPDATE hp_categorias SET descricaoCategoria = '$this->descricaoCategoria',
									 categoriaRestrita = $this->categoriaRestrita,
									 restricaoCategoria = '$this->restricaoCategoria'
				 WHERE idCategoria = $this->idCategoria";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao atualizar a categoria: " . $this->idCategoria . ": " . $this->descricaoCategoria);
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}
	}
	
	function excluir() 
	{ 
		$result = true;

		$this->lerElementos();
		
		$result = $result and $this->hpDB->begin();

		foreach ($this->elementos as $elemento) {
			$result = $result and $this->excluirElemento($elemento->idGrupo);
		}

		$_sql = "delete from hp_categorias where idCategoria = $this->idCategoria";
		$result = $result and $this->hpDB->query($_sql);

		if ($result) {
			$result = $result and $this->hpDB->commit();
		}
		else
		{
			$result = $result and $this->hpDB->rollback();
		}

		return $result;
	}

	function getArray() 
	{ 
		if (isset($this->idCategoria))
		{
			return array(
				'idCategoria' => $this->idCategoria,
				'descricaoCategoria' => $this->descricaoCategoria,
				'posPagina' => $this->posPagina,
				'categoriaRestrita' => $this->categoriaRestrita,
				'restricaoCategoria' => $this->restricaoCategoria);
		}
		else
		{
			die('erro em categoria::getArray(). Não inicializado!');
		}
	}
	
	static function newFromArray($_array)
	{
		$newCategoria = new Categoria(NULL);

		$newCategoria->idCategoria = $_array['idCategoria'];
		$newCategoria->descricaoCategoria = $_array['descricaoCategoria'];
		$newCategoria->categoriaRestrita = $_array['categoriaRestrita'];
		$newCategoria->restricaoCategoria = $_array['restricaoCategoria'];
		$newCategoria->idPagina = $_array['idPagina'];
		$newCategoria->posPagina = $_array['posPagina'];

		return $newCategoria;
	}
		
	function numeroElementos() { }

	function lerElementos()
	{

		// verifica se foi pedido algum grupo restrito
		// na stored proc chamada, eu faço um REGEXP com os grupos passados. aqui eu monto a expressão regular...
		if (isset($_REQUEST['gr'])) 
		{
			if (strpos($_REQUEST['gr'], 'all') !== false) 
			{
				$_grParm = ".*";
			}
			elseif ($_REQUEST['gr'] != '') // pediu alguns dos grupos restritos corretamente?
			{
				// sim, aceitando qualquer separador...
				$_grParm = str_replace(array(",", ".", ";", "+", "-"), "|", $_REQUEST['gr']);
				while (strpos($_grParm, "||") !== FALSE) $_grParm = str_replace("||", "|", $_grParm);
			}
			else // não, coloca o default
			{
				$_grParm = "_";
			}
		}
		else // não pediu nenhum grupo restrito
		{
			$_grParm = "_";
		}

		$_sql = "SELECT g.*, gc.*, tg.*
       from hp_grupos g, hp_gruposxcategorias gc, hp_tiposgrupos tg
      where gc.idGrupo = g.idGrupo
        and gc.idCategoria = $this->idCategoria
				and g.idTipoGrupo = tg.idTipoGrupo
        and ( (grupoRestrito = 0)
           or (grupoRestrito = 1 and restricaoGrupo REGEXP '$_grParm' ) )
     order by gc.posCategoria;";

		$_elementos = $this->hpDB->query($_sql);
		if (!$_elementos)
		{
			return array();
		}

		foreach ($_elementos as $_el)
		{
			$this->elementos[] = grupo::newFromArray($_el);
		}
	}

	function elementoNaPosicao($_posElemento) { }

	function elementoDeCodigo($_idElemento) { }
	
	// se $_idPagina foi informado quando categoria:: foi instanciada, 
	// 		lê todos os grupos que não pertencem a esta página
	// senão, 
	//		lê todos os grupos que não pertencem a esta categoria
	function lerNaoElementos() 
	{ 
		$_sql = "SELECT DISTINCT g.*, 0 as idCategoria, 0 as idTipoGrupo, '' as descricaoTipoGrupo, 0 as posCategoria
				   FROM hp_grupos g, hp_gruposxcategorias gc
				  WHERE g.idGrupo not in (SELECT idGrupo FROM hp_gruposxcategorias where idCategoria = $this->idCategoria)
				  ORDER BY g.descricaoGrupo";

		$_naoElementos = $this->hpDB->query($_sql);

		foreach ($_naoElementos as $_nel)
		{
			$_grupo = grupo::newFromArray($_nel);
			$naoElementos[] = $_grupo->getArray();
		}

		return isset($naoElementos) ? $naoElementos : false ;
	} 

	function deslocarElementoParaCima($_idElemento) 
	{ 
		// calcula a posição do grupo anterior.
		$_sql = "SELECT posCategoria FROM hp_gruposxcategorias  WHERE idCategoria = $this->idCategoria AND idGrupo = $_idElemento";
		$_return = $this->hpDB->query($_sql);
		if (!$_return or count($_return) == 0) 
		{
			die ("Não há qualquer grupo na categoria : $this->idCategoria :  $this->descricaoCategoria com chave $_idElemento");
		}
		$_ProxPosCategoria = $_return[0]['posCategoria']-1;
		if ($_ProxPosCategoria > 0) {
			// desloca o grupo anterior para baixo (se ele não existir, não tem problema).
		    $_sql = "UPDATE hp_gruposxcategorias set posCategoria = posCategoria + 1 WHERE idCategoria = $this->idCategoria AND posCategoria = $_ProxPosCategoria";
			$_return = $this->hpDB->query($_sql);
		
			// desloca para cima o grupo solicitado...
			$_sql = "UPDATE hp_gruposxcategorias set posCategoria = $_ProxPosCategoria WHERE idCategoria = $this->idCategoria AND idGrupo = $_idElemento";
			$_return = $this->hpDB->query($_sql);
		}
		return $_return;
	}

	public function deslocarElementoParaBaixo($_idElemento)
	{
		// calcula a posição do elemento posterior.
		$_sql = "SELECT posCategoria FROM hp_gruposxcategorias WHERE idCategoria = $this->idCategoria AND idGrupo = $_idElemento";
		$_return = $this->hpDB->query($_sql);
		if (!$_return or count($_return) == 0) 
		{
			die ("Não há qualquer grupo da Categoria : $this->idCategoria :  $this->descricaoCategoria com chave $_idElemento");
		}
		$_ProxPosCategoria = $_return[0]['posCategoria']+1;

		// obtém o total de elementos deste grupo.
		$_sql = "SELECT Count(1) as numGrupos from hp_gruposxcategorias WHERE idCategoria = $this->idCategoria";
		$_return = $this->hpDB->query($_sql);

		if ($_return[0]['numGrupos'] >= $_ProxPosCategoria) {
			// desloca o elemento posterior para cima. (se ele não existir não tem problema)
		    $_sql = "UPDATE hp_gruposxcategorias set posCategoria = posCategoria - 1 WHERE idCategoria = $this->idCategoria AND posCategoria = $_ProxPosCategoria";
			$_return = $this->hpDB->query($_sql);

			// desloca para baixo o elemento solicitado.
			$_sql = "UPDATE hp_gruposxcategorias set posCategoria = $_ProxPosCategoria WHERE idCategoria = $this->idCategoria AND idGrupo = $_idElemento";
			$_return = $this->hpDB->query($_sql);
		}

		return $_return;
	}
		
	function incluirElemento($_idElemento, $_posElemento = 0) 
	{ 
		if ($_posElemento > 0)
		{
			$_sql = "insert into hp_gruposxcategorias (idGrupo, idCategoria, posCategoria)
					 values ($_idElemento, $this->idCategoria, $_posElemento)";
		}
		else
		{
			$_sql = "insert into hp_gruposxcategorias (idGrupo, idCategoria, posCategoria)
				select $_idElemento, $this->idCategoria, max(posCategoria)+1 from hp_gruposxcategorias where idCategoria = $this->idCategoria";
		}
		$result = $this->hpDB->query($_sql);
		if (!$result) 
		{
			die('erro ao inserir um grupo na categoria: ' . $this->idCategoria . ' : ' . $_idElemento);
		}
		else
		{
			return $this->hpDB->getLastInsertId();
		}
	}
	
	function excluirElemento($_idElemento) 
	{ 
		$_sql = "DELETE FROM hp_gruposxcategorias where idGrupo = $_idElemento and idCategoria = $this->idCategoria";
		
		return $this->hpDB->query($_sql);
	} 

	static function getCategorias()
	{
		// como esta função é um método de classe, não posso usar nenhuma variável de instância, apenas locais e globais.
		// desta forma, tenho que usar uma nova conexão para a base de dados, ainda que já haja uma aberta.
		global $global_hpDB;

		$_sql = "SELECT * FROM hp_categorias";
		$_categorias = $global_hpDB->query($_sql);
		if (!$_categorias) 
		{
			die('erro: não consegui ler nenhuma categoria!');
		}
		else
		{
			foreach ($_categorias as $_cat)
			{
				$categorias[] = array(
					'idCategoria' => $_cat['idCategoria'],
					'descricaoCategoria' => $_cat['descricaoCategoria'],
					'categoriaRestrita' => $_cat['categoriaRestrita'],
					'restricaoCategoria' => $_cat['restricaoCategoria']);
			}
		}
		return isset($categorias) ? $categorias : FALSE;
	}

	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_categorias";
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	

} /*}}}categoria */

class subPagina extends elementoAgrupado
{ /*{{{*/
	var $idSubPagina;
	var $descricaoCategoria;
	var $idPagina;
	var $posPagina;
	var $categoriaRestrita;
	var $restricaoCategoria;

	public function __construct($_idSupPagina = NULL, $_idPagina = NULL)
	{

		parent::elementoAgrupado();

		// se não passou $_idCategoria, deve provavelmente estar criando uma categoria.
		if ($_idCategoria == NULL) 
		{
			return true;
		}

		if ($_idPagina != NULL)
		{
			$_sql = "SELECT c.*, cp.posPagina
				   from hp_categorias c, hp_categoriasxpaginas cp 
				  where c.idCategoria = cp.idCategoria
					and cp.idCategoria = $_idCategoria
					and cp.idPagina = $_idPagina";
		}
		else
		{
			$_sql = "SELECT * from hp_categorias where idCategoria = $_idCategoria";
		}

		$line = $this->hpDB->query($_sql);
		if (!$line) 
		{
			die("idCategoria incorreto: $_idCategoria");
		}
		
		$this->idCategoria = $_idCategoria;
		$this->descricaoCategoria = $line[0]['descricaoCategoria'];
		$this->categoriaRestrita = $line[0]['categoriaRestrita'];
		$this->restricaoCategoria = $line[0]['restricaoCategoria'];
		if ($_idPagina != NULL)
		{
			$this->idPagina = $_idPagina;
			$this->posPagina = $line[0]['posPagina'];
		}
	}

	function inserir() 
	{ 
		$_sql = "INSERT INTO hp_categorias (descricaoCategoria, categoriaRestrita, restricaoCategoria)
				 VALUES ('$this->descricaoCategoria', $this->categoriaRestrita, '$this->restricaoCategoria')";

		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao criar a categoria: " . $this->descricaoCategoria);
		}
		else
		{
			$this->idCategoria = $this->hpDB->getLastInsertId();
			return $this->idCategoria;
		}
		 
	}
	
	function atualizar() 
	{ 
		$_sql = "UPDATE hp_categorias SET descricaoCategoria = '$this->descricaoCategoria',
									 categoriaRestrita = $this->categoriaRestrita,
									 restricaoCategoria = '$this->restricaoCategoria'
				 WHERE idCategoria = $this->idCategoria";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao atualizar a categoria: " . $this->idCategoria . ": " . $this->descricaoCategoria);
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}
	}
	
	function excluir() 
	{ 
		$result = true;

		$this->lerElementos();
		
		$result = $result and $this->hpDB->begin();

		foreach ($this->elementos as $elemento) {
			$result = $result and $this->excluirElemento($elemento->idGrupo);
		}

		$_sql = "delete from hp_categorias where idCategoria = $this->idCategoria";
		$result = $result and $this->hpDB->query($_sql);

		if ($result) {
			$result = $result and $this->hpDB->commit();
		}
		else
		{
			$result = $result and $this->hpDB->rollback();
		}

		return $result;
	}

	function getArray() 
	{ 
		if (isset($this->idCategoria))
		{
			return array(
				'idCategoria' => $this->idCategoria,
				'descricaoCategoria' => $this->descricaoCategoria,
				'posPagina' => $this->posPagina,
				'categoriaRestrita' => $this->categoriaRestrita,
				'restricaoCategoria' => $this->restricaoCategoria);
		}
		else
		{
			die('erro em categoria::getArray(). Não inicializado!');
		}
	}
	
	static function newFromArray($_array)
	{
		$newCategoria = new Categoria(NULL);

		$newCategoria->idCategoria = $_array['idCategoria'];
		$newCategoria->descricaoCategoria = $_array['descricaoCategoria'];
		$newCategoria->categoriaRestrita = $_array['categoriaRestrita'];
		$newCategoria->restricaoCategoria = $_array['restricaoCategoria'];
		$newCategoria->idPagina = $_array['idPagina'];
		$newCategoria->posPagina = $_array['posPagina'];

		return $newCategoria;
	}
		
	function numeroElementos() { }

	function lerElementos()
	{

		// verifica se foi pedido algum grupo restrito
		// na stored proc chamada, eu faço um REGEXP com os grupos passados. aqui eu monto a expressão regular...
		if (isset($_REQUEST['gr'])) 
		{
			if (strpos($_REQUEST['gr'], 'all') !== false) 
			{
				$_grParm = ".*";
			}
			elseif ($_REQUEST['gr'] != '') // pediu alguns dos grupos restritos corretamente?
			{
				// sim, aceitando qualquer separador...
				$_grParm = str_replace(array(",", ".", ";", "+", "-"), "|", $_REQUEST['gr']);
				while (strpos($_grParm, "||") !== FALSE) $_grParm = str_replace("||", "|", $_grParm);
			}
			else // não, coloca o default
			{
				$_grParm = "_";
			}
		}
		else // não pediu nenhum grupo restrito
		{
			$_grParm = "_";
		}

		$_sql = "SELECT g.*, gc.*, tg.*
       from hp_grupos g, hp_gruposxcategorias gc, hp_tiposgrupos tg
      where gc.idGrupo = g.idGrupo
        and gc.idCategoria = $this->idCategoria
				and g.idTipoGrupo = tg.idTipoGrupo
        and ( (grupoRestrito = 0)
           or (grupoRestrito = 1 and restricaoGrupo REGEXP '$_grParm' ) )
     order by gc.posCategoria;";

		$_elementos = $this->hpDB->query($_sql);
		if (!$_elementos)
		{
			return array();
		}

		foreach ($_elementos as $_el)
		{
			$this->elementos[] = grupo::newFromArray($_el);
		}
	}

	function elementoNaPosicao($_posElemento) { }

	function elementoDeCodigo($_idElemento) { }
	
	// se $_idPagina foi informado quando categoria:: foi instanciada, 
	// 		lê todos os grupos que não pertencem a esta página
	// senão, 
	//		lê todos os grupos que não pertencem a esta categoria
	function lerNaoElementos() 
	{ 
		$_sql = "SELECT DISTINCT g.*, 0 as idCategoria, 0 as idTipoGrupo, '' as descricaoTipoGrupo, 0 as posCategoria
				   FROM hp_grupos g, hp_gruposxcategorias gc
				  WHERE g.idGrupo not in (SELECT idGrupo FROM hp_gruposxcategorias where idCategoria = $this->idCategoria)
				  ORDER BY g.descricaoGrupo";

		$_naoElementos = $this->hpDB->query($_sql);

		foreach ($_naoElementos as $_nel)
		{
			$_grupo = grupo::newFromArray($_nel);
			$naoElementos[] = $_grupo->getArray();
		}

		return isset($naoElementos) ? $naoElementos : false ;
	} 

	function deslocarElementoParaCima($_idElemento) 
	{ 
		// calcula a posição do grupo anterior.
		$_sql = "SELECT posCategoria FROM hp_gruposxcategorias  WHERE idCategoria = $this->idCategoria AND idGrupo = $_idElemento";
		$_return = $this->hpDB->query($_sql);
		if (!$_return or count($_return) == 0) 
		{
			die ("Não há qualquer grupo na categoria : $this->idCategoria :  $this->descricaoCategoria com chave $_idElemento");
		}
		$_ProxPosCategoria = $_return[0]['posCategoria']-1;
		if ($_ProxPosCategoria > 0) {
			// desloca o grupo anterior para baixo (se ele não existir, não tem problema).
		    $_sql = "UPDATE hp_gruposxcategorias set posCategoria = posCategoria + 1 WHERE idCategoria = $this->idCategoria AND posCategoria = $_ProxPosCategoria";
			$_return = $this->hpDB->query($_sql);
		
			// desloca para cima o grupo solicitado...
			$_sql = "UPDATE hp_gruposxcategorias set posCategoria = $_ProxPosCategoria WHERE idCategoria = $this->idCategoria AND idGrupo = $_idElemento";
			$_return = $this->hpDB->query($_sql);
		}
		return $_return;
	}

	public function deslocarElementoParaBaixo($_idElemento)
	{
		// calcula a posição do elemento posterior.
		$_sql = "SELECT posCategoria FROM hp_gruposxcategorias WHERE idCategoria = $this->idCategoria AND idGrupo = $_idElemento";
		$_return = $this->hpDB->query($_sql);
		if (!$_return or count($_return) == 0) 
		{
			die ("Não há qualquer grupo da Categoria : $this->idCategoria :  $this->descricaoCategoria com chave $_idElemento");
		}
		$_ProxPosCategoria = $_return[0]['posCategoria']+1;

		// obtém o total de elementos deste grupo.
		$_sql = "SELECT Count(1) as numGrupos from hp_gruposxcategorias WHERE idCategoria = $this->idCategoria";
		$_return = $this->hpDB->query($_sql);

		if ($_return[0]['numGrupos'] >= $_ProxPosCategoria) {
			// desloca o elemento posterior para cima. (se ele não existir não tem problema)
		    $_sql = "UPDATE hp_gruposxcategorias set posCategoria = posCategoria - 1 WHERE idCategoria = $this->idCategoria AND posCategoria = $_ProxPosCategoria";
			$_return = $this->hpDB->query($_sql);

			// desloca para baixo o elemento solicitado.
			$_sql = "UPDATE hp_gruposxcategorias set posCategoria = $_ProxPosCategoria WHERE idCategoria = $this->idCategoria AND idGrupo = $_idElemento";
			$_return = $this->hpDB->query($_sql);
		}

		return $_return;
	}
		
	function incluirElemento($_idElemento, $_posElemento = 0) 
	{ 
		if ($_posElemento > 0)
		{
			$_sql = "insert into hp_gruposxcategorias (idGrupo, idCategoria, posCategoria)
					 values ($_idElemento, $this->idCategoria, $_posElemento)";
		}
		else
		{
			$_sql = "insert into hp_gruposxcategorias (idGrupo, idCategoria, posCategoria)
				select $_idElemento, $this->idCategoria, max(posCategoria)+1 from hp_gruposxcategorias where idCategoria = $this->idCategoria";
		}
		$result = $this->hpDB->query($_sql);
		if (!$result) 
		{
			die('erro ao inserir um grupo na categoria: ' . $this->idCategoria . ' : ' . $_idElemento);
		}
		else
		{
			return $this->hpDB->getLastInsertId();
		}
	}
	
	function excluirElemento($_idElemento) 
	{ 
		$_sql = "DELETE FROM hp_gruposxcategorias where idGrupo = $_idElemento and idCategoria = $this->idCategoria";
		
		return $this->hpDB->query($_sql);
	} 

	static function getCategorias()
	{
		// como esta função é um método de classe, não posso usar nenhuma variável de instância, apenas locais e globais.
		// desta forma, tenho que usar uma nova conexão para a base de dados, ainda que já haja uma aberta.
		global $global_hpDB;

		$_sql = "SELECT * FROM hp_categorias";
		$_categorias = $global_hpDB->query($_sql);
		if (!$_categorias) 
		{
			die('erro: não consegui ler nenhuma categoria!');
		}
		else
		{
			foreach ($_categorias as $_cat)
			{
				$categorias[] = array(
					'idCategoria' => $_cat['idCategoria'],
					'descricaoCategoria' => $_cat['descricaoCategoria'],
					'categoriaRestrita' => $_cat['categoriaRestrita'],
					'restricaoCategoria' => $_cat['restricaoCategoria']);
			}
		}
		return isset($categorias) ? $categorias : FALSE;
	}

	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_categorias";
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	

} /*}}} subPagina */

class pagina extends elementoAgrupado
{ /*{{{*/
	var $idPagina;
	var $tituloPagina;
	var $classPagina;
	var $tituloTabela;
	var $displayGoogle;
	var $displayFindaMap;
	var $displayFortune;
	var $displayImagemTitulo;
	var $displaySelectColor;

	public function __construct(int $_idPagina)
	{
		parent::elementoAgrupado();

		if (isset($_idPagina)) 
		{
			$line = $this->hpDB->query("SELECT * from hp_paginas where idPagina = $_idPagina");
			if (!$line)
			{
				die("idPagina incorreto: $_idPagina");
			}
			
			$this->tituloPagina = $line[0]['TituloPagina'];
			if (isset($_REQUEST['class']) && $_REQUEST['class'] != '') 
			{
				$this->classPagina = $_REQUEST['class'];
			}
			else
			{
				$this->classPagina = $line[0]['classPagina'];
			}
			$this->tituloTabela = $line[0]['TituloTabela'];
			$this->idPagina = $line[0]['idPagina'];
			$this->displayGoogle = $line[0]['displayGoogle'];
			$this->displayFindaMap = $line[0]['displayFindaMap'];
			$this->displayFortune = $line[0]['displayFortune'];
			$this->displayImagemTitulo = $line[0]['displayImagemTitulo'];
			$this->displaySelectColor = $line[0]['displaySelectColor'];
		}
	}

	public function inserir ()
	{

		$_sql = "INSERT INTO hp_paginas (tituloPagina, tituloTabela, classPagina, displayGoogle, displayFindaMap, displayFortune, displayImagemTitulo, displaySelectColor)
			VALUES ('$this->tituloPagina', '$this->tituloTabela' , '$this->classPagina', $this->displayGoogle, $this->displayFindaMap, $this->displayFortune, $this->displayImagemTitulo, $this->displaySelectColor)";

		// executa o query e resgata o id criado.
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar a página: " . $this->idPagina . ": " . $this->tituloPagina);
		}
		else
		{
			$this->idPagina = $this->hpDB->getLastInsertId();
			return $this->idPagina;
		}
	}
	
	public function atualizar ()
	{

		$_sql = "UPDATE hp_paginas SET tituloPagina = '$this->tituloPagina',
									 tituloTabela = '$this->tituloTabela',
									 classPagina = '$this->classPagina',
									 displayGoogle = $this->displayGoogle,
									 displayFindaMap = $this->displayFindaMap,
									 displayFortune = $this->displayFortune,
									 displayImagemTitulo = $this->displayImagemTitulo,
									 displaySelectColor = $this->displaySelectColor
				 WHERE idPagina = $this->idPagina";
		
		// executa o query
		$result = $this->hpDB->query($_sql);
		if (!$result)
		{
			die ("erro ao gravar a página: " . $this->idPagina . ": " . $this->tituloPagina);
		}
		else
		{
			return $this->hpDB->getAffectedRows();
		}
	}

	public function excluir() 
	{ 
		$result = true;

		$this->lerElementos();
		
		$result = $result and $this->hpDB->begin();

		foreach ($this->elementos as $elemento) {
			$result = $result and $this->excluirElemento($elemento->idCategoria);
		}

		$_sql = "delete from hp_paginas where idPagina = $this->idPagina";
		$result = $result and $this->hpDB->query($_sql);

		if ($result) {
			$result = $result and $this->hpDB->commit();
		}
		else
		{
			$result = $result and $this->hpDB->rollback();
		}

		return $result;
	}

	public function getArray() 
	{ 
		if (isset($this->idPagina))
		{
			return array(
				'idPagina' => $this->idPagina,
				'tituloPagina' => $this->tituloPagina,
				'tituloTabela' => $this->tituloTabela,
				'classPagina' => $this->classPagina,
				'displayGoogle' => $this->displayGoogle,
				'displayFindaMap' => $this->displayFindaMap,
				'displayFortune' => $this->displayFortune,
				'displayImagemTitulo' => $this->displayImagemTitulo,
				'displaySelectColor' => $this->displaySelectColor				);
		}
		else
		{
			die('erro em pagina::getArray(). Não inicializado!');
		}
	}
	
    public function getBigArray( )
    {
        if ( !isset( $this->idPagina ) )
        {
            die ( 'Não é possível ler esta página. ' );
        }

		// verifica se foi pedido algum grupo restrito
		// na stored proc chamada, eu faço um REGEXP com os grupos passados. aqui eu monto a expressão regular...
		if (isset($_REQUEST['gr'])) 
		{
			if (strpos($_REQUEST['gr'], 'all') !== false) 
			{
				$_grParm = ".*";
			}
			elseif ($_REQUEST['gr'] != '') // pediu alguns dos grupos restritos corretamente?
			{
				// sim, aceitando qualquer separador...
				$_grParm = str_replace(array(",", ".", ";", "+", "-"), "|", $_REQUEST['gr']);
				// limpa separadores repetidos no meio da string.
				while (strpos($_grParm, "||") !== FALSE) $_grParm = str_replace("||", "|", $_grParm);
			}
			else // não, coloca o default
			{
				$_grParm = "_";
			}
		}
		else // não pediu nenhum grupo restrito
		{
			$_grParm = "_";
		}

        $_sql = "select p.idPagina, c.idCategoria, c.descricaoCategoria, c.categoriaRestrita, c.restricaoCategoria, cp.posPagina,
                      g.idGrupo, g.idTipoGrupo, g.descricaoGrupo, g.grupoRestrito, g.restricaoGrupo, gc.posCategoria,
                      e.idElemento, e.descricaoElemento, e.idTipoElemento, e.posGrupo, e.urlElemento,
                      e.urlElementoLocal, e.dicaElemento, e.urlElementoTarget, e.formNome, e.formNomeCampo,
                      e.formTamanhoCampo, e.separadorBreakBefore, e.rssItemNum, e.templateFileName,
                      e.urlElementoSSL, e.urlElementoSVN
                 from hp_paginas p, hp_categoriasxpaginas cp, hp_categorias c,
                      hp_gruposxcategorias gc, hp_grupos g, hp_elementos e
                where p.idPagina = cp.idPagina
                  and cp.idCategoria = c.idCategoria
                  and c.idCategoria = gc.idCategoria
                  and ( (categoriaRestrita = 0)
                      or (categoriaRestrita = 1 and restricaoCategoria REGEXP '$_grParm' ) )
                  and gc.idGrupo = g.idGrupo
                  and g.idGrupo = e.idGrupo
                  and ( (g.grupoRestrito = 0)
                      or (g.grupoRestrito = 1 and g.restricaoGrupo REGEXP '$_grParm' ) )
                  and p.idPagina = " . $this->idPagina . "
                order by cp.posPagina, gc.posCategoria, e.posGrupo";

		$_elementos = $this->hpDB->query($_sql);
		if (!$_elementos)
		{
			return array();
		}
		#var_dump($_elementos);
		
		foreach ($_elementos as $_el)
		{
			$elementos[] = array( 
                   'idPagina' => $_el['idPagina'],
                   'idElemento' => $_el['idElemento'],
                   'idGrupo' => $_el['idGrupo'],
                   'idCategoria' => $_el['idCategoria'],
                   'descricaoCategoria' => $_el['descricaoCategoria'],
                   'posPagina' => $_el['posPagina'],
                   'idTipoGrupo' => $_el['idTipoGrupo'],
                   'descricaoGrupo' => $_el['descricaoGrupo'],
                   'posCategoria' => $_el['posCategoria'],
                   'descricaoElemento' => $_el['descricaoElemento'],
                   'idTipoElemento' => $_el['idTipoElemento'],
                   'posGrupo' => $_el['posGrupo'], 
                   'urlElemento' => $_el['urlElemento'],
                   'urlElementoLocal' => $_el['urlElementoLocal'], 
                   'dicaElemento' => $_el['dicaElemento'], 
                   'urlElementoTarget' => $_el['urlElementoTarget'], 
                   'formNome' => $_el['formNome'], 
                   'formNomeCampo' => $_el['formNomeCampo'],
                   'formTamanhoCampo' => $_el['formTamanhoCampo'], 
                   'separadorBreakBefore' => $_el['separadorBreakBefore'], 
                   'rssItemNum' => $_el['rssItemNum'], 
                   'templateFileName' => $_el['templateFileName'],
                   'urlElementoSSL' => $_el['urlElementoSSL'], 
                   'urlElementoSVN' => $_el['urlElementoSVN']
                   );
		}
        
        return isset( $elementos ) ? $elementos : array( ) ;
    }
        
    static function newFromArray($_array)
	{
	}
		
	function numeroElementos() { }

	public function lerElementos()
	{

		// verifica se foi pedido algum grupo restrito
		// na stored proc chamada, eu faço um REGEXP com os grupos passados. aqui eu monto a expressão regular...
		if (isset($_REQUEST['gr'])) 
		{
			if (strpos($_REQUEST['gr'], 'all') !== false) 
			{
				$_grParm = ".*";
			}
			elseif ($_REQUEST['gr'] != '') // pediu alguns dos grupos restritos corretamente?
			{
				// sim, aceitando qualquer separador...
				$_grParm = str_replace(array(",", ".", ";", "+", "-"), "|", $_REQUEST['gr']);
				// limpa separadores repetidos no meio da string.
				while (strpos($_grParm, "||") !== FALSE) $_grParm = str_replace("||", "|", $_grParm);
			}
			else // não, coloca o default
			{
				$_grParm = "_";
			}
		}
		else // não pediu nenhum grupo restrito
		{
			$_grParm = "_";
		}

		$_sql = "SELECT c.*, cp.*
       from hp_categorias c, hp_categoriasxpaginas cp
      where cp.idCategoria = c.idCategoria
        and cp.idPagina = $this->idPagina
        and ( (categoriaRestrita = 0)
           or (categoriaRestrita = 1 and restricaoCategoria REGEXP '$_grParm' ) )
     order by cp.PosPagina;";

		$_elementos = $this->hpDB->query($_sql);
		if (!$_elementos)
		{
			return array();
		}

		foreach ($_elementos as $_el)
		{
			$this->elementos[] = categoria::newFromArray($_el);
		}
	}

	function elementoNaPosicao($_posElemento) { }

	function elementoDeCodigo($_idElemento) { }
	
	public function lerNaoElementos()
	{
		$_sql = "SELECT DISTINCT cp.idCategoria
				   FROM hp_categoriasxpaginas cp
				  WHERE idPagina != $this->idPagina
				    AND cp.idCategoria not in (SELECT idCategoria FROM hp_categoriasxpaginas WHERE idPagina = $this->idPagina)";

		$_naoElementos = $this->hpDB->query($_sql);

		foreach ($_naoElementos as $_nel)
		{
			$_categoria = new categoria($_nel['idCategoria']);
			$naoElementos[] = $_categoria->getArray();
		}

		return isset($naoElementos) ? $naoElementos : false ;
	}

	function deslocarElementoParaCima($_idElemento)
	{
		// calcula a posição do elemento anterior.
		$_sql = "SELECT posPagina FROM hp_categoriasxpaginas WHERE idPagina = $this->idPagina AND idCategoria = $_idElemento";
		$_return = $this->hpDB->query($_sql);
		if (!$_return or count($_return) == 0) 
		{
			die ("Não há qualquer categoria da página : $this->idPagina :  $this->tituloPagina com chave $_idElemento");
		}
		$_ProxPosPagina = $_return[0]['posPagina']-1;

		if ($_ProxPosPagina > 0) {
			// desloca o elemento anterior para baixo. (se ele não existir não tem problema)
		    $_sql = "UPDATE hp_categoriasxpaginas set posPagina = posPagina + 1 WHERE idPagina = $this->idPagina AND posPagina = $_ProxPosPagina";
			$_return = $this->hpDB->query($_sql);

			// desloca para cima o elemento solicitado.
			$_sql = "UPDATE hp_categoriasxpaginas set posPagina = $_ProxPosPagina WHERE idPagina = $this->idPagina AND idCategoria = $_idElemento";
			$_return = $this->hpDB->query($_sql);
		}

		return $_return;
	}

	public function deslocarElementoParaBaixo($_idElemento)
	{
		// calcula a posição do elemento posterior.
		$_sql = "SELECT posPagina FROM hp_categoriasxpaginas WHERE idPagina = $this->idPagina AND idCategoria = $_idElemento";
		$_return = $this->hpDB->query($_sql);
		if (!$_return or count($_return) == 0) 
		{
			die ("Não há qualquer categoria da página : $this->idPagina :  $this->tituloPagina com chave $_idElemento");
		}
		$_ProxPosPagina = $_return[0]['posPagina']+1;

		// obtém o total de elementos deste grupo.
		$_sql = "SELECT Count(1) as numCategorias from hp_categoriasxpaginas WHERE idPagina = $this->idPagina";
		$_return = $this->hpDB->query($_sql);

		if ($_return[0]['numCategorias'] >= $_ProxPosPagina) {
			// desloca o elemento posterior para cima. (se ele não existir não tem problema)
		    $_sql = "UPDATE hp_categoriasxpaginas set posPagina = posPagina - 1 WHERE idPagina = $this->idPagina AND posPagina = $_ProxPosPagina";
			$_return = $this->hpDB->query($_sql);

			// desloca para baixo o elemento solicitado.
			$_sql = "UPDATE hp_categoriasxpaginas set posPagina = $_ProxPosPagina WHERE idPagina = $this->idPagina AND idCategoria = $_idElemento";
			$_return = $this->hpDB->query($_sql);
		}

		return $_return;
	}

	public function incluirElemento($_idElemento, $_posElemento = 0) 
	{ 
		if ($_posElemento > 0)
		{
			$_sql = "insert into hp_categoriasxpaginas (idCategoria, idPagina, posPagina)
					 values ($_idElemento, $this->idPagina, $_posElemento)";
		}
		else
		{
			$_sql = "insert into hp_categoriasxpaginas (idCategoria, idPagina, posPagina)
				select $_idElemento, $this->idPagina, max(posPagina)+1 from hp_categoriasxpaginas where idPagina = $this->idPagina";
		}
		$result = $this->hpDB->query($_sql);
		if (!$result) 
		{
			die('erro ao inserir elemento na página: ' . $this->idPagina . ' : ' . $_idElemento);
		}
		else
		{
			return $this->hpDB->getLastInsertId();
		}
	}
	
	public function excluirElemento($_idElemento) 
	{ 
		$_sql = "DELETE FROM hp_categoriasxpaginas where idCategoria = $_idElemento and idPagina = $this->idPagina";
		
		return $this->hpDB->query($_sql);
	} 

	static function getPaginas()
	{
		// como esta função é um método de classe, não posso usar nenhuma variável de instância, apenas locais e globais.
		// desta forma, tenho que usar uma nova conexão para a base de dados, ainda que já haja uma aberta.
		global $global_hpDB;

		$_sql = "SELECT idPagina FROM hp_paginas";
		$_paginas = $global_hpDB->query($_sql);
		if (!$_paginas) 
		{
			die('erro: não consegui ler nenhuma página!');
		}
		else
		{
			foreach ($_paginas as $_pag)
			{
				$_pagina = new pagina($_pag['idPagina']);
				$paginas[] = $_pagina->getArray();
			}
		}
		return isset($paginas) ? $paginas : FALSE;
	}

	static function getCount()
	{
		global $global_hpDB;

		$_sql = "SELECT COUNT(*) as numElementos FROM hp_paginas";
		$_count = $global_hpDB->query($_sql);
		if (!$_count) 
		{
			return 0;
		}
		else
		{
			return $_count[0]['numElementos'];
		}
	}
	
} /*}}} pagina */

//-- vi: set tabstop=4  shiftwidth=4 showmatch nowrap:

?>

