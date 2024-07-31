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

function cookieOptions() {
    return ";expires = " + calcExpires(300) + ";path=" + adminPath + ";domain=" + location.hostname + ";secure=true;SameSite=strict;";
}

