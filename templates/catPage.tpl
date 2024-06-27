{include file="page_header.tpl"}

<script type="text/javascript">
<!--
	function doAction(pressed) {ldelim}
		var idCat = document.getElementById('idCat').value;
		var getCategURL = '/dyn/getCategoria.php?idCat=' + idCat;
		xhttpInnerHtml(getCategURL, 'divCategoria');
	{rdelim}
-->
</script>


<div style="width: 600px; float: left;">
	<form name="cdel" method="POST">
		<table width="600px" style="text-align: center;">
		<tr><th class="categoria" colspan="3">{$LANG.selecionarCategoria}</th></tr>
		<tr><td>
			<br />
			<select name="idCat" id="idCat" size="5">
			{section name=cat loop=$categorias}
				<option value="{$categorias[cat].idCategoria}">
					{$categorias[cat].descricaoCategoria} :: {if $categorias[cat].categoriaRestrita == 1}[ restrição - {$categorias[cat].restricaoCategoria} ]{else}[ sem restrição ]{/if}
				</option>
			{/section}
			</select>
			<p />
		</td></tr>
		<tr><th class="categoria" colspan="3" style="text-align: center;">
			<input type="submit" class="submit" name="go" value="Confirmar" onclick="javascript: doAction(); return false;"> ::
		</th></tr>
		</table>
	</form>
</div>
<div id='divCategoria' style="margin-right: 100px; float: right; width: 200px;">
</div>

{include file="page_footer.tpl"}

{* // vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
