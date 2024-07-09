{include file="page_header.tpl"}

<script type="text/javascript">
<!--
    function doAction(pressed) {ldelim}
        mode = document.getElementById('mode').value;
        if (mode == 'crImg') {ldelim}
            switch (pressed) {ldelim}
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'crImg';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'clWnd';
                    break;
            {rdelim}
        {rdelim} else {ldelim}
            switch (pressed) {ldelim}
                case '{$LANG.gravar}':
                    document.getElementById('mode').value = 'svImg';
                    break;
                case '{$LANG.cancelar}':
                    document.getElementById('mode').value = 'clWnd';
                    break;
            {rdelim}
        {rdelim}
        document.edImg.submit();
    {rdelim}

-->
</script>
<form name="edImg" id="edImg" action="elemento_edit.php">
{if $criarElemento}
    <input type="hidden" name="mode" id="mode" value="crImg" />
{else}
    <input type="hidden" name="mode" id="mode" value="svImg" />
    <input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
    <input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
    <div class="subTitulo">{$LANG.configuracao}</div><p />
    <div class="itemLateral">{$LANG.hp_imagens_descricaoImagem}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoImagem" value="{$elemento.descricaoImagem}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_imagens_urlImagem}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="urlImagem" value="{$elemento.urlImagem}" tabindex="2" /></div>
    <div class="itemLateral" style="width: 99%; Text-Align: center;">
        <input id="localLink" type="checkbox" name="localLink" {if $elemento.localLink == '1'}checked{/if} tabindex="3"/>
        <label for="localLink">{$LANG.hp_imagens_localLink}</label>
    </div>
    <div class="itemLateral" colspan="10" style="width: 99%; margin-top: 5px; Text-Align: center;">
         <input type="submit" class="submit" name="go" value="{$LANG.gravar}" onclick="javascript: doAction('{$LANG.gravar}')" tabindex="4" /> :: 
         <input type="submit" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: doAction('{$LANG.cancelar}')" tabindex="5" />
    </div>
</form>
{include file="page_footer.tpl"}

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
