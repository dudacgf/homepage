{section name=dc loop=$categoriasPresentes}
<p>
    <div class="tituloColuna" style="clear: left;">
        <a href="{$includePATH}admin/categoria_edit.php?mode=edCat&id={$idPagina}&idCat={$categoriasPresentes[dc].idCategoria}">{$categoriasPresentes[dc].descricaoCategoria}</a>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarCategoria('ascenderCategoria', {$idPagina}, {$categoriasPresentes[dc].idCategoria});">
            <i class="fa-solid fa-circle-arrow-up" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarCategoria('descenderCategoria', {$idPagina}, {$categoriasPresentes[dc].idCategoria});">
            <i class="fa-solid fa-circle-arrow-down" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarCategoria('excluirCategoria', {$idPagina}, {$categoriasPresentes[dc].idCategoria});">
            <i class="fa-solid fa-circle-xmark" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="coluna" >
            {if $categoriasPresentes[dc].categoriaRestrita == 1}Sim [{$categoriasPresentes[dc].restricaoCategoria}]{else}N&atilde;o{/if}
    </div>
<p>
{/section}
<div class="subTitulo">{$LANG.novaCategoria}:</div>
<div class="fortune">
<form id="nwCat" method="POST">
    <select id="categoriaSelector" name="categoriaSelector">
    {section name=dnc loop=$categoriasAusentes}
        <option value="{$categoriasAusentes[dnc].idCategoria}">{$categoriasAusentes[dnc].descricaoCategoria}</option>
    {/section}
    </select>
    <input type="button" class="submit" onClick="editarCategoria('incluirCategoria', {$idPagina}, document.getElementById('categoriaSelector').value);" value="{$LANG.incluir}">
</form>
</div>
