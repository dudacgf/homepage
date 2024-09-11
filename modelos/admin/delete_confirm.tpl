{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
<td>
<form action="{$includePATH}{$script2call}" method="POST">
<input type="hidden" name="mode" id="mode" value="{$scriptMode}" />
{if isset($idPagina)}
	<input type="hidden" name="id" id="id" value="{$idPagina}" />
{/if}
{if isset($idCategoria)}
	<input type="hidden" name="idCat" id="idCat" value="{$idCategoria}" />
{/if}
{if isset($idGrupo)}
	<input type="hidden" name="idGrp" id="idGrp" value="{$idGrupo}" />
{/if}
{if isset($idElm)}
	<input type="hidden" name="idElm" id="idElm" value="{$idElm}" />
{/if}
	<table style="text-align: center;">
	<tr><th class="categoria" colspan="3">{$deleteConfirmTituloTabela}</td></tr>
	<tr><td><span class="fortune">
		<br />
		<b>{$deleteConfirmDescricao}?</b>
		<p />
	</span></td></tr>
	<tr><td>
		<input type="submit" class="submit" name="go" value="{$LANG.sim}">
		<input type="submit" class="submit" name="go" value="{$LANG.nao}">
	</td></tr>
	</table>
</form>
</td>

{include file="page_footer.tpl"}
