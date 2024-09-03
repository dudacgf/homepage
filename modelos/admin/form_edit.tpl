<form name="edFrm" id="edFrm" action="javascript: gravarElemento('edFrm')">
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$tituloPaginaAlternativo}{$tituloTabelaAlternativo}</div><p />
    <div class="itemLateral">{$LANG.hp_forms_nomeForm}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="nomeForm" placeholder="{$LANG.hp_forms_Placeholder_nomeForm}" value="{$elemento.nomeForm}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_forms_acao}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="acao" placeholder="{$LANG.hp_forms_Placeholder_acao}" value="{$elemento.acao}" tabindex="2" /></div>
    <div class="itemLateral">{$LANG.hp_forms_nomeCampo}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="nomeCampo" placeholder="{$LANG.hp_forms_Placeholder_nomeCampo}" value="{$elemento.nomeCampo}" tabindex="3" /></div>
    <div class="itemLateral">{$LANG.hp_forms_tamanhoCampo}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="tamanhoCampo" placeholder="{$LANG.hp_forms_Placeholder_tamanhoCampo}" value="{$elemento.tamanhoCampo}" tabindex="4" /></div>
    <div class="itemLateral">{$LANG.hp_forms_descricaoForm}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoForm" placeholder="{$LANG.hp_forms_Placeholder_descricaoForm}" value="{$elemento.descricaoForm}" tabindex="5" /></div>
    <div class="itemLateral" style="width: 99%; Text-Align: center;">
{if $criarElemento}
        <input type="hidden" name="mode" id="mode" value="criarForm" />
{else}
        <input type="hidden" name="mode" id="mode" value="salvarForm" />
        <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" tabindex="6" /> :: 
        <input type="button" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: ocultarFormDiv();" tabindex="7" />
    </div>
</form>
