{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{if isset($edicaoPagina)}
<iframe id="exemploPagina" class="exemploCategoria" src="{$includePATH}/homepage.php?id={$idPagina}&gr=all"></iframe>
{/if}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$includePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;"{/if}>
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>

<script type="text/javascript">
<!--
	function doAction(pressed) {ldelim}
		if (pressed == '{$LANG.confirmar}') {ldelim}
			document.cdel.action = '{$includePATH}admin/page_edit.php?mode=edPag&id=' + document.getElementById('id').value;
		{rdelim} 
		else if (pressed == '{$LANG.novaPagina}') {ldelim}
			document.cdel.action = '{$includePATH}admin/page_edit.php?mode=nwPag';
		{rdelim} 
		else if (pressed == '{$LANG.voltar}') {ldelim}
			document.cdel.action = '{$includePATH}admin/estatisticas.php';
		{rdelim}
		document.cdel.submit();
	{rdelim}
-->
</script>


<td>
<form name="cdel" action="{$includePATH}admin/page_edit.php" method="POST">
	<table width="600px" style="text-align: center;">
	<tr><th class="categoria" colspan="3">{$LANG.selecionarPagina}</th></tr>
	<tr><td>
		<br />
		<select name="id" id="id" size="15">
		{section name=pag loop=$paginas}
			<option value="{$paginas[pag].idPagina}">
				{$paginas[pag].tituloPagina} :: {$paginas[pag].tituloTabela}
			</option>
		{/section}
		</select>
		<p />
	</td></tr>
	<tr><th class="categoria" colspan="3" style="text-align: center;">
		<input type="submit" class="submit" name="go" value="{$LANG.confirmar}" onclick="javascript: doAction(this.value);"> ::
		<input type="submit" class="submit" name="go" value="{$LANG.novaPagina}" onclick="javascript: doAction(this.value);"> ::
		<input type="submit" class="submit" name="go" value="{$LANG.voltar}" onclick="javascript: doAction(this.value);">
	</th></tr>
	</table>
</form>
</td>

{include file="page_footer.tpl"}

{* // vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
