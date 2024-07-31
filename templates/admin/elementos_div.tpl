{section name=el loop=$elementos}
    <div class="tituloColuna" style="clear: left;">
        <div class="click_div" onClick="editarElemento('edElm', {$elementos[el].idElemento}, {$grupo.idGrupo});">{$elementos[el].descricaoElemento|default:"[sem título]"}</div>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarElemento('upElm', {$elementos[el].idElemento}, {$grupo.idGrupo});">
            <i class="fa-solid fa-circle-arrow-up fa-sm" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarElemento('dwElm', {$elementos[el].idElemento}, {$grupo.idGrupo});">
            <i class="fa-solid fa-circle-arrow-down fa-sm" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="colunaTransparente" >
        <div class="click_div" onClick="editarElemento('exElm', {$elementos[el].idElemento}, {$grupo.idGrupo});">
            <i class="fa-solid fa-circle-xmark fa-sm" style="color: var(--theme-dark);"></i>
        </div>
    </div>
    <div class="coluna" >
        {assign var="idTipoElemento" value=$elementos[el].tipoElemento}
        {$tiposElementos[$idTipoElemento]}
    </div>
{/section}
