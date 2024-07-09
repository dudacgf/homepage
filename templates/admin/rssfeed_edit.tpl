{include file="page_header.tpl"}

<script type="text/javascript">
<!--
    function doAction(pressed) {ldelim}
        mode = document.getElementById('mode').value;
        if (mode == 'crRss') {ldelim}
            switch (pressed) {ldelim}
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'crRss';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'clWnd';
                    break;
            {rdelim}
        {rdelim} else {ldelim}
            switch (pressed) {ldelim}
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'svRss';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'clWnd';
                    break;
            {rdelim}
        {rdelim}
        document.edRss.submit();
    {rdelim}

-->
</script>
<form name="edRss" id="edRss" action="elemento_edit.php">
{if $criarElemento}
    <input type="hidden" name="mode" id="mode" value="crRss" />
{else}
    <input type="hidden" name="mode" id="mode" value="svRss" />
    <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$LANG.configuracao}</div><p />
    <div class="itemLateral">{$LANG.hp_rssfeeds_rssURL}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="rssURL" value="{$elemento.rssURL}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_rssfeeds_rssItemNum}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="rssItemNum" value="{$elemento.rssItemNum}" tabindex="2" /></div>
    <div class="itemLateral" style="width: 99%; Text-Align: center;">
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" onclick="javascript: doAction('{$LANG.gravar}')" tabindex="3" /> :: 
        <input type="submit" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: doAction('{$LANG.cancelar}')" tabindex="4" />
    </div>
</form>
{include file="page_footer.tpl"}

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
