{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$includePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;"{/if}>
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
	<div class="itemLateral">{$LANG.NumPaginas}</div>
	<div class="item">{$numPaginas}</div>
	<div class="itemLateral">{$LANG.NumCategorias}</div>
	<div class="item">{$numCategorias}</div>
	<div class="itemLateral">{$LANG.NumGrupos}</div>
	<div class="item">{$numGrupos}</div>
	<div class="itemLateral">{$LANG.NumLinks}</div>
	<div class="item">{$numLinks}</div>
	<div class="itemLateral">{$LANG.NumForms}</div>
	<div class="item">{$numForms}</div>
	<div class="itemLateral">{$LANG.NumImagens}</div>
	<div class="item">{$numImagens}</div>
	<div class="itemLateral">{$LANG.NumTemplates}</div>
	<div class="item">{$numTemplates}</div>
	<div class="itemLateral">{$LANG.NumFortunes}</div>
	<div class="item">{$numFortunes}</div>

{include file="page_footer.tpl"}