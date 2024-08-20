{include file="page_header.tpl"}

<script type="text/javascript">
<!--
	function doAction(pressed) {ldelim}
		if (pressed == '{$LANG.confirmar}') {ldelim}
			document.cdel.action = '{$includePATH}admin/categoria_edit.php?mode=edCat&idCat=' + document.getElementById('idCat').value;
		{rdelim} 
		else if (pressed == '{$LANG.novaCategoria}') {ldelim}
			document.cdel.action = '{$includePATH}admin/categoria_edit.php?mode=nwCat';
		{rdelim} 
		else if (pressed == '{$LANG.voltar}') {ldelim}
			document.cdel.action = '{$includePATH}admin/estatisticas.php';
		{rdelim}
		document.cdel.submit();
	{rdelim}
-->
</script>


<td>
<form name="cdel" method="POST">
	<table width="600px" style="text-align: center;">
	<tr><th class="categoria" colspan="3">{$LANG.selecionarCategoria}</th></tr>
	<tr><td>
		<br />
		<select name="idCat" id="idCat" size="15">
		{section name=cat loop=$categorias}
			<option value="{$categorias[cat].idCategoria}">
				{$categorias[cat].descricaoCategoria} :: {if $categorias[cat].categoriaRestrita == 1}[ restri&ccedil;&atilde;o - {$categorias[cat].restricaoCategoria} ]{else}[ sem restri&ccedil;&atilde;o ]{/if}
			</option>
		{/section}
		</select>
		<p />
	</td></tr>
	<tr><th class="categoria" colspan="3" style="text-align: center;">
		<input type="submit" class="submit" name="go" value="{$LANG.confirmar}" onclick="javascript: doAction(this.value);"> ::
		<input type="submit" class="submit" name="go" value="{$LANG.novaCategoria}" onclick="javascript: doAction(this.value);"> ::
		<input type="submit" class="submit" name="go" value="{$LANG.voltar}" onclick="javascript: doAction(this.value);">
	</th></tr>
	</table>
</form>
</td>

{include file="page_footer.tpl"}

{* // vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
