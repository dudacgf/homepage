<form name="edLnk" id="edLnk" action="javascript: groupAction('edLnk');">
    <input type="hidden" name="idGrp" id="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$tituloPaginaAlternativo}{$tituloTabelaAlternativo}</div><p />
    <div class="itemLateral">{$LANG.hp_links_descricaoLink}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoLink" id="descricaoLink" placeholder="{$LANG.hp_links_Placeholder_descricaoLink}" value="{$elemento.descricaoLink}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_links_linkURL}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="linkURL" id="linkURL" placeholder="{$LANG.hp_links_Placeholder_linkURL}" value="{$elemento.linkURL}" tabindex="2" /></div>
    <div class="itemLateral">{$LANG.hp_links_dicaLink}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="dicaLink" id="dicaLink" placeholder="{$LANG.hp_links_Placeholder_dicaLink}" value="{$elemento.dicaLink}" tabindex="3" /></div>
    <div class="itemLateral" style="width: 99%; text-align: center;">
        <input id="urlElementoSSL" type="checkbox" name="urlElementoSSL" {if $elemento.urlElementoSSL == '1'}checked{/if} tabindex="4"/>
        <label for="urlElementoSSL">{$LANG.hp_links_urlElementoSSL}</label>
        <input id="urlElementoSVN" type="checkbox" name="urlElementoSVN" {if $elemento.urlElementoSVN == '1'}checked{/if} tabindex="4"/>
        <label for="urlElementoSVN">{$LANG.hp_links_urlElementoSVN}</label>
        <input id="localLink" type="checkbox" name="localLink" {if $elemento.localLink == '1'}checked{/if} tabindex="4"/>
        <label for="localLink">{$LANG.hp_links_localLink}</label>
    </div>
    <div class="itemLateral">{$LANG.hp_links_targetLink}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="targetLink" id="targetLink" value="{$elemento.targetLink}" tabindex="5" /></div>
    <div class="itemLateral" style="width: 99%; text-align: center;">
{if $criarElemento}
        <input type="hidden" name="mode" id="mode" value="criarLink" />
{else}
        <input type="hidden" name="mode" id="mode" value="salvarLink" />
        <input type="hidden" name="idElm" id="idElm" value="{$elemento.idElemento}" />
{/if}
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" tabindex="6" /> :: 
        <input type="button" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: ocultarFormDiv();" tabindex="7" />
    </div>
</form>
