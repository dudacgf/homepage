{include file="page_header.tpl"}
<body id="theBody" class="{$classPagina}" onload="executarNoFrameTema([populaElementoCor, carregarPaletaAtual], false); {if isset($smarty.cookies.showAlerta)}createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');{/if}">
<script type="text/javascript">
function doAction(action) {
    if (action == '{$LANG.gravar}') {
        mode = document.getElementById('mode').value;
        if (mode = 'nwTema') 
            document.edTema.action = '{$includePATH}admin/tema_edit.php?mode=crTema';
        else
            document.edTema.action = '{$includePATH}admin/tema_edit.php?mode=svTema&idTema=' + document.getElementById('idTema').value; 
    } else if (action == '{$LANG.excluir}') {
        response = confirm('Confirma exclusão do tema?');
        if (!response) 
            return;
        document.edTema.action = '{$includePATH}admin/tema_edit.php?mode=exTema&idTema=' + document.getElementById('idTema').value; 
    }
    document.edTema.submit();
}

const executarNoFrameTema = (callFunctions, reloadFrame = true) => {
    try {
        var fdoc = window.frames['paginaTema'].contentDocument;
        var rt = fdoc.querySelector(':root');
    } catch (e) {
        return;
    }

    for (i = 0; i < callFunctions.length; i++)
        callFunctions[i](rt, '{$tema.nome}');

    if (reloadFrame) 
        fdoc.location.reload();
}
</script>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
<div class="content" style="text-align: left;">
<form id="edTema" name="edTema" method="POST">
    <div class="subTitulo">{$LANG.configuracao}</div><p />
    <div class="itemLateral">{$LANG.hp_temas_nome}</div>
    <div class="item">
        <input type="text" class="FormExtra" size=30 id="nome" name="nome" placeholder="{$LANG.hp_temas_Placeholder_nome}" value="{$tema.nome}" tabindex="1" {if !$criarTema}disabled{/if}/>
    </div>
    <div class="itemLateral">{$LANG.hp_temas_comentario}</div>
    <div class="item">
        <input type="text" class="FormExtra" size=30 id="comentario" name="comentario" placeholder="{$LANG.hp_temas_Placeholder_comentario}" value="{$tema.comentario}" tabindex="1" />
    </div>
{if $criarTema}
    <div class="itemLateral">{$LANG.hp_temas_derivarTema}</div>
    <div class="item">
        <select style=" width: 122pt;" name="temaBase" id="temaBase">
        {section name=t loop=$temas}
            <option value="{$temas[t].nome}" id="{$temas[t].id}" name="temaBase">{$temas[t].nome}</option>
        {/section}
        </select>
    </div>
{/if}
    <div class="interior" style=" text-align: center; padding-top: 4pt; align-items: center;">
{if !$criarTema}
        <input type="hidden" id="idTema" name="idTema" value="{$idTema}" />
        <input type="hidden" id="mode" name="mode" value="svTema" />
        <input type="button" id="{$LANG.excluir}" value="{$LANG.excluir}" class="submit" onclick="doAction('{$LANG.excluir}')"/>
{else}
        <input type="button" id="{$LANG.gravar}" onclick="doAction('{$LANG.gravar}');" value="{$LANG.gravar}">
        <input type="hidden" id="mode" name="mode" value="crTema" />
{/if}
        <input type="button" id="{$LANG.cancelar}" value="{$LANG.cancelar}" class="submit" onclick="window.location = '{$includePATH}admin/tema_select.php?';"/> 
    </div>
</form>
</div>
{if !$criarTema}
<div style="width: 100%; height: 20px; margin: 0; padding: 0; border: 0; background-color: var(--cor-tituloBB);"></div>
<input type="hidden" id="selectedColor" value="" />
<div class="content" style="display: flex;">
    <div class="columnBox" style="width: 43%; height: 100%; border: 0; margin-top: 1rem; display: flex; flex-flow: column; clear: top; float: left; margin: 0; padding: 20px; text-align: left;" >
        <div class="exemplo" style="flex: 1 1 auto; width: 100%;">
        {include file="admin/tema_cores.tpl"}
        </div>
    </div>
    <div class="columnBox" style="width: 57%; height: 100%; border: 0; border-left: 10px solid var(--cor-tituloBB); margin-top: 1rem; display: flex; flex-flow: column; clear: top; float: left; margin: 0; padding: 20px;" >
        <div class"cabecalho" style="flex: 0 1 auto; width: 100%;">
            <div class="menuBarra">
                <div class="menuBarraItem " onClick="executarNoFrameTema([restaurarTema]);">
                <span class="material-symbols-sharp">restore_page</span>
                Restaurar Tema
                </div> 
                <div class="menuBarraItem" onClick="executarNoFrameTema([salvarTema]);">
                <span class="material-symbols-sharp">save</span>
                Salvar Tema
                </div>
            </div>
        </div>
        <div class="exemplo" style="flex: 1 1 auto; width: 100%;">
            <iframe id="paginaTema" class="paginaTema" style="margin-top: 1rem; zoom: 88%; width: 80%; min-height: 59.5vmin; max-height: 130vmin;" src="{$includePATH}admin/tema_frame.php?idTema={$idTema}"></iframe>
        </div>
        <div class="rodape" style="margin-top: 5vmin; flex: 0 1 40px; width: 100%;">
        </div>
    </div>
</div>
{/if}
{include file="page_footer.tpl"}
