/*----------------------------------------------------------

  cores.js
  (c) ecgf - 2005

  Rotinas para defini��o de cor de um ou mais elementos dado seu id ou sua classe.

  note que este script depende fortemente dos estilos 
  definidos em $hp_homepage_path . estilos.css

----------------------------------------------------------*/

var elTitulo = 0;
var elColorStyle = 1;
var elSearchCriteria = 2;
var elSearchTerm = 3;
var elCookie = 4;
var elValorCor = 5;

function hexdec(f1) {
	f1 = f1.toUpperCase();
	rval = parseInt(f1,16);
	return rval;
}

function RGBColor(valorCor)
{
	this.ok = false;
	if (valorCor.substr(0, 1) == '#') {
		try {
			this.r = hexdec(valorCor.substr(1, 2));
			this.g = hexdec(valorCor.substr(3, 2));
			this.b = hexdec(valorCor.substr(5, 2));
			this.ok = true;
		} catch (err) {
			this.ok = false;
		}
	}
}

/*
 ** esta fun��o � a que realmente realiza as trocas de cores dos elementos...
 **
*/
function alterarCorAtributo(theObj, aColorAtribute, aColor) {
  var acType, acs = aColorAtribute.split(";"), k;
  for (k = 0; k < acs.length; k++) {
    acType = acs[k];
    switch (acType.toLowerCase()) {
      case 'color':
        theObj.style.color = aColor;
        break;
      case 'bcolor':
        theObj.style.backgroundColor = aColor;
        break;
      case 'tborder':
        theObj.style.borderTopColor = aColor ; 
        break;
      case 'lborder':
        theObj.style.borderLeftColor = aColor ; 
        break;
      case 'bborder':
        theObj.style.borderBottomColor = aColor ; 
        break;
      case 'rborder':
        theObj.style.borderRightColor = aColor ; 
        break;
	  case 'bimg':
		if (aColor.substr(0, 1) == '#') {
			var color = new RGBColor(aColor);
			if (color.ok) { // 'ok' is true when the parsing was a success
				theObj.style.backgroundImage = 'url("../drawing/background.php?r=' + color.r + '&g=' + color.g + '&b=' + color.b + '")'
			}
		} else {
			theObj.style.backgroundImage = aColor;
		}
    }
  }
}

/*
 **
 ** Esta fun��o altera a cor de um elemento dado seu id
 **
*/
function alteraCorElementoPorId(theId, aColorAtribute, aColor) {
  var obj = document.getElementById(theId);
  alterarCorAtributo(obj, aColorAtribute, aColor);
}

/*
 **
 **  Esta fun��o executa a troca de cores de uma classe dado seu selectorText
 **
 **  Aqui, theSelectorText � o seletor de uma classe, p.ex "div.fortune".
 **
*/
function alterarCorElementoPorClasse(theSelectorText, aColorAtribute, aCor) {
  var ua = window.navigator.userAgent;
  if ( ua.search(/MSIE/) > 0) {
    bodyclass = document.body.getAttribute("className");
   } else {
    bodyclass = document.body.getAttribute("class");
  }
  for (i = 0; i < document.styleSheets.length; i++) {
    var ss = document.styleSheets[i];
    var j, sr, sslength, bodyclass, srtext, srSelectors = new Array();
    if ( ua.search(/MSIE/) > 0) {
      sslength = ss.rules.length; 
     } else {
      sslength = ss.cssRules.length;
    }
    var st = (bodyclass != "" ? "body." + bodyclass + " " : "") +  theSelectorText.toLowerCase();
    for (j = 0; j < sslength; j++) {
      if ( ua.search(/MSIE/) > 0) {
        sr = ss.rules.item(j);
      } else {
        sr = ss.cssRules[j];
      }
      srtext = sr.selectorText.toLowerCase();
      srSelectors = srtext.split(",");
      for (k = 0; k < srSelectors.length; k++) {
        if ( srSelectors[k] == st ) {
          alterarCorAtributo(sr, aColorAtribute, aCor);
          break;
        }
      }
    }
  }
}

/*
**
**  esta fun��o executa a troca de cores de um ou mais elementos
**  identificados por um ou mais atributos Id.  Os Ids devem estar
**  separados por ';' (ponto-e-v�rgula).
**
*/
function alterarCorElementosPorId(theId, aColorAtribute, aColor) {
  var theIds = new Array(), i;
  theIds = theId.split(';');
  for (i = 0 ; i < theIds.length; i++) {
    alteraCorElementoPorId(theIds[i], aColorAtribute, aColor);
  }
}

/*
**
** esta fun��o executa a troca de cores de um ou mais elementos coloridos
** identificados por um ou mais 'class selectors' (div.x etc).  Os elementos devem
** estar separados por ';' 
**
*/
function alterarCorElementosPorClasse(theSelector, aColorAtribute, aColor) {
  var theSelectors = new Array(), i;
  theSelectors = theSelector.split(';');
  for (i = 0 ; i < theSelectors.length; i++) {
    alterarCorElementoPorClasse(theSelectors[i], aColorAtribute, aColor);
  }
}

function alterarCorElemento(oElemento, aCor) {
	switch (oElemento[elSearchCriteria]) {
		case 'id':
			alterarCorElementosPorId(oElemento[elSearchTerm], oElemento[elColorStyle], aCor);
			break;
		case 'class':
			alterarCorElementosPorClasse(oElemento[elSearchTerm], oElemento[elColorStyle], aCor);
			break;
	}
}

//
// chamada com um pequeno delay pelas a��es do form de sele��o de cores.
// testa o valor do elemento de retorno de xHttpInnerHtml.
// 'wait ...' - valor inicializado no innerHTML do elemento por xHttpInnerHtml. 
//  			significa que o handler ainda n�o recebeu status 4.
//				A rotina chama a si pr�pria novamente com outro delay
// 'NOK'	  - retornou com erro. recarrega a p�gina (� mais f�cil que tratar o erro).
// outro	  - aparentemente deu certo, tenta alterar a cor do elemento a partir do valor retornado.
//
function delayed_AlterarCorElemento() {
	var ua = window.navigator.userAgent;
	var xhr = document.getElementById('xHttpResponse').innerHTML + '';

	if (xhr == 'wait ...') 
	  setTimeout('delayed_AlterarCorElemento()', 50);
	else if (xhr == 'NOK') 
		window.location.reload();
	else {				   
		var oElemento = xhr.split('|');
		var aCor = unescape(oElemento[elValorCor]);
		alterarCorElemento(oElemento, aCor);
	}
}

/*
 **
 ** Executa a 1� a��o do form colorForm - adicionar uma cor a um par Pagina x elementoColorido
 **
*/
function adicionarCookedStyle() {

  // obt�m o elemento cuja cor ser� alterada pela op��o selecionada no <select> com elementos...
  var obj = document.getElementById('elementSelector');
  var idElementoColorido = obj.value;

  // obt�m a cor pela op��o selecionada no <select> com as cores...
  obj = document.getElementById('zzSelectColorForm');
  var valorCor = obj.options[obj.selectedIndex].value;

  // obt�m, no form, o id da p�gina que est� sendo editada.
  obj = document.getElementById('id');
  var id = obj.value;

  // inclui o cookie na base de dados e a seguir altera a cor no form.
  insertRequest = "/dyn/addcookie.php?id=" + id + "&el=" + idElementoColorido + "&c=" + escape(valorCor);
  xhttpInnerHtml(insertRequest, 'xHttpResponse');
  setTimeout('delayed_AlterarCorElemento()', 50);

}

/*
 **
 ** Executa a 2� a��o do form colorForm - deletar a associa��o de uma cor a um par Pagina x elementoColorido e restaurar
 **										  o padr�o da classe.
 **
*/
function deletarCookedStyle() {
  // obt�m o elemento cuja cor ser� alterada pela op��o selecionada no <select> com elementos...
  var obj = document.getElementById('elementSelector');
  var idElementoColorido = obj.value;

  // obt�m, no form, o id da p�gina que est� sendo editada.
  obj = document.getElementById('id');
  var id = obj.value;

  // deleta o cookie na base de dados e a seguir restaura a cor da classe.
  deleteRequest = "/dyn/delcookie.php?id=" + id + "&el=" + idElementoColorido;
  xhttpInnerHtml(deleteRequest, 'xHttpResponse');
  setTimeout('delayed_AlterarCorElemento()', 50);
}

/*
 **
 ** Executa a 3� a��o do form colorForm - restaurar o padr�o da classe, eliminando todos as associa��es entre uma cor
 **										  e um par Pagina x elementoColorido
 **
*/
function restaurarPagina() {
  // obt�m, no form, o id da p�gina que est� sendo editada.
  var id = document.getElementById('id').value;

  // remove os cookies da base de dados e recarrega a p�gina
  resetRequest = "/dyn/resetpage.php?id=" + id;
  xhttpInnerHtml(resetRequest, 'xHttpResponse');
  setTimeout('window.location.reload()', 500);
}

