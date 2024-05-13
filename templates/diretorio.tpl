<!-- saved from url="http://www.theshirescompany.com" -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
<link rel="shortcut icon" href="{$relativePATH}favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Type" content="image/jpeg" />
<link rel="stylesheet" type="text/css" href="{$relativePATH}estilos/estilos.css" title="default" />
<title>
	{if !isset($tituloPaginaAlternativo)}
		{$tituloPagina}
	{else}
		{$tituloPaginaAlternativo}
	{/if}
</title>
</head>
<body class="{$classPagina}" style=" overflow: auto;"; id="theBody">
<table class="fullDiv"><tr><td class="interior">
	<table style="border-collapse: collapse; border: 1pt solid white;" class="content" align="center">
	<tr>
		<td colspan="10" class="titulo">
			<span style=" white-space: nowrap; font-size: 14pt; font-weight: bold;">
				{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
			</span>
		</td>
	</tr>
	<tr>
	{section name=fn loop=$arquivos}{strip}
		<td style="width: 120pt; border: solid 1px; padding: 0px;">
			<a href="/svn/{$arquivos[fn].fileName}">{$arquivos[fn].fileName}</a>
		</td>
		{if $arquivos[fn].fimLinha}
		</tr><tr>
		{/if}
	{/strip}{/section}
	</tr>
	<tr>
		<td colspan="10" class="titulo">
			&nbsp;
		</td>

{include file="page_footer.tpl"}

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
