{foreach from=$elementos item=elemento}
    <div class="tituloColuna" style="clear: left;">
        <div class="click_div" onClick="editarElemento('editarElemento', {$elemento.idElemento}, {$grupo.idGrupo});">{$elemento.descricaoElemento|default:"[sem título]"}</div>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarElemento('ascenderElemento', {$elemento.idElemento}, {$grupo.idGrupo});">
            <i class="fa-solid fa-circle-arrow-up" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarElemento('descenderElemento', {$elemento.idElemento}, {$grupo.idGrupo});">
            <i class="fa-solid fa-circle-arrow-down" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarElemento('excluirElemento', {$elemento.idElemento}, {$grupo.idGrupo});">
            <i class="fa-solid fa-circle-xmark" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="coluna" >
        {assign var="idTipoElemento" value=$elemento.tipoElemento}
        {$tiposElementos[$idTipoElemento]}
    </div>
{/foreach}