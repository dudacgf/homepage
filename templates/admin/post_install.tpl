{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{if isset($edicaoPagina)}
<iframe id="exemploPagina" class="exemploCategoria" src="{$includePATH}/homepage.php?id={$idPagina}&gr=all"></iframe>
{/if}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$includePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;"{/if}>
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
<div style="float: left; padding: 15px;">
<span style="font-weight: bold;">'/' .htaccess<br /></span>
<span style="font-family: monospace; white-space: pre-line; text-align: left;">{$root_ht}</span>
</div>
<div style="clear: left; padding: 15px;">
<span style="font-weight: bold;">'/admin/' .htaccess<br /></span>
<span style="font-family: monospace; white-space: pre-line; text-align: left;">{$admin_ht}</span>
</div>

<div style="float: left; padding: 15px;">
{assign var=path value=$includePATH|cat:"homepage.php"}
<button class="submit" onclick="window.location = '{$path|replace:'//':'/'}'">HOMEPAGE</button>
</div>
{include file="page_footer.tpl"}

