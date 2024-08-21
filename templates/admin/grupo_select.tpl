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
			document.getElementById('mode').value = 'edGrp';
		{rdelim} 
		else if (pressed == '{$LANG.novoGrupo}') {ldelim}
			document.getElementById('mode').value = 'nwGrp';
		{rdelim} 
		else if (pressed == '{$LANG.voltar}') {ldelim}
			document.getElementById('mode').value = 'stats';
		{rdelim}
		document.cdel.submit();
	{rdelim}
-->
</script>


<td>
<form name="cdel" action="{$includePATH}admin/grupo_edit.php" method="GET">
<input type="hidden" id="mode" name="mode" value="edGrp">
	<table width="600px" style="text-align: center;">
	<tr><th class="categoria" colspan="3">{$LANG.selecionarGrupo}</th></tr>
	<tr><td>
		<br />
		<select name="idGrp" id="idGrp" size="15">
		{section name=g loop=$grupos}
			<option value="{$grupos[g].idGrupo}">
				{$grupos[g].descricaoGrupo} :: {if $grupos[g].grupoRestrito == 1}[ restri&ccedil;&atilde;o - {$grupos[g].restricaoGrupo} ]{else}[ sem restri&ccedil;&atilde;o ]{/if}
			</option>
		{/section}
		</select>
		<p />
	</td></tr>
	<tr><th class="categoria" colspan="3" style="text-align: center;">
		<input type="submit" class="submit" name="go" value="{$LANG.confirmar}" onclick="javascript: doAction(this.value);"> ::
		<input type="submit" class="submit" name="go" value="{$LANG.novoGrupo}" onclick="javascript: doAction(this.value);"> ::
		<input type="submit" class="submit" name="go" value="{$LANG.voltar}" onclick="javascript: doAction(this.value);">
	</th></tr>
	</table>
</form>
</td>

{include file="page_footer.tpl"}

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
