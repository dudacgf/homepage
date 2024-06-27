{assign var="edicaoPagina" value="1"}
{include file="page_header.tpl"}

<script type="text/javascript">
<!--
	function doAction(pressed) {ldelim}
		mode = document.getElementById('mode').value;
		if (mode != 'crPag') {ldelim}
			switch (pressed) {ldelim}
				case '{$LANG.gravar}':
					document.edPag.action = '{$includePATH}admin/page_edit.php?mode=svPag&id=' + document.getElementById('id').value; 
					break;
				case '{$LANG.excluir}':
					document.edPag.action = '{$includePATH}admin/page_edit.php?mode=cfExPag&id=' + document.getElementById('id').value; 
					break;
				case '{$LANG.novaPagina}':
					document.edPag.action = '{$includePATH}admin/page_edit.php?mode=nwPag';
					break;
				case '{$LANG.cancelar}':
					document.edPag.action = '{$includePATH}admin/page_edit.php?mode=slPag';
					break;
			{rdelim}
		{rdelim} else {ldelim}
			switch (pressed) {ldelim}
				case '{$LANG.gravar}':
					document.edPag.action = '{$includePATH}admin/page_edit.php?mode=crPag';
					break;
				case '{$LANG.cancelar}':
					document.edPag.action = '{$includePATH}admin/page_edit.php?mode=slPag';
					break;
			{rdelim}
		{rdelim}
		document.edPag.submit();
	{rdelim}
-->
</script>

<form id="edPag" name="edPag" method="POST">
{if $criarPagina}
	<input type="hidden" id="mode" name="mode" value="crPag" />
{else}
	<input type="hidden" id="id" name="id" value="{$idPagina}" />
	<input type="hidden" id="mode" name="mode" value="svPag" />
{/if}
	<div class="subTitulo">{$LANG.configuracao}</div><p />
	<div class="itemLateral">{$LANG.hp_paginas_TituloPagina}</div>
	<div class="item"><input type="text" class="FormExtra" size=30 name="tituloPagina" value="{$tituloPagina}" tabindex="1" /></div>
	<div class="itemLateral">{$LANG.hp_paginas_TituloTabela}</div>
	<div class="item"><input type="text" class="FormExtra" size=30 name="tituloTabela" value="{$tituloTabela}" tabindex="1" /></div>
	<div class="itemLateral">{$LANG.hp_paginas_classPagina}</div>
	<div class="item">
		<select style=" width: 122pt;" name="classPagina" id="classPagina" >
		{section name=cp loop=$classNames}
			<option value="{$classNames[cp]}" {if $classNames[cp] == $classPagina}selected="selected"{/if} onclick="javascript: document.getElementById('theBody').setAttribute('{$classAttribute}', this.value)" >{$classNames[cp]}</option>
		{/section}
		</select>
	</div>
	<div class="item" style="clear: left; width: 70%; align: right;">
		<input id="displayGoogle" type="checkbox" name="displayGoogle" {if $displayGoogle == '1'}checked{/if} />
		<label for="displayGoogle">{$LANG.hp_paginas_displayGoogle}</label>
		<input id="displayFindaMap" type="checkbox" name="displayFindaMap" {if $displayFindaMap == '1'}checked{/if} />
		<label for="displayFindaMap">{$LANG.hp_paginas_displayFindaMap}</label>
		<input id="displayFortune" type="checkbox" name="displayFortune" {if $displayFortune == '1'}checked{/if} />
		<label for="displayFortune">{$LANG.hp_paginas_displayFortune}</label>
		<input id="displayImagemTitulo" type="checkbox" name="displayImagemTitulo" {if $displayImagemTitulo == '1'}checked{/if} />
		<label for="displayImagemTitulo">{$LANG.hp_paginas_displayImagemTitulo}</label>
		<input id="displaySelectColor" type="checkbox" name="displaySelectColor" {if $displaySelectColor == '1'}checked{/if} />
		<label for="displaySelectColor">{$LANG.hp_paginas_displaySelectColor}</label>
	</div>
	<div class="interior" style=" text-align: center; padding-top: 4pt;">
		<input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/> ::
{if !$criarPagina}
		<input type="submit" name="go" id="go" value="{$LANG.excluir}" class="submit" onclick="doAction('{$LANG.excluir}')"/> ::
		<input type="submit" name="go" id="go" value="{$LANG.novaPagina}" class="submit" onclick="doAction('{$LANG.novaPagina}')"/> ::
{/if}
		<input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/> 
	</div>
	<div class="exemploCategoria">
{include file="page_body.tpl"}
	</div>
</form>
{if !$criarPagina}
<p>
<div class="subTitulo">{$LANG.categorias}</div>
<div class="tituloColuna">Categoria</div>
<div class="tituloColuna">{$LANG.subir}</div>
<div class="tituloColuna">{$LANG.descer}</div>
<div class="tituloColuna">{$LANG.excluir}</div>
<div class="tituloColuna">{$LANG.categoriaRestrita}</div>
</p>
{section name=dc loop=$categoriasPresentes}
<p>
	<div class="tituloColuna" style="clear: left;">
		<a href="{$includePATH}admin/categoria_edit.php?mode=edCat&id={$idPagina}&idCat={$categoriasPresentes[dc].idCategoria}">{$categoriasPresentes[dc].descricaoCategoria}</a>
	</div>
	<div class="colunaTransparente" >
		<a href="{$includePATH}admin/page_edit.php?mode=upCat&id={$idPagina}&idCat={$categoriasPresentes[dc].idCategoria}"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/up_arrow.gif"></a>
	</div>
	<div class="colunaTransparente" >
		<a href="{$includePATH}admin/page_edit.php?mode=downCat&id={$idPagina}&idCat={$categoriasPresentes[dc].idCategoria}"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/down_arrow.gif"></a>
	</div>
	<div class="colunaTransparente" >
		<a href="{$includePATH}admin/page_edit.php?mode=rmCat&id={$idPagina}&idCat={$categoriasPresentes[dc].idCategoria}"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/delete.gif"></a>
	</div>
	<div class="coluna" >
			{if $categoriasPresentes[dc].categoriaRestrita == 1}Sim [{$categoriasPresentes[dc].restricaoCategoria}]{else}N&atilde;o{/if}
	</div>
</p>
{/section}
<div class="subTitulo">{$LANG.novaCategoria}:</div>
<div class="fortune">
<form id="nwCat" action="{$includePATH}admin/page_edit.php?mode=inCat&id={$idPagina}" method="POST">
	<select id="categoriaSelector" name="categoriaSelector">
	{section name=dnc loop=$categoriasAusentes}
		<option value="{$categoriasAusentes[dnc].idCategoria}">{$categoriasAusentes[dnc].descricaoCategoria}</option>
	{/section}
	</select>
	<input type="submit" class="submit" name="go" value="{$LANG.incluir}">
</form>
</div>
{/if}

{include file="page_footer.tpl"}
