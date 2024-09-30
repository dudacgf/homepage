{assign var="edicaoPagina" value="1"}
{include file="page_header.tpl"}
<body id="theBody" class="{$temaPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
<script type="text/javascript">
function doAction(pressed) {
    mode = document.getElementById('mode').value;
    if (mode != 'crPag') {
        switch (pressed) {
            case '{$LANG.gravar}':
                document.edPag.action = '{$includePATH}admin/page_edit.php?mode=svPag&id=' + document.getElementById('id').value; 
                break;
            case '{$LANG.excluir}':
                response = confirm('Confirma exclusão da página?');
                if (!response) 
                    return;
                document.edPag.action = '{$includePATH}admin/page_edit.php?mode=exPag&id=' + document.getElementById('id').value; 
                break;
            case '{$LANG.novaPagina}':
                document.edPag.action = '{$includePATH}admin/page_edit.php?mode=nwPag';
                break;
            case '{$LANG.cancelar}':
                document.edPag.action = '{$includePATH}admin/page_edit.php?mode=slPag';
                break;
        }
    } else {
        switch (pressed) {
            case '{$LANG.gravar}':
                document.edPag.action = '{$includePATH}admin/page_edit.php?mode=crPag';
                break;
            case '{$LANG.cancelar}':
                document.edPag.action = '{$includePATH}admin/page_edit.php?mode=slPag';
                break;
        }
    }
    document.edPag.submit();
}

const exemploPaginaSrc = "{$includePATH}homepage.php?id={$idPagina}&gr=all";
function carregaEstilo(nomeEstilo) {
    const classURL = window.includePATH + 'temaPagina/' + nomeEstilo + '.css';
    const headRef = document.createElement('link');
    headRef.rel = "stylesheet";
    headRef.type = "text/css";
    headRef.href = classURL;
    document.getElementsByTagName("head")[0].appendChild(headRef);
    setTimeout(
        function () {
            for (i = 0; i < document.styleSheets.length-1; i++) { 
                if (document.styleSheets[i].href && document.styleSheets[i].href.includes(document.body.className)) { 
                    document.styleSheets[i].disabled = true; 
                }
            }
            document.body.className = nomeEstilo;
            document.getElementById('exemploPagina').src = exemploPaginaSrc + '&class=' + nomeEstilo;
        }, 200);
}
</script>

<div class="subTitulo">{$LANG.configuracao}</div>
<div class="content" style="display: flex">
    <div class="columnBox left" style="width: {if !$criarPagina}60%{else}100%{/if}">
        <form id="edPag" name="edPag" method="POST">
            <div class="itemLateral" style="padding: 5px;">{$LANG.hp_paginas_TituloPagina}</div>
            <div class="item" style="padding: 0;">
                <input type="text" class="FormExtra" size=30 name="tituloPagina" placeholder="{$LANG.hp_paginas_Placeholder_TituloPagina}" value="{$tituloPagina}" tabindex="1" style="padding: 0; padding-left: 3px;" />
            </div>
            <div class="itemLateral" style="padding: 5px;">{$LANG.hp_paginas_TituloTabela}</div>
            <div class="item" style="padding: 0;">
                <input type="text" class="FormExtra" size=30 name="tituloTabela" placeholder="{$LANG.hp_paginas_Placeholder_TituloTabela}" value="{$tituloTabela}" tabindex="2" style="padding: 0; padding-left: 3px;" />
            </div>
            <div class="itemLateral" style="padding: 5px;">{$LANG.hp_paginas_temaPagina}</div>
            <div class="item" style="padding: 0;">
                <select style="margin: 0; padding: 1px; padding-left: 3px; width: 122pt;" name="temaPagina" id="temaPagina" onChange="carregaEstilo(this.value);" tabindex="3">
                {section name=cp loop=$classNames}
                    <option value="{$classNames[cp]}" {if $classNames[cp] == $temaPagina}selected="selected"{/if} >{$classNames[cp]}</option>
                {/section}
                </select>
            </div>
            <div class="opcoes">
                <input id="displayFortune" type="checkbox" name="displayFortune" {if $displayFortune == '1'}checked{/if} />
                <label for="displayFortune">{$LANG.hp_paginas_displayFortune}</label>
                <input id="displayImagemTitulo" type="checkbox" name="displayImagemTitulo" {if $displayImagemTitulo == '1'}checked{/if} />
                <label for="displayImagemTitulo">{$LANG.hp_paginas_displayImagemTitulo}</label>
            </div>
            <div class=barra>
                <input type="submit" name="go" id="go" value="{$LANG.gravar}" class="submit" onclick="doAction('{$LANG.gravar}')"/>
        {if !$criarPagina}
                <input type="hidden" id="id" name="id" value="{$idPagina}" />
                <input type="hidden" id="mode" name="mode" value="svPag" />
                <input type="submit" name="go" id="go" value="{$LANG.excluir}" class="submit" onclick="doAction('{$LANG.excluir}')"/>
                <input type="submit" name="go" id="go" value="{$LANG.novaPagina}" class="submit" onclick="doAction('{$LANG.novaPagina}')"/>
        {else}
                <input type="hidden" id="mode" name="mode" value="crPag" />
        {/if}
                <input type="submit" name="go" id="go" value="{$LANG.cancelar}" class="submit" onclick="doAction('{$LANG.cancelar}')"/> 
            </div>
        </form>
        {if !$criarPagina}
        <div class="left">
            <div class="subTitulo">{$LANG.categorias}</div>
            <div class="tituloColuna">Categoria</div>
            <div class="tituloColuna">{$LANG.subir}</div>
            <div class="tituloColuna">{$LANG.descer}</div>
            <div class="tituloColuna">{$LANG.excluir}</div>
            <div class="tituloColuna w25pc">{$LANG.categoriaRestrita}</div>
            <div id="categorias_div">
                {section name=dc loop=$categoriasPresentes}
                <div class="content left">
                    <div class="tituloColuna" style="clear: left;">
                        <a href="{$includePATH}admin/categoria_edit.php?mode=edCat&id={$idPagina}&idCat={$categoriasPresentes[dc].idCategoria}">{$categoriasPresentes[dc].descricaoCategoria}</a>
                    </div>
                    <div class="colunaTransparente" >
                        <div class="click_div" onClick="editarCategoria('ascenderCategoria', {$idPagina}, {$categoriasPresentes[dc].idCategoria});">
                            <i class="fa-solid fa-circle-arrow-up" style="color: var(--cor-dark);"></i>
                        </div>
                    </div>
                    <div class="colunaTransparente" >
                        <div class="click_div" onClick="editarCategoria('descenderCategoria', {$idPagina}, {$categoriasPresentes[dc].idCategoria});">
                            <i class="fa-solid fa-circle-arrow-down" style="color: var(--cor-dark);"></i>
                        </div>
                    </div>
                    <div class="colunaTransparente" >
                        <div class="click_div" onClick="editarCategoria('excluirCategoria', {$idPagina}, {$categoriasPresentes[dc].idCategoria});">
                            <i class="fa-solid fa-circle-xmark" style="color: var(--cor-dark);"></i>
                        </div>
                    </div>
                    <div class="coluna w25pc" >
                            {if $categoriasPresentes[dc].categoriaRestrita == 1}Sim [{$categoriasPresentes[dc].restricaoCategoria}]{else}Não{/if}
                    </div>
                </div>
                {sectionelse}
                    <div class="subTitulo">{$LANG.paginavazia}</div>
                {/section}
                <div class="content contentTable">
                <div class="subTitulo" >{$LANG.novaCategoria}:</div>
                <form id="nwCat" method="POST">
                <div class="contentFxAlignCenter">
                    <select class="novoFilho" id="categoriaSelector" name="categoriaSelector">
                    {section name=dnc loop=$categoriasAusentes}
                        <option value="{$categoriasAusentes[dnc].idCategoria}">{$categoriasAusentes[dnc].descricaoCategoria}</option>
                    {/section}
                    </select>
                    <input type="button" class="submit" onClick="editarCategoria('incluirCategoria', {$idPagina}, document.getElementById('categoriaSelector').value);" value="{$LANG.incluir}" style="margin-top: 2px; margin-bottom: 2px;">
                </div>
                </form>
                </div>
            </div>
        </div>
        {/if}
    </div>
    {if !$criarPagina}
    <div class="columnBox" style="width: 40%; clear: top;">
        <iframe id="exemploPagina" class="exemploPagina" src="{$includePATH}homepage.php?id={$idPagina}&gr=all"></iframe>
    </div>
    {/if}
</div>
{include file="page_footer.tpl"}
