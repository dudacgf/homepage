{include file="page_header.tpl"}

<script type="text/javascript">
function doAction(pressed) {
    mode = document.getElementById('mode').value;
    if (mode != 'crGrp') {
        switch (pressed) {
            case '{$LANG.gravar}':
                document.getElementById('mode').value = 'svGrp';
                break;
            case '{$LANG.excluir}':
                response = confirm('Confirma exclusão do grupo?');
                if (!response) 
                    return;
                document.getElementById('mode').value = 'excluirGrupo';
                break;
            case '{$LANG.novoGrupo}':
                document.getElementById('mode').value = 'nwGrp';
                break;
            case '{$LANG.cancelar}':
                document.getElementById('mode').value = 'slGrp';
                break;
        }
    } else {
        switch (pressed) {
            case '{$LANG.gravar}':
                document.getElementById('mode').value = 'crGrp';
                break;
            case '{$LANG.excluir}':
                response = confirm('Confirma exclusão do grupo?');
                if (!response) 
                    return;
                document.getElementById('mode').value = 'excluirGrupo';
                break;
            case '{$LANG.cancelar}':
                document.getElementById('mode').value = 'slGrp';
                break;
        }
    }
    document.edGrp.submit();
}
</script>

<form id="edGrp" action="{$includePATH}admin/grupo_edit.php" name="edGrp" method="POST">
    <div class="subTitulo">{$LANG.configuracao}</div><p />
    <div class="itemLateral">{$LANG.hp_grupos_DescricaoGrupo}</div>
    <div class="item"><input type="text" class="FormExtra" size=30 name="descricaoGrupo" placeholder="{$LANG.hp_grupos_Placeholder_DescricaoGrupo}" value="{$grupo.descricaoGrupo}" tabindex="1" /></div>
    <div class="itemLateral">{$LANG.hp_grupos_TipoGrupo}</div>
    <div class="item">
        <select style=" width: 122pt;" name="idTipoGrupo" id="idTipoGrupo" >
         {foreach key=tg item=tipoGrupo from=$tiposGrupos}
            <option value="{$tg}" {if $tg == $grupo.idTipoGrupo}selected="selected"{/if}>{$tipoGrupo}</option>
        {/foreach}
        </select>
    </div>
    <div class="itemLateral">{$LANG.hp_grupos_Label_Restricao}</div>
    <div class="item">
        <input id="grupoRestrito" type="checkbox" name="grupoRestrito" {if $grupo.grupoRestrito == '1'}checked{/if} 
                onClick="javascript: document.getElementById('restricaoGrupo').disabled = !(document.getElementById('grupoRestrito').checked);" />
        <label for="grupoRestrito">{$LANG.hp_grupos_GrupoRestrito}</label>
    </div>
    <div class="itemLateral">{$LANG.hp_grupos_RestricaoGrupo}</div>
    <div class="item">
        <input type="text" class="FormExtra" size=30 name="restricaoGrupo" id="restricaoGrupo" placeholder="{$LANG.hp_grupos_Placeholder_RestricaoGrupo}" value="{$grupo.restricaoGrupo}" tabindex="1" />
        <script type="text/javascript">document.getElementById('restricaoGrupo').disabled = !(document.getElementById('grupoRestrito').checked);</script> 
    </div>
    <div class="interior" style=" text-align: center; padding-top: 4pt;">
{if $criarGrupo}
        <input type="hidden" id="mode" name="mode" value="crGrp" /> :: 
        <input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/> ::
        <input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/> :: 
{else}
        <input type="hidden" id="mode" name="mode" value="svGrp" />
        <input type="hidden" id="idGrp" name="idGrp" value="{$grupo.idGrupo}" /> :: 
        <input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/> ::
        <input type="button" name="go" id="go" value="{$LANG.excluir}" class="submit" onclick="doAction('{$LANG.excluir}')"/> ::
        <input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/> :: 
{/if}
    </div>
</form>
{if !$criarGrupo}
<p>
<div class="subTitulo">{$LANG.elementos}</div>
<div class="tituloColuna">{$LANG.hp_elementos_Descricao}</div>
<div class="tituloColuna">{$LANG.subir}</div>
<div class="tituloColuna">{$LANG.descer}</div>
<div class="tituloColuna">{$LANG.excluir}</div>
<div class="tituloColuna">{$LANG.tipoElemento}</div>
<p>
<div id="elementos_div">
{include file="admin/elementos_div.tpl"}
</div>
<div class="subTitulo">{$LANG.novosElementos}:</div>
<div class="novoFilho" style="text-align: center; padding: 0; margin: 0; height: 30px;">
    <div class="newItem_div" onClick="editarElemento('novoLink', 0, {$grupo.idGrupo});">
        :: {$LANG.novoLink} ::
    </div>
    <div class="newItem_div" onClick="editarElemento('novoForm', 0, {$grupo.idGrupo});">
        :: {$LANG.novoForm} ::
    </div> 
    <div class="newItem_div" onClick="editarElemento('novoSeparador', 0, {$grupo.idGrupo});">
        :: {$LANG.novoSeparador} ::
    </div> 
    <div class="newItem_div" onClick="editarElemento('novaImagem', 0, {$grupo.idGrupo});">
        :: {$LANG.novaImagem} ::
    </div>
    <div class="newItem_div" onClick="editarElemento('novoTemplate', 0, {$grupo.idGrupo});">
        :: {$LANG.novoTemplate} ::
    </div>
</div>
{/if}
{include file="page_footer.tpl"}
