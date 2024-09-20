{section name=gp loop=$gruposPresentes}
<div class="content left">
	<div class="tituloColuna" style="clear: left;">
		<a href="{$includePATH}admin/grupo_edit.php?mode=edGrp&idGrp={$gruposPresentes[gp].idGrupo}">{$gruposPresentes[gp].descricaoGrupo|default:$LANG.semTitulo}</a>
	</div>
	<div class="colunaTransparente" >
        <div class="click_div" onclick="editarGrupo('ascenderGrupo', {$idCategoria}, {$gruposPresentes[gp].idGrupo});">
            <i class="fa-solid fa-circle-arrow-up" style="color: var(--cor-dark);"></i>
        </div>
	</div>
	<div class="colunaTransparente" >
        <div class="click_div" onclick="editarGrupo('descenderGrupo', {$idCategoria}, {$gruposPresentes[gp].idGrupo});">
            <i class="fa-solid fa-circle-arrow-down" style="color: var(--cor-dark);"></i>
        </div>
	</div>
	<div class="colunaTransparente" >
        <div class="click_div" onclick="editarGrupo('excluirGrupo', {$idCategoria}, {$gruposPresentes[gp].idGrupo});">
            <i class="fa-solid fa-circle-xmark" style="color: var(--cor-dark);"></i>
        </div>
  	</div>
	<div class="coluna" >
		{assign var="idTipoGrupo" value=$gruposPresentes[gp].idTipoGrupo}
		{$tiposGrupos[$idTipoGrupo]}
	</div>
	<div class="coluna" >
		{if $gruposPresentes[gp].grupoRestrito == 1}Sim [{$gruposPresentes[gp].restricaoGrupo}]{else}N&atilde;o{/if}
	</div>
</div>
{sectionelse}
	<div class="subTitulo">{$LANG.categoriavazia}</div>
{/section}
<div class="content left">
<div class="subTitulo">{$LANG.novoGrupo}:</div>
<form id="nwCat">
<div style="display: flex; float: left; align-items: center; padding-left: 10px;">
	<select class="novoFilho" id="grupoSelector" name="grupoSelector">
	{section name=ne loop=$gruposAusentes}
		<option value="{$gruposAusentes[ne].idGrupo}">{$gruposAusentes[ne].descricaoGrupo|default:$LANG.semTitulo}</option>
	{/section}
	</select>
	<input type="button" class="submit" onclick="editarGrupo('incluirGrupo', {$idCategoria}, document.getElementById('grupoSelector').value)" value="{$LANG.incluir}" style="margin-top: 2px; margin-bottom: 2px;">
</div>
</form>
</div>
