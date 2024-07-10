{include file="page_header.tpl"}

<script type="text/javascript">
<!--
	function doAction(pressed) {ldelim}
		mode = document.getElementById('mode').value;
		if (mode != 'crGrp') {ldelim}
			switch (pressed) {ldelim}
				case '{$LANG.gravar}':
					document.getElementById('mode').value = 'svGrp';
					break;
				case '{$LANG.excluir}':
					document.getElementById('mode').value = 'cfExGrp';
					break;
				case '{$LANG.novoGrupo}':
					document.getElementById('mode').value = 'nwGrp';
					break;
				case '{$LANG.cancelar}':
					document.getElementById('mode').value = 'slGrp';
					break;
			{rdelim}
		{rdelim} else {ldelim}
			switch (pressed) {ldelim}
				case '{$LANG.gravar}':
					document.getElementById('mode').value = 'crGrp';
					break;
				case '{$LANG.cancelar}':
					document.getElementById('mode').value = 'slGrp';
					break;
			{rdelim}
		{rdelim}
		document.edGrp.submit();
	{rdelim}

	function editarElemento(mode, idGrupo, idElemento) {ldelim}
		novodoc = window.open('{$includePATH}admin/elemento_edit.php?mode=' + mode + '&idGrp=' + idGrupo + '&idElm=' + idElemento, '_blank', 
					'top=200, left=200, directories=no, height=400, width=600, location=no, menubar=no, resizable=yes, scrollbars=no, status=no, toolbar=no', false);
		novodoc.close;
	{rdelim}
-->
</script>

<form id="edGrp" action="{$includePATH}admin/grupo_edit.php" name="edGrp" method="POST">
{if $criarGrupo}
	<input type="hidden" id="mode" name="mode" value="crGrp" />
{else}
	<input type="hidden" id="mode" name="mode" value="svGrp" />
	<input type="hidden" id="id" name="id" value="{$idPagina}" />
	<input type="hidden" id="idGrp" name="idGrp" value="{$grupo.idGrupo}" />
{/if}
	<div class="subTitulo">{$LANG.configuracao}</div><p />
	<div class="itemLateral">{$LANG.hp_grupos_DescricaoGrupo}</div>
	<div class="item"><input type="text" class="FormExtra" size=30 name="descricaoGrupo" value="{$grupo.descricaoGrupo}" tabindex="1" /></div>
	<div class="itemLateral">{$LANG.hp_grupos_TipoGrupo}</div>
	<div class="item">
		<select style=" width: 122pt;" name="idTipoGrupo" id="idTipoGrupo" >
	 	{foreach key=tg item=tipoGrupo from=$tiposGrupos}
			<option value="{$tg}" {if $tg == $grupo.idTipoGrupo}selected="selected"{/if}>{$tipoGrupo}</option>
		{/foreach}
		</select>
	</div>
	<div class="itemLateral">{$LANG.hp_grupos_Label_Restricao}</div>
	<div class="item">
		<input id="grupoRestrito" type="checkbox" name="grupoRestrito" {if $grupo.grupoRestrito == '1'}checked{/if} 
				onClick="javascript: document.getElementById('restricaoGrupo').disabled = !(document.getElementById('grupoRestrito').checked);" />
		<label for="grupoRestrito">{$LANG.hp_grupos_GrupoRestrito}</label>
	</div>
	<div class="itemLateral">{$LANG.hp_grupos_RestricaoGrupo}</div>
	<div class="item">
		<input type="text" class="FormExtra" size=30 name="restricaoGrupo" id="restricaoGrupo" value="{$grupo.restricaoGrupo}" tabindex="1" /></div>
		<script type="text/javascript">document.getElementById('restricaoGrupo').disabled = !(document.getElementById('grupoRestrito').checked);</script> 
	</div>
	<div class="interior" style=" text-align: center; padding-top: 4pt;">
				<input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/> ::
{if !$criarGrupo}
				<input type="submit" name="go" id="go" value="{$LANG.excluir}" class="submit" onclick="doAction('{$LANG.excluir}')"/> ::
				<input type="submit" name="go" id="go" value="{$LANG.novoGrupo}" class="submit" onclick="doAction('{$LANG.novoGrupo}')"/> ::
{/if}
				<input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/> 
	</div>
</form>
{if !$criarGrupo}
<p>
<div class="subTitulo">{$LANG.elementos}</div>
<div class="tituloColuna">{$LANG.hp_elementos_Descricao}</div>
<div class="tituloColuna">{$LANG.subir}</div>
<div class="tituloColuna">{$LANG.descer}</div>
<div class="tituloColuna">{$LANG.excluir}</div>
<div class="tituloColuna">{$LANG.tipoElemento}</div>
</p>
{section name=el loop=$elementos}
<p>
	<div class="tituloColuna" style="clear: left;">
		<a href="javascript: editarElemento('edElm', {$grupo.idGrupo}, {$elementos[el].idElemento}) ;">{$elementos[el].descricaoElemento|default:"[sem t&iacute;tulo]"}</a>
	</div>
	<div class="colunaTransparente" >
		<a href="{$includePATH}admin/grupo_edit.php?mode=upElm&idGrp={$grupo.idGrupo}&idElm={$elementos[el].idElemento}"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/up_arrow.gif"></a>
	</div>
	<div class="colunaTransparente" >
		<a href="{$includePATH}admin/grupo_edit.php?mode=downElm&idGrp={$grupo.idGrupo}&idElm={$elementos[el].idElemento}"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/down_arrow.gif"></a>
	</div>
	<div class="colunaTransparente" >
		<a href="javascript: editarElemento('cfExElm', {$grupo.idGrupo}, {$elementos[el].idElemento});"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/delete.gif"></a>
	</div>
	<div class="coluna" >
		{assign var="idTipoElemento" value=$elementos[el].tipoElemento}
		{$tiposElementos[$idTipoElemento]}
	</div>
{/section}
<div class="subTitulo">{$LANG.novosElementos}:</div>
<div class="fortune" style="text-align: center;">
	<a href="javascript: editarElemento('nwLnk', {$grupo.idGrupo}, 0);">{$LANG.novoLink}</a> :: 
	<a href="javascript: editarElemento('nwFrm', {$grupo.idGrupo}, 0);">{$LANG.novoForm}</a> :: 
	<a href="javascript: editarElemento('nwSrp', {$grupo.idGrupo}, 0);">{$LANG.novoSeparador}</a> :: 
	<a href="javascript: editarElemento('nwImg', {$grupo.idGrupo}, 0);">{$LANG.novaImagem}</a> :: 
	<a href="javascript: editarElemento('nwRss', {$grupo.idGrupo}, 0);">{$LANG.novoRssFeed}</a> :: 
	<a href="javascript: editarElemento('nwTpt', {$grupo.idGrupo}, 0);">{$LANG.novoTemplate}</a> :: 
</div>
</div>
{/if}

{include file="page_footer.tpl"}
