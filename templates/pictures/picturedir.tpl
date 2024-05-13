{include file="page_header.tpl"}

<table>
<tr>
	<th class="categoria" style=" width: 100pt; text-align: left;">
		{if $pagatual > 2}
			<a href="picturedir.php?dir={$dirAtual}&pag=1"><img src="{$relativePATH}imagens/leftleft_arrow.gif" style=" width: 20px; height: 15px; vertical-align: middle; border: 0pt">&nbsp;</a>
		{/if}
		{if $pagatual > 1}
			<a href="picturedir.php?dir={$dirAtual}&pag={math equation="pag - 1" pag=$pagatual}"><img src="{$relativePATH}imagens/left_arrow.gif" style=" width: 14px; height: 15px; vertical-align: middle; border: 0pt">&nbsp;</a>
		{/if}
	</th>
	<td colspan="4" style=" width: 400pt; " class="titulo"><a href="picturedir.php">:: FOTOS ::</a></td>
	<th class="categoria" style=" width: 100pt; text-align: right;">
		{if $pagatual < $numpaginas}
			<a href="picturedir.php?dir={$dirAtual}&pag={$pagproxima}">&nbsp;<img src="{$relativePATH}imagens/right_arrow.gif" style=" width: 14px; height: 15px; vertical-align: middle; border: 0pt;"></a>
		{/if}
		{if $pagatual < $pagpenultima}
			<a href="picturedir.php?dir={$dirAtual}&pag={$numpaginas}">&nbsp;<img src="{$relativePATH}imagens/rightright_arrow.gif" style=" width: 20px; height: 15px; vertical-align: middle; border: 0pt;"></a>
		{/if}
	</th>
</tr><tr>
	{section name=ft loop=$arquivos}
	<td class="categoria" style=" width: 100pt; vertical-align: top; text-align: center;" >
		<div class="interior" onmouseover="this.setAttribute('{$classAttribute}', 'expanded')" onmouseout="this.setAttribute('{$classAttribute}', 'interior')">
			<a href="{$arquivos[ft].url}"><img src="{$arquivos[ft].image}" alt="" title="" border="0" vspace="10"><br /><span style="font-size: 8pt;">{$arquivos[ft].arquivo|wordwrap:20:"<br />"}</span></a>
		</div>
	</td>
	{if $arquivos[ft].fimColuna}
</tr><tr>
	{/if}
	{sectionelse}
	<td class="category" colspan="10">
		<div class="fortune" style=" font-size: 14pt; font-weight: bold; text-align: center;">
		N&atilde;o h&aacute; fotos neste diret&oacute;rio!
		</div>
	</td>
	{/section}
</tr>
</table>

{include file="page_footer.tpl"}

{* // vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
