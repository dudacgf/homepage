{include file="page_header.tpl"}
<body class="{$temaPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>

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
    <div class="itemLateral" style="padding: 5px;">{$LANG.hp_grupos_DescricaoGrupo}</div>
    <div class="item" style="padding: 0;">
        <input type="text" class="FormExtra" size=30 name="descricaoGrupo" placeholder="{$LANG.hp_grupos_Placeholder_DescricaoGrupo}" value="{$grupo.descricaoGrupo}" tabindex="1"  style="padding: 0; padding-left: 3px;" />
    </div>
    <div class="itemLateral" style="padding: 5px;">{$LANG.hp_grupos_TipoGrupo}</div>
    <div class="item" style="padding: 0;">
        <select style="margin: 0; padding: 1px; padding-left: 3px; width: 122pt;" name="idTipoGrupo" id="idTipoGrupo" >
         {foreach key=tg item=tipoGrupo from=$tiposGrupos}
            <option value="{$tg}" {if $tg == $grupo.idTipoGrupo}selected="selected"{/if}>{$tipoGrupo}</option>
        {/foreach}
        </select>
    </div>
    <div class="itemLateral" style="padding: 5px;">{$LANG.hp_grupos_Label_Restricao}</div>
    <div class="item" style="padding: 0;">
        <input id="grupoRestrito" type="checkbox" name="grupoRestrito" {if $grupo.grupoRestrito == '1'}checked{/if} 
                onClick="javascript: document.getElementById('restricaoGrupo').disabled = !(document.getElementById('grupoRestrito').checked);"  style="padding: 0; padding-left: 3px;" />
        <label for="grupoRestrito">{$LANG.hp_grupos_GrupoRestrito}</label>
    </div>
    <div class="itemLateral" style="padding: 5px;">{$LANG.hp_grupos_RestricaoGrupo}</div>
    <div class="item" style="padding: 0;">
        <input type="text" class="FormExtra" size=30 name="restricaoGrupo" id="restricaoGrupo" placeholder="{$LANG.hp_grupos_Placeholder_RestricaoGrupo}" value="{$grupo.restricaoGrupo}" tabindex="1" style="padding: 0; padding-left: 3px;" />
        <script type="text/javascript">document.getElementById('restricaoGrupo').disabled = !(document.getElementById('grupoRestrito').checked);</script> 
    </div>
    <div class="interior" style=" text-align: center; padding-top: 4pt;">
{if $criarGrupo}
        <input type="hidden" id="mode" name="mode" value="crGrp" />
        <input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/>
        <input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/>
{else}
        <input type="hidden" id="mode" name="mode" value="svGrp" />
        <input type="hidden" id="idGrp" name="idGrp" value="{$grupo.idGrupo}" />
        <input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/>
        <input type="button" name="go" id="go" value="{$LANG.excluir}" class="submit" onclick="doAction('{$LANG.excluir}')"/>
        <input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/>
{/if}
    </div>
</form>
{if !$criarGrupo}
<p>
<div class="subTitulo">{$LANG.elementos}</div>
<div class="tituloColuna">{$LANG.hp_elementos_Descricao}<div class="fa-arrow-down-short-wide fawLeftPointer" onClick="editarElemento('redefinirPosicoesElementos', 0, {$grupo.idGrupo});" title="redefinir posições de elementos"></div></div>
<div class="tituloColuna">{$LANG.subir}</div>
<div class="tituloColuna">{$LANG.descer}</div>
<div class="tituloColuna">{$LANG.excluir}</div>
<div class="tituloColuna">{$LANG.tipoElemento}</div>
<p>
<div id="elementos_div" style="margin-top: 5px; margin-bottom: 20px;">
{include file="admin/elementos_div.tpl"}
</div>
<div class="subTitulo" style="margin-bottom: 5px;">{$LANG.novosElementos}:</div>
<div class="menuBarra" style="">
    <div class="menuBarraItem fa-link" onClick="editarElemento('novoLink', 0, {$grupo.idGrupo});">
        {$LANG.novoLink}
    </div>
    <div class="menuBarraItem fa-table-cells-large" onClick="editarElemento('novoForm', 0, {$grupo.idGrupo});">
        {$LANG.novoForm}
    </div> 
    <div class="menuBarraItem fa-arrows-up-to-line" onClick="editarElemento('novoSeparador', 0, {$grupo.idGrupo});">
        {$LANG.novoSeparador}
    </div> 
    <div class="menuBarraItem fa-image" onClick="editarElemento('novaImagem', 0, {$grupo.idGrupo});">
        {$LANG.novaImagem}
    </div>
    <div class="menuBarraItem fa-rectangle-list" onClick="editarElemento('novoTemplate', 0, {$grupo.idGrupo});">
        {$LANG.novoTemplate}
    </div>
</div>
{/if}
{include file="page_footer.tpl"}
