<form name="edImg" id="edImg" action="javascript: groupAction('edImg')">
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$tituloPaginaAlternativo}{$tituloTabelaAlternativo}</div><p />
    <div class="itemLateral">{$LANG.hp_imagens_descricaoImagem}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoImagem" value="{$elemento.descricaoImagem}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_imagens_urlImagem}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="urlImagem" value="{$elemento.urlImagem}" tabindex="2" /></div>
    <div class="itemLateral" style="width: 99%; Text-Align: center;">
        <input id="localLink" type="checkbox" name="localLink" {if $elemento.localLink == '1'}checked{/if} tabindex="3"/>
        <label for="localLink">{$LANG.hp_imagens_localLink}</label>
    </div>
    <div class="itemLateral" colspan="10" style="width: 99%; margin-top: 5px; Text-Align: center;">
{if $criarElemento}
       <input type="hidden" name="mode" id="mode" value="crImg" />
{else}
       <input type="hidden" name="mode" id="mode" value="svImg" />
       <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" tabindex="4" /> :: 
        <input type="button" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: ocultarFormDiv();" tabindex="5" />
    </div>
</form>
