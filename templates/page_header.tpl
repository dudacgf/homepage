<!DOCTYPE html>
<html>
<head>
<title>{if !isset($tituloPaginaAlternativo)}{$tituloPagina}{else}{$tituloPaginaAlternativo}{/if}</title>
<link rel="shortcut icon" href="{$includePATH}favicon.ico" type="image/x-icon" />
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Type" content="image/jpeg" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/base.css" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/colorbase.css" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/fawsome.css" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/toast_style.css" />
{if isset($edicaoPagina)}
  {section name=cp loop=$classNames}
  {if $classNames[cp] != $classPagina}
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/{$classNames[cp]}.css" />
  {/if}
  {/section}
{/if}
{if $classPagina != 'colorbase'}
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/{$classPagina}.css" />
{/if}
<script type="text/javascript">window.includePATH = "{$includePATH}";</script>
<script type="text/javascript" src="{$includePATH}js/api.js"></script>
<script type="text/javascript" src="{$includePATH}js/toast.js"></script>
{if isset($cookedStyles)}
<style type="text/css">
{$cookedStyles|strip}
</style>
{/if}
{if isset($smarty.cookies.showAlerta)}
<script type="text/javascript">document.cookie = 'showAlerta=; Max-Age=-99999999;Path={$includePATH};Domain=' + location.hostname + ';Secure=true;SameSite=Strict;'</script>
{/if}
</head>
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{if isset($edicaoPagina)}
<iframe id="exemploPagina" class="exemploCategoria" src="{$includePATH}/homepage.php?id={$idPagina}&gr=all"></iframe>
{/if}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$includePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;"{/if}>
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
