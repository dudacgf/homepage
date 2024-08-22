{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$includePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;"{/if}>
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
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
<form name="cdel" method="POST">
	<table width="600px" style="text-align: center;">
	<tr><th class="categoria" colspan="3">{$LANG.selecionarCategoria}</th></tr>
	<tr><td>
		<p>
		<select name="idCat" id="idCat" size="15">
		{section name=cat loop=$categorias}
			<option value="{$categorias[cat].idCategoria}">
				{$categorias[cat].descricaoCategoria} :: {if $categorias[cat].categoriaRestrita == 1}[ restrição - {$categorias[cat].restricaoCategoria} ]{else}[ sem restrição ]{/if}
			</option>
		{/section}
		</select>
		</p>
	</td></tr>
	<tr><th class="categoria" colspan="3" style="text-align: center;">
		<input type="submit" class="submit" name="go" value="{$LANG.confirmar}" onclick="javascript: doAction(this.value);"> ::
		<input type="submit" class="submit" name="go" value="{$LANG.novaCategoria}" onclick="javascript: doAction(this.value);"> ::
		<input type="submit" class="submit" name="go" value="{$LANG.voltar}" onclick="javascript: doAction(this.value);">
	</th></tr>
	</table>
</form>
{include file="page_footer.tpl"}
