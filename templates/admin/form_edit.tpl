<form name="edFrm" id="edFrm" action="javascript: groupAction('edFrm')">
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$tituloPaginaAlternativo}{$tituloTabelaAlternativo}</div><p />
    <div class="itemLateral">{$LANG.hp_forms_nomeForm}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="nomeForm" value="{$elemento.nomeForm}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_forms_acao}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="acao" value="{$elemento.acao}" tabindex="2" /></div>
    <div class="itemLateral">{$LANG.hp_forms_nomeCampo}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="nomeCampo" value="{$elemento.nomeCampo}" tabindex="3" /></div>
    <div class="itemLateral">{$LANG.hp_forms_tamanhoCampo}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="tamanhoCampo" value="{$elemento.tamanhoCampo}" tabindex="4" /></div>
    <div class="itemLateral">{$LANG.hp_forms_descricaoForm}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoForm" value="{$elemento.descricaoForm}" tabindex="5" /></div>
    <div class="itemLateral" style="width: 99%; Text-Align: center;">
{if $criarElemento}
        <input type="hidden" name="mode" id="mode" value="crFrm" />
{else}
        <input type="hidden" name="mode" id="mode" value="svFrm" />
        <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" tabindex="6" /> :: 
        <input type="button" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: ocultarFormDiv();" tabindex="7" />
    </div>
</form>
