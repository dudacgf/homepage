<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">


<head>

<script language="javascript" type="text/javascript" src="{$includePATH}js/jquery.js"></script>
<link rel="stylesheet" href="{$includePATH}estilos/c64charset.css" type="text/css" media="screen" />
<meta charset="UTF-8">
<style>
	* { margin:0px;padding:0px; }
	/* body { background:#7b73de url(/404/c64.gif) no-repeat center 50px scroll; } */
	body { background:#7b73de }

	
	#c64background { height:400px; width:640px; background:#3931a5; }
	.cont { text-align:left;margin-top:168px;width:640px; color:#7b73de; font-family: verdana; font-size:24px; font-weight:bold;}
	#ursor { position:relative; top:-0px; left:-3px; color:#7b73de;background:#7b73de;width:20px;height:21px; font-size:1px;display:block}
	#cursor { position:absolute; top:168px; color:#7b73de;background:#7b73de;width:16px;height:16px; font-size:1px;display:block}
	kbd { float:left;display:none; }
</style>

<script language="JavaScript">


	// c64 404 page not found by Klaus Hessellund / 200709
	// Feel free to copy this script to you own page. Some credits would be nice, but not mandatory :p
	// c64 charset found here : http://www.dwarffortresswiki.net/index.php/List_of_user_tilesets


	var loadlist;
	loadlist = "½LOAD\"$\",§8\n";
	loadlist += "SEARCHING FOR $\nLOADING¤\nREADY.\n";
	loadlist += "½¤LIST§\n";
	loadlist += "0 \"TEST DISK       \" 23 2A\n";
	loadlist += "20   \"FOO\"               PRG\n";
	loadlist += "3    \"BAR\"               PRG\n";
	loadlist += "641 BLOCKS FREE.\n";



	var text;
	text = "§ \n";
	text += "§    **** THE SHIRE'S COMPANY ****\n \n";
	text += "ERROR 404 PAGE NOT FOUND\n \n";
	text += "IF YOU THINK THIS IS AN ERROR OF MINE\n";
	text += "¤½FEEL FREE TO PRESS BACKSPACE\",8§\n" + 
	       "SEARCHING FOR /404/&Acirc;&nbsp;¤\n" +
	       "?FILE NOT FOUND  ERROR\nREADY.\n" + loadlist + 
				 "READY.\n" + 
				 "¤¤¤¤¤¤¤¤¤¤¤¤¤½SYS 64738¤°" + 
				 "" + 
				 ""; 

	var textlen = text.length;
	var textidx=0;
  var fontwidth=16;
	var typing=1;
	var pause=0;
	var reset=0;

	function parsechar (c) {
		movecursor=1;
		if (c == ' ') { c='space'; }
		else if (c == 'Ø') { c='OE'; }
		else if (c == 'Å') { c='AA'; }
		else if (c == '.') { c='dot'; }
		else if (c == '*') { c='star'; }
		else if (c == '$') { c='dollar'; }
		else if (c == '/') { c='slash'; }
		else if (c == '"') { c='quot'; }
		else if ((c>0 && c<=9)) { c='n'+c; }
		else if ((c== '0')) { c='n'+c; }
		else if (c == ',') { c='comma'; }
		else if (c == '!') { c='att'; }
		else if (c == '?') { c='question'; }
		else if (c == '_') { c='underscore'; }
		else if (c == '\'') { c='singlequot'; }
		else if (c == ':') { c='colon'; }
		else if (c == ';') { c='semicolon'; }
		else if (c == '&') { c='amp'; }
		else if (c == '=') { c='equal'; }
		else if (c == '%') { c='percent'; }
		else if (c == '+') { c='plus'; }
		else if (c == '\n') { c='newline'; movecursor=0; }
		else if (c == '°') { reset=1; movecursor=0; }
		else if (c == '§') { c='none'; typing = 0; movecursor=0; }
		else if (c == '½') { c='none'; typing = 1; movecursor=0; }
		else if (c == '¤') { c='none'; pause = 1; movecursor=0; }

		return (c);
	}

	var t;
	var c;
	var b;

	function writetext() {


		if (reset) {
			initWrite();
			return;
		}

		var getchar = text.charAt(textidx);
		c64char = '<div id="i' + textidx + '" class="' + parsechar(getchar) + '"></div>';
		t.innerHTML = t.innerHTML + c64char;
		
		var elid='i' + textidx;
		var el = document.getElementById(elid);
		var addwidth = movecursor ? fontwidth : 0 ;

		var posarr = findPos (el);
		
		var curleft = posarr[0]+addwidth;
		var curtop = posarr[1];

		if (textlen != textidx) {
			textidx++;
			c.style.top = curtop + 'px';
			c.style.left = curleft + 'px';
			
			if (typing) {
				if (randx(10) > 7) {
					rand=randx(700);
				} else {
					rand=randx(200);
				}
				fixedrand=10;
			} else {
				rand=0;
				fixedrand=0;
			}
			if (pause) {
				rand=2000; pause=0; 
			} else {
				flipcursor(1);
			}

			if (rand==0) {
				writetext();
			} else {
    		setTimeout ('writetext()',fixedrand+rand);
			}

		} else {

		}
	}

	function findPos(obj) {
		var fcurleft = fcurtop = 0;
		if (obj.offsetParent) {
			fcurleft = obj.offsetLeft
			fcurtop = obj.offsetTop
			while (obj = obj.offsetParent) {
				fcurleft += obj.offsetLeft
				fcurtop += obj.offsetTop
			}
		}

		return [fcurleft,fcurtop];
	}


	function flipcursor(nosettime) {

		var cursor=document.getElementById("cursor");

		if (cursor.style.visibility == 'hidden') {
			cursor.style.visibility = '';
		} else {
			cursor.style.visibility = 'hidden';
		}
		if (!nosettime) {
			setTimeout('flipcursor()',300);
		}
	}

	function randx(n) {
 		return ( Math.floor ( Math.random ( ) * n + 1 ) );
	}

	function initWrite () {
		t = document.getElementById("thetext");
		c = document.getElementById("cursor");
		b = document.getElementById("body");
		t.innerHTML="";
		textidx=0;
		reset=0;
		writetext();
		// flipbackground();
	}

	var bgflipped = 1;
	function flipbackground() {
		bgflipped ? b.style.background="white" : b.style.background="black";
		bgflipped = bgflipped ? 0 : 1;
		setTimeout('flipbackground()',0);
	}


</script>


</head>

<body id="body" onload="flipcursor(0);initWrite();">


<center>

<div style="height:100px">&nbsp;<br><br><br></div>
<div id="c64background">

	<div id="thetext">
	</div>
	<div id="cursor">&nbsp;</div>

</div>


</center>


</body>


</html>

