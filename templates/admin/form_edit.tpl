{include file="page_header.tpl"}

<script type="text/javascript">
<!--
    function doAction(pressed) {ldelim}
        mode = document.getElementById('mode').value;
        if (mode == 'crFrm') {ldelim}
            switch (pressed) {ldelim}
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'crFrm';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'clWnd';
                    break;
            {rdelim}
        {rdelim} else {ldelim}
            switch (pressed) {ldelim}
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'svFrm';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'clWnd';
                    break;
            {rdelim}
        {rdelim}
        document.edFrm.submit();
    {rdelim}

-->
</script>
<form name="edFrm" id="edFrm" action="elemento_edit.php">
{if $criarElemento}
    <input type="hidden" name="mode" id="mode" value="crFrm" />
{else}
    <input type="hidden" name="mode" id="mode" value="svFrm" />
    <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$LANG.configuracao}</div><p />
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
        <input type="submit" class="submit" name="go" value="{$LANG.gravar}" onclick="javascript: doAction('{$LANG.gravar}')" tabindex="6" /> :: 
        <input type="submit" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: doAction('{$LANG.cancelar}')"  tabindex="7" />
    </div>
</form>
{include file="page_footer.tpl"}

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
