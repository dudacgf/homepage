<form name="edSrp" id="edSrp" class="edit" action="javascript: gravarElemento('edSrp')">
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$tituloPaginaAlternativo}{$tituloTabelaAlternativo}</div><p />
    <div class="itemLateral">{$LANG.hp_separadores_descricaoSeparador}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoSeparador" placeholder="{$LANG.hp_separadores_Placeholder_descricaoSeparador}" value="{$elemento.descricaoSeparador}" tabindex="1" /></div>
    <div class=barra>
        <input id="breakBefore" type="checkbox" name="breakBefore" {if $elemento.breakBefore == '1'}checked{/if} tabindex="2"/>
        <label for="breakBefore">{$LANG.hp_separadores_breakBefore}</label>
    </div>
    <div class=barra>
{if $criarElemento}
        <input type="hidden" name="mode" id="mode" value="criarSeparador" />
{else}
        <input type="hidden" name="mode" id="mode" value="salvarSeparador" />
        <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" tabindex="3" />
        <input type="button" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: ocultarFormDiv();" tabindex="4" />
    </div>
</form>
