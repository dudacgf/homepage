{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{if isset($edicaoPagina)}
<iframe id="exemploPagina" class="exemploCategoria" src="{$includePATH}/homepage.php?id={$idPagina}&gr=all"></iframe>
{/if}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$includePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;"{/if}>
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
<div class="content">
{if !isset($edicaoPagina)}<table style="text-align: center;"><tr>{/if}
{include file="page_body.tpl"}
{if !isset($edicaoPagina)}</tr></table>{/if}
</div>
{include file="form_color.tpl"}
<div style="clear: none;">
{include file="fortune.tpl"}
</div>
{include file="page_footer.tpl"}
