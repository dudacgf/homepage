<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="{$includePATH}favicon.ico" type="image/x-icon" />
{if isset($refresh)}
  <meta http-equiv="refresh" content="{$refresh}">
{/if}
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
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/{$classPagina}.css" />
<script type="text/javascript">
    window.includePATH = "{$includePATH}";
</script>
<script type="text/javascript" src="{$includePATH}js/rotinas.js"></script>
<script type="text/javascript" src="{$includePATH}js/api.js"></script>
<script type="text/javascript" src="{$includePATH}js/toast.js"></script>
<title>
  {if !isset($tituloPaginaAlternativo)}
    {$tituloPagina}
  {else}
    {$tituloPaginaAlternativo}
  {/if}
</title>
<style type="text/css">
{$cookedStyles|strip}
</style>
{if isset($smarty.cookies.showAlerta)}
<script type="text/javascript">document.cookie = 'showAlerta=; Max-Age=-99999999;Path={$includePATH};Domain=' + location.hostname + ';Secure=true;SameSite=Strict;'</script>
</head>
<body class="{$classPagina}" id="theBody" onload="createToast({if isset($smarty.cookies.iconAlerta)} '{$smarty.cookies.iconAlerta}' {else} 'info' {/if}, '{$smarty.cookies.msgAlerta}');">
{else}
</head>
<body class="{$classPagina}" id="theBody">
{/if}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$includePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;"{/if}>
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
