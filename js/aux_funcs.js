var valorCaixaTexto;

function trim(str)
{
	 return str.replace(/^\s*|\s*$/g,"");
}

function trimPunct(str)
{
	 str = str.replace(";", "");
	 str = str.replace(",", "");
	 str = str.replace(":", "");
	 return str;
}

function getSel()
{
	var txt = '';
	if (window.getSelection)
	{
		txt = window.getSelection();
	}
	else if (document.getSelection)
	{
		txt = document.getSelection();
	}
	else if (document.selection)
	{
		txt = document.selection.createRange().text;
	}
	else return;
	
	txt = trim(txt);

	return txt;
}


function GoLink(url)
{
	// # ManuelC: 2004-06-07: Verfica se o URL é para outro ponto na Internet ou se é para o próprio site.
	//# Não é a maneira mais elegante de o fazer, mas é a que envolve menos esforço de correcção...
	if (url.indexOf("http://www.") != 0) {
		document.areas.action = url;
		document.areas.__VIEWSTATE.name = 'NOTVIEWSTATE';
		document.areas.submit();
	} else {
		window.open(url, '', '');
	}
}

function GetSeachValue()
{
	if(document.areas.h_texto_pesquisa.value != null && document.areas.h_texto_pesquisa.value != "")
	{
		return document.areas.Dicionario_palVisible.value = document.areas.h_texto_pesquisa.value;
	}
	else
		return "";
		
}

	function Imprimir(){
		
		var i, conteudo, divConteudo;
		if (navigator.platform == "Win32") {
			if(navigator.appName=="Netscape") {
				
				window.print();
			} else {
				conteudo = document.body.innerHTML;
				divConteudo	= document.getElementById("conteudo").innerHTML;					
				document.body.innerHTML = divConteudo;
				window.print();
				document.body.innerHTML = conteudo;
			}
		} else {
			alert("Para imprimir seleccione 'File > Print...' do menu de topo.");
		}	
	}

	function SendToAFriend(pageUrl)
	{
		var QueryString;
		
		if(document.areas != undefined && document.areas.pal != null)
		{
			if (document.areas.pal != null)
			{
				// procura efectuada
				if(document.areas.pal.value != null && document.areas.pal.value != "")
				{
					// url
					QueryString = '?pageUrl=' + pageUrl;

					// forma
					if(document.areas.h_n.value != null && document.areas.h_n.value != 0)
					{
						QueryString += '&h_n=' + document.areas.h_n.value;
					}
				}
				else
					QueryString = '?pageUrl=' + pageUrl + '&pal=' + document.areas.pal.value;
			}
			else
				QueryString = '?pageUrl=' + pageUrl;
		} 
		else
				QueryString = '?pageUrl=' + pageUrl;
		
		//window.open('/SendToAFriend.aspx?pageUrl=' + pageUrl,'SendToAFriend','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=350');	
		window.open('/SendToAFriend.aspx' + QueryString,'SendToAFriend','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=350');		
	}


	// função para capturar o enter da página e a submeter
	function AccaoDoEnter(e, objValue)
	{	
		var key;
		var keychar;
		
		//alert("AccaoDoEnter: " + objValue.value + " - " + e);
		
		if (navigator.appName == "Netscape") {
			key = e.which;

			//BUG: Netscape 6 chama isto com o backspace
			if (navigator.appVersion.indexOf("5") != 0) {
				if (key == 0) {
					key = 13;
				}
			}
		} else {
			key = e.keyCode;
		}
		
		/*
		hugos: Código original
		if(window.event || !e.which) // IE
		{
			key = e.keyCode; // for IE, same as window.event.keyCode
		}
		else if(e) // netscape
		{
			key = e.which;
		}
		else
		{
			return false;
		}
		*/
		//alert ("Key: " + key);
		if(key == 13) {
			SeleccionaEntrada(objValue.value, "0");
		}
								
	}

	function AccaoDaSeta()
	{
		//alert("AccaoDaSeta");
		if (document.areas.Dicionario_palVisible.value != null && document.areas.Dicionario_palVisible.value != "") 		
		{		
			// SeleccionaAccao('definir');			
			SeleccionaEntrada(document.areas.Dicionario_palVisible.value, "0");
		}
		else
		{	
			return false;
		}
	}	
	
	// função para popular input escondida com a string a procurar, antes de submeter
	
	function SearchKey()
	{		
		document.areas.pal.value;						
	}
	
	function ObtemDicionario()
	{
		
	  // obtém nome da página
	  var sPathO = window.location.pathname;	
//	  if(sPathO.indexOf('/PRIBERAM_2003_01')	!= -1)
//	  {
//		sPathO = sPathO.replace('/PRIBERAM_2003_01','')
//	  }
	  var sPageO = sPathO.substring(sPathO.lastIndexOf(')') + 2);	  	  	  	  	  	  	  	  
	  sPageO = sPageO.substring(0,sPageO.indexOf('/'));
			  	  	  
	  // devolve
	  return sPageO;
	
	}


	function SeleccionaAccao (accao)
	{	
	  
	  
	  
	  //alert(document.getElementById("__VIEWSTATE").name)// = 'NOTVIEWSTATE';
	  document.areas.__VIEWSTATE.name = 'NOTVIEWSTATE';
		
		// obtem dicionário
	  var sPage = ObtemDicionario();	  

	  //alert("SELECCIONA ACCAO:" + accao);
	  // document.areas.accao.value = accao;	  
	  switch (accao) {
		case 'pesquisar': 
			// DEBUG CODE
			// alert(document.areas.pesqActionTarget.value);
			switch (sPage)
			{
				case 'dlpo':				
					if(document.areas.pal.value != null && document.areas.pal.value != "")
					{
						document.areas.action = document.areas.pesqActionTarget.value;// + '?pal=' + document.areas.pal.value;
					}
					else
					{
						document.areas.action = document.areas.pesqActionTarget.value;// + '?pal=' + document.areas.pal.value;
					}
					break;
				case 'dcvpo':
					if(document.areas.pal.value != null && document.areas.pal.value != "")
					{					
						document.areas.action = document.areas.CVpesqActionTarget.value;// + '?pal=' + document.areas.pal.value;
					}
					else
					{
						document.areas.action = document.areas.CVpesqActionTarget.value;//  + '?pal=' + document.areas.pal.value;
					}
					break;					
			}			
			break;
		case 'definir':
			// DEBUG CODE
			// 
			//alert(document.areas.definActionTarget.value);
			//alert ("sPage: " + sPage);
			
			switch (sPage)
			{
				case 'dlpo':					
					if(document.areas.h_n.value != null && document.areas.h_n.value != "")
						{document.areas.action = document.areas.definActionTarget.value;}// + '?pal=' + document.areas.pal.value + '&id=' + document.areas.h_n.value;}
					else								
						{document.areas.action = document.areas.definActionTarget.value;}// + '?pal=' + document.areas.pal.value;}
					break;
				case 'dcvpo':					
					{document.areas.action = document.areas.CVdefinActionTarget.value;}// + '?pal=' + document.areas.pal.value;
					break;			
			}
			break;
		case 'conjugar':
		
			// DEBUG CODE
			// alert(document.areas.definActionTarget.value);
			switch (sPage)
			{
				case 'dlpo':			
					document.areas.action = document.areas.conjugaActionTarget.value;
					break;
				case 'dcvpo':		
					document.areas.action = document.areas.CVconjugaActionTarget.value;
					break;
			}
			break;
			
		case 'guardar_preferencias':
			//Hugos: 07/06/2004
			valorCaixaTexto = document.areas.Dicionario_palVisible.value;

			ActualizaPreferencias();
			document.areas.hdn_guardar.value='ok';
			break;
			


		case 'aplicar_restricao':
			//Hugos: 07/06/2004
			valorCaixaTexto = document.areas.Dicionario_palVisible.value;
			
			ActualizaParamsFiltro('on');
			// document.areas.hdn_guardar.value='ok';
			//alert('Aqui');
			break;

		case 'eliminar_restricao':
			//Hugos: 07/06/2004
			valorCaixaTexto = document.areas.Dicionario_palVisible.value;
			
			ActualizaParamsFiltro('off');
			// document.areas.hdn_guardar.value='ok';
			//alert('Aqui');
			break;


		default:
			{
				document.areas.accao.value = accao;
				break;
			}
		break;						
	  }
	
		document.areas.h_texto_pesquisa.value = valorCaixaTexto; //document.areas.Dicionario_palVisible.value
		 
		document.areas.__VIEWSTATE.name = 'NOTVIEWSTATE';

		document.areas.submit();
		
	}

	function SetDefaultAction (accao)
	{
	  if (document.areas.accao.value == '')
		{document.areas.accao.value = accao;}
	}

	function SeleccionaEntrada_v2 (palavra, n)
	{
		var word = palavra.split(" ");
		
		if (word.length!=1)
			return;
			
		palavra =  trimPunct(word[0]);
		
		if(palavra=="")
			return;		
			
		SeleccionaEntrada(palavra, n)
	}

	function SeleccionaEntrada (palavra, n)
	{
		valorCaixaTexto = palavra;
		//document.areas.Dicionario_palVisible.value = palavra;
		document.areas.pal.value = palavra;
		document.areas.h_n.value = n;
		SeleccionaAccao('definir');
	}

	function PesquisaExpressao (expressao)
	{
		valorCaixaTexto=expressao;
		document.areas.pal.value = expressao;
		SeleccionaAccao('pesquisar');
	}

	function ConjugaVerbo (verbo)
	{
		valorCaixaTexto = verbo;
		document.areas.pal.value = verbo;
		
		SeleccionaAccao('conjugar');
	}

/*
	function CondicionaCheckBox(field, subfield, i)
	{
		if ((i==1) && (field.checked == false))
			{subfield.checked = false;}
		else {
		if ((i==2) && (subfield.checked == true))
			{field.checked = true;}
		}
	}
*/

	function SetKeyWork(strPal)
	{
		if(strPal != null && strPal != "")
		{
			document.areas.pal.value = strPal;
		}
		
	}



	function SeleccionaPreferencias(vista, abrev_cat, abrev_flex, abrev_dom, abrev_exemp, abrev_etim, abrev_outras)
	{
		if (vista=='avancada')
			{document.areas.vista[0].checked = 1}
		else {
			if (vista=='reduzida')
				{document.areas.vista[1].checked = 1}
		}

		if (abrev_cat=='1')
			{document.areas.abrev_cat.checked = "true";}
		if (abrev_flex=='1')
			{document.areas.abrev_flex.checked = "true";}
		if (abrev_dom=='1')
			{document.areas.abrev_dom.checked = "true";}
		if (abrev_exemp=='1')
			{document.areas.abrev_exemp.checked = "true";}
		if (abrev_etim=='1')
			{document.areas.abrev_etim.checked = "true";}
		if (abrev_outras=='1')
			{document.areas.abrev_outras.checked = "true";}
	}

	function SeleccionaOpcoesFiltro()
	{
		var i;
		
		var dominio = document.areas.h_dominio.value;
		var var_geografica = document.areas.h_var_geografica.value;
		var categoria = document.areas.h_categoria.value;
		var reg_linguistico = document.areas.h_reg_linguistico.value;
		var etimologia = document.areas.h_etimologia.value;
		
		for(i=0; i<document.areas.dominio.length; i++)
			if(document.areas.dominio.options[i].value == dominio)
			{
				document.areas.dominio.options[i].selected = true;
				break;
			}
				
		for(i=0; i<document.areas.var_geografica.length; i++)
			if(document.areas.var_geografica.options[i].value == var_geografica)
			{
				document.areas.var_geografica.options[i].selected = true;
				break;
			}
			
		for(i=0; i<document.areas.categoria.length; i++)
			if(document.areas.categoria.options[i].value == categoria)
			{
				document.areas.categoria.options[i].selected = true;
				break;
			}
			
		for(i=0; i<document.areas.reg_linguistico.length; i++)
			if(document.areas.reg_linguistico.options[i].value == reg_linguistico)
			{
				document.areas.reg_linguistico.options[i].selected = true;
				break;
			}
			
		for(i=0; i<document.areas.etimologia.length; i++)
			if(document.areas.etimologia.options[i].value == etimologia)
			{
				document.areas.etimologia.options[i].selected = true;
				break;
			}			
	}


	function ActualizaPreferencias()
	{
		if (document.areas.vista[0].checked == 1)
			{document.areas.h_vista.value = "avancada";}
		else
			{document.areas.h_vista.value = "reduzida";}

		if (document.areas.abrev_cat.checked)
			document.areas.h_abrev_cat.value = "1";
		else
			document.areas.h_abrev_cat.value = "0";
			
		if (document.areas.abrev_flex.checked)
			document.areas.h_abrev_flex.value = "1";
		else
			document.areas.h_abrev_flex.value = "0";
	
		if (document.areas.abrev_dom.checked)
			document.areas.h_abrev_dom.value = "1";
		else
			document.areas.h_abrev_dom.value = "0";
			
		if (document.areas.abrev_exemp.checked)
			document.areas.h_abrev_exemp.value = "1";
		else
			document.areas.h_abrev_exemp.value = "0";
		
		if (document.areas.abrev_etim.checked)
			document.areas.h_abrev_etim.value = "1";
		else
			document.areas.h_abrev_etim.value = "0";
		
		if (document.areas.abrev_outras.checked)
			document.areas.h_abrev_outras.value = "1";
		else
			document.areas.h_abrev_outras.value = "0";
	}
	
	function ActualizaControlos()
	{		
		if (document.areas.h_vista.value == "avancada")
			{document.areas.vista[0].checked = 1;}
		else
			{document.areas.vista[1].checked = 1;}
			
		if (document.areas.h_abrev_cat.value == "1")			
			document.areas.abrev_cat.checked = 1;
		else
			document.areas.abrev_cat.checked = 0;
												
		if (document.areas.h_abrev_flex.value == "1")
			document.areas.abrev_flex.checked = 1;
		else
			document.areas.abrev_flex.checked = 0;
							
		if (document.areas.h_abrev_dom.value == "1")
			document.areas.abrev_dom.checked = 1;
		else
			document.areas.abrev_dom.checked = 0;
															
		if (document.areas.h_abrev_exemp.value == "1")			
			document.areas.abrev_exemp.checked = 1;
		else
			document.areas.abrev_exemp.checked = 0;
									
		if (document.areas.h_abrev_etim.value == "1")
			document.areas.abrev_etim.checked = 1;
		else
			document.areas.abrev_etim.checked = 0;
					
		if (document.areas.h_abrev_outras.value == "1")			
			document.areas.abrev_outras.checked = 1;
		else
			document.areas.abrev_outras.checked = 0;
	}	
		
	function ActualizaParamsFiltro(modo)
	{
		document.areas.h_filtro.value = modo;
		
		if (modo=='off')
		{
			document.areas.h_dominio.value = ""
			document.areas.h_desc_dominio.value = "";
			document.areas.h_var_geografica.value = "";
			document.areas.h_desc_var_geografica.value = "";	
			document.areas.h_categoria.value = "";
			document.areas.h_desc_categoria.value = "";
			document.areas.h_reg_linguistico.value = "";
			document.areas.h_desc_reg_linguistico.value = "";
			document.areas.h_etimologia.value = "";
			document.areas.h_desc_etimologia.value = "";		
		
		}
		
		else
		
		{
			var i;
			i = document.areas.dominio.selectedIndex;
			if (i!=0)
			{
				document.areas.h_dominio.value = document.areas.dominio.options[i].value;
				document.areas.h_desc_dominio.value = document.areas.dominio.options[i].text;
			}
			else
			{
				document.areas.h_dominio.value = "";
				document.areas.h_desc_dominio.value = "";
			}
		
			i = document.areas.var_geografica.selectedIndex;
			if (i!=0)
			{
				document.areas.h_var_geografica.value = document.areas.var_geografica.options[i].value;
				document.areas.h_desc_var_geografica.value = document.areas.var_geografica.options[i].text;
			}
			else
			{
				document.areas.h_var_geografica.value = "";
				document.areas.h_desc_var_geografica.value = "";	
			}

			i = document.areas.categoria.selectedIndex;
			if (i!=0)
			{
				document.areas.h_categoria.value = document.areas.categoria.options[i].value;
				document.areas.h_desc_categoria.value = document.areas.categoria.options[i].text;
			}
			else
			{
				document.areas.h_categoria.value = "";
				document.areas.h_desc_categoria.value = "";
			}
				
			i = document.areas.reg_linguistico.selectedIndex;
			if (i!=0)
			{
				document.areas.h_reg_linguistico.value = document.areas.reg_linguistico.options[i].value;
				document.areas.h_desc_reg_linguistico.value = document.areas.reg_linguistico.options[i].text;
			}
			else
			{
				document.areas.h_reg_linguistico.value = "";
				document.areas.h_desc_reg_linguistico.value = "";
			}
				
			i = document.areas.etimologia.selectedIndex;
			if (i!=0)
			{
				document.areas.h_etimologia.value = document.areas.etimologia.options[i].value;
				document.areas.h_desc_etimologia.value = document.areas.etimologia.options[i].text;
			}
			else
			{
				document.areas.h_etimologia.value = "";
				document.areas.h_desc_etimologia.value = "";		
			}
		}
	}


	function toggleSection(secId)
	{
		if (secId.style.display=="none"){secId.style.display=""}
		else{secId.style.display="none"}
	}


	function MostraSeccao(seccao, index)
	{	
		// obtem dicionário
		var sPage = ObtemDicionario();	
	
	    // obtem dicionário
	    var sDicionario = ObtemDicionario();
	  
		sPage = '/' + sPage;
						
		var targetUrl = '';		
						
		//alert("MOSTRA SECCAO:" + seccao + "," + index);
		switch (index) {
			case 0:				
				// Raíz da Ajuda				
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda.aspx';
						break;					
				}
				break;	
			case 1:				
				// Ajuda Intro
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_intro.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_intro.aspx';
						break;					
				}
				break;							
			case 2:
				// Barra de comandos			
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_barracomandos.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_barracomandos.aspx';
						break;					
				}
				break;				
			case 3:
				// Visualização
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_visualizacao.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_visualizacao.aspx';
						break;	
				}
				break;			
			case 4:
				// Definir
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_definir.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_pesquisar.aspx';
						break;	
				}
				break;				
			case 5:
				// Restringir
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_restringir.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_reversa.aspx';
						break;	
				}
				break;				
			case 6:
				// Exemplos
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_exemplos.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_atraves.aspx';
						break;	
				}
				break;				
			case 7:
				// Abreviaturas
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_abreviaturas.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_exemplos.aspx';
						break;	
				}
				break;				
			case 8:
				// Conjugação de Verbos
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_conjugacaoverbos.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_abreviaturas.aspx';
						break;	
				}
				break;				
			case 9:
				// Preferências
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_preferencias.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_gramatica.aspx';
						break;	
				}
				break;				
			case 10:
				// Gramática
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_gramatica.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_apartirdasuapag.aspx';
						break;	
				}
				break;				
			case 12:
				// Teclados Não Portugueses
				targetUrl = sPage + '/ajuda/ajuda_tecladosnport.aspx';
				break;
				/*
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_tecladosnport.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_tecladosnport.aspx';
						break;	
				}
				*/				
			case 13:
				// Procurar a partir da sua página
				targetUrl = sPage + '/ajuda/ajuda_apartirdasuapag.aspx';
				break;
				/*
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_apartirdasuapag.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_apartirdasuapag.aspx';
						break;	
				}
				*/				
			case 22:
				// Pesquisar
				targetUrl = sPage + '/ajuda/ajuda_pesquisar.aspx';
				break;
				/*
				switch(sDicionario)				
				{
					case 'dlpo':
						targetUrl = sPage + '/ajuda/ajuda_pesquisar.aspx';
						break;					
					case 'dcvpo':
						targetUrl = sPage + '/ajuda/ajuda_pesquisar.aspx';
						break;	
				}
				*/				
			default:
				;	
		}
		
		//alert(targetUrl);
		location.href= targetUrl;
		
		// ManuelC: Não sei para que serve esta linha...
		//document.forms[0].h_seccao_index.value = index;
	}
