<form name="edTpt" id="edTpt" action="javascript: gravarElemento('edTpt')">
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$tituloPaginaAlternativo}{$tituloTabelaAlternativo}</div><p />
    <div class="itemLateral">{$LANG.hp_templates_descricaoTemplate}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoTemplate" placeholder="{$LANG.hp_templates_Placeholder_descricaoTemplate}" value="{$elemento.descricaoTemplate}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_templates_nomeTemplate}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="nomeTemplate" placeholder="{$LANG.hp_templates_Placeholder_nomeTemplate}" value="{$elemento.nomeTemplate}" tabindex="2" /></div>
    <div class="itemLateral" style="width: 99%; Text-Align: center;">
{if $criarElemento}
        <input type="hidden" name="mode" id="mode" value="criarTemplate" />
{else}
        <input type="hidden" name="mode" id="mode" value="salvarTemplate" />
        <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" tabindex="3" />
        <input type="button" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: ocultarFormDiv();" tabindex="4" />
    </div>
</form>
