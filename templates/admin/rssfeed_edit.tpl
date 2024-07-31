<form name="edRss" id="edRss" action="javascript: groupAction('edRss');">
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$tituloPaginaAlternativo}{$tituloTabelaAlternativo}</div><p />
    <div class="itemLateral">{$LANG.hp_rssfeeds_rssURL}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="rssURL" value="{$elemento.rssURL}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_rssfeeds_rssItemNum}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="rssItemNum" value="{$elemento.rssItemNum}" tabindex="2" /></div>
    <div class="itemLateral" style="width: 99%; Text-Align: center;">
{if $criarElemento}
        <input type="hidden" name="mode" id="mode" value="crRss" />
{else}
        <input type="hidden" name="mode" id="mode" value="svRss" />
        <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" onclick="javascript: doAction('{$LANG.gravar}')" tabindex="3" /> :: 
        <input type="button" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: ocultarFormDiv();" tabindex="4" />
    </div>
</form>
