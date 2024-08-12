{section name=gp loop=$gruposPresentes}
	<div class="tituloColuna" style="clear: left;">
		<a href="{$includePATH}admin/grupo_edit.php?mode=edGrp&idGrp={$gruposPresentes[gp].idGrupo}">{$gruposPresentes[gp].descricaoGrupo|default:$LANG.semTitulo}</a>
	</div>
	<div class="colunaTransparente" >
        <div class="click_div" onclick="editarGrupo('descenderGrupo', {$idCategoria}, {$gruposPresentes[gp].idGrupo});">
            <i class="fa-solid fa-circle-arrow-up" style="color: var(--theme-dark);"></i>
        </div>
	</div>
	<div class="colunaTransparente" >
        <div class="click_div" onclick="editarGrupo('descenderGrupo', {$idCategoria}, {$gruposPresentes[gp].idGrupo});">
            <i class="fa-solid fa-circle-arrow-down" style="color: var(--theme-dark);"></i>
        </div>
	</div>
	<div class="colunaTransparente" >
        <div class="click_div" onclick="editarGrupo('excluirGrupo', {$idCategoria}, {$gruposPresentes[gp].idGrupo});">
            <i class="fa-solid fa-circle-xmark" style="color: var(--theme-dark);"></i>
        </div>
  	</div>
	<div class="coluna" >
		{assign var="idTipoGrupo" value=$gruposPresentes[gp].idTipoGrupo}
		{$tiposGrupos[$idTipoGrupo]}
	</div>
	<div class="coluna" >
		{if $gruposPresentes[gp].grupoRestrito == 1}Sim [{$gruposPresentes[gp].restricaoGrupo}]{else}N&atilde;o{/if}
	</div>
{sectionelse}
	<div class="subTitulo">{$LANG.categoriavazia}</div>
{/section}
<div class="subTitulo">{$LANG.novoGrupo}:</div>
<div class="fortune">
<form id="nwCat">
	<select id="grupoSelector" name="grupoSelector">
	{section name=ne loop=$gruposAusentes}
		<option value="{$gruposAusentes[ne].idGrupo}">{$gruposAusentes[ne].descricaoGrupo|default:$LANG.semTitulo}</option>
	{/section}
	</select>
	<input type="button" class="submit" onclick="editarGrupo('incluirGrupo', {$idCategoria}, document.getElementById('grupoSelector').value)" value="{$LANG.incluir}">
</form>
</div>
