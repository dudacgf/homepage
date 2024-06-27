{include file="page_header.tpl"}
<script type="text/javascript" src="{$includePATH}js/form_cores.js"></script>

<div id='help' class='expanded'>
</div>

<script type="text/javascript">
	document.getElementById('help').innerHTML = '';
	var rgbObj = new RGBColor('');
	var cores = rgbObj.getNamedColors();
	var aCor = '';
	for (i = 0; i < osNomesDasCores.length; i++) {ldelim}
			aCor = osNomesDasCores[i];
			document.getElementById('help').innerHTML += aCor + " ==> " + cores[aCor.toLowerCase()] + "<br />";
	{rdelim}
</script>

{include file="page_footer.tpl"}
{* //-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
