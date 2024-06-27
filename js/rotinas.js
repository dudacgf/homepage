function getParameters() {
	var onlyParams, posParams, parsedParams, tuplaKeyValue;
	var s = document.URL;
	posParams = s.search(/\?/);
	document.className = '';
	document.local = '';
	if (posParams != -1) {
		onlyParams = s.substr(posParams+1);
		parsedParams = onlyParams.split(/\&/);
		for (i = 0; i < parsedParams.length; i = i + 1) {
			tuplaKeyValue = parsedParams[i].split(/\=/);
			switch (tuplaKeyValue[0]) {
				case 'w':
				case 'l':
				case 'local':
				case 'location':
					document.local = tuplaKeyValue[1];
					break;
				case 'c':
				case 'cor':
				case 'color':
					document.className = tuplaKeyValue[1];
					break;
			}
		}			
	}
}

function gogoogle(pressed) {
	switch (pressed) {
		case 'search':
			document.gg.action ="http://www.google.com/search"; break;
		case 'I\'m Feeling lucky':
			document.gg.action ="http://www.google.com/search&btnI=I\'m\ Feeling Lucky"; break;
		case 'Images':
			document.gg.action ="http://images.google.com/images?hl=pt-BR&lr=&sa=N&tab=wi"; break
		case 'News': 
			document.gg.action ="http://news.google.com/news"; break;
		case 'Music':	
			document.gg.action ="http://www.google.com/musicsearch";
	}
	document.gg.submit();
}

function pesq_isbn() {

	theIsbn = document.formisbn.isbn.value;

	// monta a url da fnac e a executa em uma nova janela...
	fnacUrl = 'http://www.fnac.com.br/busca/BuscaR.aspx?tpP=1&strISBN=' + theIsbn;
	window.open(fnacUrl);

	// monta a url da saraiva e a executa em uma nova janela...
	saraivaUrl = 'http://www.livrariasaraiva.com.br/pesquisaweb/pesquisaweb.dll/pesquisa?'
	saraivaUrl = saraivaUrl + 'FILTRON1=G&ESTRUTN1=0301&PALAVRASN1=' + theIsbn;
	window.open(saraivaUrl);

	// monta a url da cultura e a executa em uma nova janela...
	culturaUrl = 'http://www.livrariacultura.com.br/scripts/cultura/catalogo/busca.asp?'
	culturaUrl = culturaUrl + 'tipo_pesq=isbn&palavra=' + theIsbn;
	window.open(culturaUrl);

	// monta a url da sodiler e a executa em uma nova janela...
	sodilerUrl = 'http://www.sodiler.com.br/resultados-isbn.cfm?OrdemResults=DESCR&isbn=' + theIsbn;
	window.open(sodilerUrl);

	// monta a url para pesquisa de livros de informática...
	infoUrl = 'http://www.livrosdeinformatica.com.br/defaultlivros.asp?Origem=Pesquisa&';
	infoUrl = infoUrl + 'Pesquisar=Ok&TipoPesquisa=ISBN&PalavraChave=' + theIsbn;
	window.open(infoUrl);
}

function pesqCD() {
	theCD = document.formscds.kw.value;

	americanasURL = 'http://busca.americanas.com.br/procura.asp?id=7246&raiz=580&kw=' + escape(theCD);
	window.open(americanasURL);

	submarinoURL = 'http://www.submarino.com.br/cds_searchresults.asp?';
	submarinoURL += 'Query=ProductPage&ProdTypeId=2&WhichForm=frmSearchHomePage';
	submarinoURL += '&search=' + escape(theCD);
	window.open(submarinoURL);

	somLivreURL = 'http://busca.somlivre.globo.com/somlivrebusca/layout2004/index.cfm?&opcao=CD-ARTISTA&login=';
	somLivreURL +='&textobusca='+ theCD;
	window.open(somLivreURL);

	fnacUrl = 'http://www.fnac.com.br/busca/BuscaR.aspx?tpP=2&strCtBusca=1&strQuery=' + escape(theCD);
	window.open(fnacUrl);
}

function pesqVirus() {
	theVirus = document.formsvirus.kw.value;

	mcafeeURL = 'http://vil.nai.com/vil/alphar.asp?SearchType=2&char=' + escape(theVirus);
	window.open(mcafeeURL);

	avgURL = 'http://www.grisoft.com/doc/62/lng/us/tpl/tpl01?&nam=' + escape(theVirus);
	window.open(avgURL);

	sophosURL = 'http://www.sophos.com/search/?virus_search=1&terms=' + escape(theVirus);
	window.open(sophosURL);

	trendURL = 'http://www.trendmicro.com/vinfo/virusencyclo/default2.asp?m=q&virus=' + escape(theVirus);
	window.open(trendURL);

	virusListURL = 'http://www.viruslist.com/en/find?search_mode=virus&words=' + escape(theVirus);
	window.open(viruslistURL);
}

function changeClass(that, classe) {
	if ( classe != '') {
		s = window.navigator.userAgent;
		if (s.search(/MSIE/) > 0) {
			that.setAttribute("className", classe);
		} else {
			that.setAttribute("class", classe);
		}	
	}
}
 
function toggleClass(thisElement, umaClass, outraClass) {
	var classeAtual, classeAMudar;
	var s = window.navigator.userAgent;
	if (s.search(/MSIE/) > 0) {
		classeAtual = document.getElementById(thisElement).getAttribute('className');
	} 
	else {
		classeAtual = document.getElementById(thisElement).getAttribute('class');
	} ;
	classeAMudar = (classeAtual == umaClass)? outraClass : umaClass;
	if (s.search(/MSIE/) > 0) {
		document.getElementById(thisElement).setAttribute("className", classeAMudar);
	}
	else {
		document.getElementById(thisElement).setAttribute("class", classeAMudar);
	} ;
}

// 
// cria e envia um request para processamento dinâmico coloca o retorno no innerHTML de um elemento na página
//
// recebe: url - url para o XMLHttpRequest
// 		   idElemento - elemento que receberá, em seu innerHTML, o retorno do processamento da url
//
function xhttpInnerHtml(url, idElemento) { 
	if (url.substring(0, 4) != 'http') {
			url = document.location.protocol + "//" + document.location.host + url;
	}

	var element = document.getElementById(idElemento); 
	element.innerHTML = 'wait ...'; 
	
	var xmlhttp=false;
	if (typeof XMLHttpRequest!='undefined') {
		try {
			xmlhttp = new XMLHttpRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	if (!xmlhttp && window.createRequest) {
		try {
			xmlhttp = window.createRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	if (!xmlhttp) {
		return false;
	}
	
	xmlhttp.open("GET", url); 
	xmlhttp.onreadystatechange = function() { 
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { 
			var rt = xmlhttp.responseText; 
			rt = rt.replace(/\n/, '');
			rt = rt.replace(/\r/, '');
			element.innerHTML = rt; 
		}
	} 
	xmlhttp.send(null); 
} 

// adiciona um novo grupo ou categoria restrita à pagina atual
//
function onemoreGR(grAadicionar) {
	url = document.location + '.' + grAadicionar;
	document.location = url;
}

// vim: set shiftwidth=4 tabstop=4 showmatch:
