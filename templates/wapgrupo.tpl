<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR.UTF-8" lang="pt-BR.UTF-8">
<head>
<link rel="shortcut icon" href="{$relativePATH}favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Type" content="image/jpeg" />
<link rel="stylesheet" type="text/css" href="{$relativePATH}estilos/base.css" title="default" />
<link rel="stylesheet" type="text/css" href="{$relativePATH}estilos/{$classPagina}.css" title="default" />
<script type="text/javascript" src="{$relativePATH}js/rotinas.js"></script>
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
<body class="{$classPagina}" style=" overflow: auto; background-image: none;" id="theBody" >
<div class="wtitulo">
  <a href="/wappage.php?id={$idPagina}{if isset($gr)}&amp;gr={$gr}{/if}" title="Volta à página">
    {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
  </a>
</div>
<div class="wcategoria">
	<a href="/wappage.php?id={$idPagina}&amp;cat={$idCategoria}{if isset($gr)}&amp;gr={$gr}{/if}" title="Volta à categoria">
	  {$descricaoCategoria}
	</a>
</div>
<div class="wgrupo">
  {$descricaoGrupo}
</div>
{section name=el loop=$elementos}
{strip}
<div class="wlink">
	{assign var="elemento" value=$elementos[el]}
	{if $elemento.tipoElemento == 1}  
		  <a href="
		  {if $elemento.urlElementoSSL}
			https://
		  {elseif $elemento.urlElementoSVN}
			svn+ssh://
		  {else}
			http://
		  {/if}
		  {if $elemento.localLink}
			{$smarty.server.SERVER_NAME}/{$relativePATH}
		  {/if}
		  {$elemento.linkURL}" target="{$elemento.targetLink}" title="{$elemento.dicaLink} [ {$elemento.linkURL} ]">
		<img src="/imagens/seta_direita.png" style="vertical-align: middle; border: none 0px;" />
		{$elemento.descricaoLink}</a>
	{/if}
</div>
{/strip}
{/section}
<div class="footer" style="position: absolute; left: 2px; bottom: 3px;">
  <span class="texto" style="font-weight: bold;">&copy; The Shire's Company 19/11/2008</span>
</div>
</body>
</html>
{php} //-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: {/php}
