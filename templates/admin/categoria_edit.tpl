{include file="page_header.tpl"}
<script type="text/javascript">
    function doAction(pressed) {
        mode = document.getElementById('mode').value;
        if (mode != 'crCat') {
            switch (pressed) {
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'svCat';
                    break;
                case '{$LANG.excluir}':
                    document.getElementById('mode').value = 'cfExCat';
                    break;
                case '{$LANG.novaCategoria}':
                    document.getElementById('mode').value = 'nwCat';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'slCat';
                    break;
            }
    } else {
        switch (pressed) {
            case '{$LANG.gravar}':
                document.getElementById('mode').value = 'crCat';
                break;
            case '{$LANG.cancelar}':
                document.getElementById('mode').value = 'slCat';
                break;
        }
    }
    document.edCat.submit();
}
</script>

<form id="edCat" name="edCat" method="POST" action="{$includePATH}admin/categoria_edit.php">
{if $criarCategoria}
	<input type="hidden" id="mode" name="mode" value="crCat" />
{else}
	<input type="hidden" id="mode" name="mode" value="svCat" />
	<input type="hidden" id="id" name="id" value="{$idPagina}" />
	<input type="hidden" id="idCat" name="idCat" value="{$idCategoria}" />
{/if}
	<div class="subTitulo">{$LANG.configuracao}</div><p />
	<div class="itemLateral">{$LANG.hp_categorias_DescricaoCategoria}</div>
	<div class="item"><input type="text" class="FormExtra" size=30 name="descricaoCategoria" placeholder="{$LANG.hp_categorias_Placeholder_DescricaoCategoria}" value="{$descricaoCategoria}" tabindex="1" /></div>
	<div class="itemLateral">{$LANG.hp_categorias_Label_Restricao}</div>
	<div class="item">
		<input id="categoriaRestrita" type="checkbox" name="categoriaRestrita" {if $categoriaRestrita == '1'}checked{/if} 
				onClick="javascript: document.getElementById('restricaoCategoria').disabled = !(document.getElementById('categoriaRestrita').checked);" />
		<label for="categoriaRestrita">{$LANG.hp_categorias_CategoriaRestrita}</label>
	</div>
	<div class="itemLateral">{$LANG.hp_categorias_RestricaoCategoria}</div>
	<div class="item">
		<input type="text" class="FormExtra" size=30 name="restricaoCategoria" id="restricaoCategoria" placeholder="{$LANG.hp_categorias_Placeholder_RestricaoCategoria}" value="{$restricaoCategoria}" tabindex="1" />
  		<script type="text/javascript">document.getElementById('restricaoCategoria').disabled = !(document.getElementById('categoriaRestrita').checked);</script> 
	</div>
	<div class="interior" style=" text-align: center; padding-top: 4pt;">
		<input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/> ::
{if !$criarCategoria}
		<input type="submit" name="go" id="go" value="{$LANG.excluir}" class="submit" onclick="doAction('{$LANG.excluir}')"/> ::
		<input type="submit" name="go" id="go" value="{$LANG.novaCategoria}" class="submit" onclick="doAction('{$LANG.novaCategoria}')"/> ::
{/if}
		<input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/> 
	</div>
</form>
{if !$criarCategoria}
<p>
<div class="subTitulo">{$LANG.grupos}</div>
<div class="tituloColuna">{$LANG.hp_grupos_DescricaoGrupo}</div>
<div class="tituloColuna">{$LANG.subir}</div>
<div class="tituloColuna">{$LANG.descer}</div>
<div class="tituloColuna">{$LANG.excluir}</div>
<div class="tituloColuna">{$LANG.tipoGrupo}</div>
<div class="tituloColuna">{$LANG.grupoRestrito}</div>
</p>
{section name=gp loop=$gruposPresentes}
<p>
	<div class="tituloColuna" style="clear: left;">
		<a href="{$includePATH}admin/grupo_edit.php?mode=edGrp&idGrp={$gruposPresentes[gp].idGrupo}">{$gruposPresentes[gp].descricaoGrupo|default:$LANG.semTitulo}</a>
	</div>
	<div class="colunaTransparente" >
		<a href="{$includePATH}admin/categoria_edit.php?mode=upGrp&idCat={$idCategoria}&idGrp={$gruposPresentes[gp].idGrupo}"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/up_arrow.gif"></a>
	</div>
	<div class="colunaTransparente" >
		<a href="{$includePATH}admin/categoria_edit.php?mode=downGrp&idCat={$idCategoria}&idGrp={$gruposPresentes[gp].idGrupo}"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/down_arrow.gif"></a>
	</div>
	<div class="colunaTransparente" >
		<a href="{$includePATH}admin/categoria_edit.php?mode=rmGrp&idCat={$idCategoria}&idGrp={$gruposPresentes[gp].idGrupo}"><img style="border: 0pt; width: 12px; height: 12px;" src="{$includePATH}imagens/delete.gif"></a>
  	</div>
	<div class="coluna" >
		{assign var="idTipoGrupo" value=$gruposPresentes[gp].idTipoGrupo}
		{$tiposGrupos[$idTipoGrupo]}
	</div>
	<div class="coluna" >
		{if $gruposPresentes[gp].grupoRestrito == 1}Sim [{$gruposPresentes[gp].restricaoGrupo}]{else}N&atilde;o{/if}
	</div>
</p>
{sectionelse}
<p>
	<div class="subTitulo">{$LANG.categoriavazia}</div>
</p>
{/section}
<div class="subTitulo">{$LANG.novoGrupo}:</div>
<div class="fortune">
<form id="nwCat" action="{$includePATH}admin/categoria_edit.php?mode=inGrp&idCat={$idCategoria}" method="POST">
	<select id="grupoSelector" name="grupoSelector">
	{section name=ne loop=$gruposAusentes}
		<option value="{$gruposAusentes[ne].idGrupo}">{$gruposAusentes[ne].descricaoGrupo|default:$LANG.semTitulo}</option>
	{/section}
	</select>
	<input type="submit" class="submit" name="go" value="{$LANG.incluir}">
</form>
</div>
{/if}

{include file="page_footer.tpl"}
