<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
<link rel="shortcut icon" href="{$includePATH}favicon.ico" type="image/x-icon" />
{if isset($refresh)}
  <meta http-equiv="refresh" content="{$refresh}">
{/if}
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Type" content="image/jpeg" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/base.css" title="default" />
{if !isset($edicaoPagina)}
  <link rel="stylesheet" type="text/css" href="{$includePATH}estilos/{$classPagina}.css" title="default" />
{else}
  {section name=cp loop=$classNames}
  <link rel="stylesheet" type="text/css" href="{$includePATH}estilos/{$classNames[cp]}.css" {if $classNames[cp] == $classPagina}title="default"{/if} />
  {/section}
{/if}
<script type="text/javascript" src="{$includePATH}js/rotinas.js"></script>
{if $displaySelectColor == 1}
<script type="text/javascript" src="{$includePATH}js/cores.js"></script>
{/if}
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
<!-- Font Awesome icons -->
<link rel="stylesheet" href="{$includePATH}estilos/fawsome.css" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/toast_style.css" title="default" />
<script src="{$includePATH}js/toast.js" defer></script>
<script type="text/javascript">document.cookie = 'showAlerta=; Max-Age=-99999999;Path={$includePATH};Domain=' + location.hostname + ';Secure=true;SameSite=Strict;'</script>
</head>
<body class="{$classPagina}" style=" overflow: auto;" id="theBody" onload="createToast({if isset($smarty.cookies.iconAlerta)} '{$smarty.cookies.iconAlerta}' {else} 'info' {/if}, '{$smarty.cookies.msgAlerta}');">
{else}
</head>
<body class="{$classPagina}" style=" overflow: auto;" id="theBody">
{/if}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$includePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;"{/if}>
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
