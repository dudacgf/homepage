<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR.UTF-8" lang="pt-BR.UTF-8">
<head>
<link rel="shortcut icon" href="{$includePATH}favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Type" content="image/jpeg" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/base.css" title="default" />
<link rel="stylesheet" type="text/css" href="{$includePATH}estilos/{$classPagina}.css" title="default" />
<script type="text/javascript" src="{$includePATH}js/rotinas.js"></script>
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
		  {$descricaoCategoria}
	</div>
	{section name=dc loop=$elementos}
		<div class="wgrupo">
	  <a href="/wappage.php?id={$idPagina}&amp;cat={$idCategoria}&amp;grp={$elementos[dc].idGrupo}{if isset($gr)}&amp;gr={$gr}{/if}" title="Links do grupo {$elementos[dc].descricaoGrupo}">
		  <img src="/imagens/seta_direita.png" style="vertical-align: middle; border: none 0px;" />
		  {$elementos[dc].descricaoGrupo}
	  </a>
	</div>
{/section}
</div>
<div class="footer" style="position: absolute; left: 2px; bottom: 3px;">
  <span class="texto" style="font-weight: bold;">&copy; The Shire's Company 19/11/2008</span>
</div>
</body>
</html>
{php} //-- vi: set tabstop=4 shiftwidth=4 showmatch nowrap: {/php}
