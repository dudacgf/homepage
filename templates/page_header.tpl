<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
<link rel="shortcut icon" href="{$relativePATH}favicon.ico" type="image/x-icon" />
{if isset($refresh)}
  <meta http-equiv="refresh" content="{$refresh}">
{/if}
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Type" content="image/jpeg" />
<link rel="stylesheet" type="text/css" href="estilos/base.css" title="default" />
{if !isset($edicaoPagina)}
  <link rel="stylesheet" type="text/css" href="estilos/{$classPagina}.css" title="default" />
{else}
  {section name=cp loop=$classNames}
  <link rel="stylesheet" type="text/css" href="estilos/{$classNames[cp]}.css" {if $classNames[cp] == $classPagina}title="default"{/if} />
  {/section}
{/if}
<script type="text/javascript" src="js/rotinas.js"></script>
{if $displaySelectColor == 1}
<script type="text/javascript" src="js/cores.js"></script>
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
</head>
<body class="{$classPagina}" style=" overflow: auto;" id="theBody" >
{if isset($msgAlerta)}
<script type="text/javascript">alert('{$msgAlerta}');</script>
{/if}
<div  class="titulo" {if $displayImagemTitulo == '1'}style="background-image: url('{$relativePATH}imagens/duda_logo.gif');background-repeat: no-repeat; background-position: top right;{/if}">
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
