{include file="page_header.tpl"}

<script type="text/javascript">
<!--
    function doAction(pressed) {ldelim}
        mode = document.getElementById('mode').value;
        if (mode == 'crSrp') {ldelim}
            switch (pressed) {ldelim}
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'crSrp';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'clWnd';
                    break;
            {rdelim}
        {rdelim} else {ldelim}
            switch (pressed) {ldelim}
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'svSrp';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'clWnd';
                    break;
            {rdelim}
        {rdelim}
        document.edSrp.submit();
    {rdelim}

-->
</script>
<form name="edSrp" id="edSrp" action="elemento_edit.php">
{if $criarElemento}
    <input type="hidden" name="mode" id="mode" value="crSrp" />
{else}
    <input type="hidden" name="mode" id="mode" value="svSrp" />
    <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$LANG.configuracao}</div><p />
    <div class="itemLateral">{$LANG.hp_separadores_descricaoSeparador}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoSeparador" value="{$elemento.descricaoSeparador}" tabindex="1" /></div>
    <div class="itemLateral" style="width: 99%; Text-Align: left;">
        <input id="breakBefore" type="checkbox" name="breakBefore" {if $elemento.breakBefore == '1'}checked{/if} tabindex="2"/>
        <label for="breakBefore">{$LANG.hp_separadores_breakBefore}</label>
    </div>
    <div class="itemLateral" style="width: 99%; Text-Align: center;">
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" onclick="javascript: doAction('{$LANG.gravar}')" tabindex="3" /> :: 
        <input type="submit" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: doAction('{$LANG.cancelar}')" tabindex="4" />
    </div>
</form>
{include file="page_footer.tpl"}

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
