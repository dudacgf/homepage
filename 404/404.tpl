<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="{$includePATH}404/c64.css" type="text/css" media="screen" />
<meta charset="UTF-8">
<style>
    * { margin:0px;padding:0px; }
    body { background:#7b73de }
    #c64background { height:480px; width:640px; background:#3931a5; }
    #cursor { position:absolute; top:168px; color:#7b73de;background:#7b73de;width:16px;height:16px; font-size:1px;display:block}
</style>

<script language="JavaScript">
    // c64 404 page not found by Klaus Hessellund / 200709
    // Feel free to copy this script to you own page. Some credits would be nice, but not mandatory :p
    // c64 charset found here : http://www.dwarffortresswiki.net/index.php/List_of_user_tilesets
    // 
    // completely readapted for the current use

    var text;
    text = "§ \n \n";
    text += "§    **** THE SHIRE'S COMPANY ****    §\n \n";
    text += "¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤\n";
    text += "ERROR 404 PAGE NOT FOUND\n";
    text += "¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤\n \n";
    text += "¤½SOMEWHERE NEARBY IS COLOSSAL CAVE, \n \n¤°WHERE OTHERS HAVE FOUND FORTUNES IN\n \n ";
    text += "TREASURE AND GOLD, THOUGH IT IS\n \n RUMORED THAT SOME WHO ENTER ARE NEVER\n \n§½ ";
    text += "SEEN AGAIN. \n \n ¬¬MAGIC IS SAID TO WORK IN THE CAVE.¬¬";

    var textlen = text.length;
    var textidx=0;
    var fontwidth=16;
    var typing=1;
    var pause=0;
    var reset=0;

    var t;
    var c;
    var b;

    function writetext() {


        if (reset) {
            initWrite();
            return;
        }

        movecursor = 1;

        var getchar = text.charAt(textidx);
        if (getchar == '\n') 
            c64char = '<div class="newline" id="i' + textidx + '"></div>';
        else if (getchar == ' ')
            c64char = '<div class="c64" id="i' + textidx + '">&nbsp;</div>';
        else
            c64char = '<div class="c64" id="i' + textidx + '">' + getchar + '</div>';
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
