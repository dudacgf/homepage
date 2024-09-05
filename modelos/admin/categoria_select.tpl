{include file="page_header.tpl"}
<body id="theBody" class="{$classPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{if $displayImagemTitulo == '1'}<div class="logo"><img src='{$includePATH}imagens/logo_shires.png'/ ></div>{/if}
{include file="admin/menu.tpl"}
<div  class="titulo">{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</div>
<input type="hidden" id="idCat" value="" />
<div class="contentSelecao">
    <div class="tituloSelecao">
        {$LANG.selecionarCategoria}
    </div>
    <div class="contornoSelecao">
        <div class="boxSelecao" id="boxSelecao">
        {section name=cat loop=$categorias}
            <label class="boxSelecaoLabel">
            <input class="boxRadio noselect" type="radio"  id="{$categorias[cat].idCategoria}" name="selectCategoria" value="{$categorias[cat].idCategoria}" onClick="document.getElementById('idCat').value = this.value;" style="user-select: none;"/>
                        {$categorias[cat].descricaoCategoria} :: {if $categorias[cat].categoriaRestrita == 1}[ restrição - {$categorias[cat].restricaoCategoria} ]{else}[ sem restrição ]{/if}
            </label> 
        {/section}
        </div>
    </div>
    <div class="interior" style="text-align: center; padding-top: 4pt; margin: 1.5rem;">
        <input type="submit" class="submitEspacado" value="{$LANG.confirmar}"
               onclick="window.location = '{$includePATH}admin/categoria_edit.php?mode=edCat&idCat=' + document.getElementById('idCat').value";/> 
        <input type="submit" class="submitEspacado" value="{$LANG.novaCategoria}"
               onclick="window.location = '{$includePATH}admin/categoria_edit.php?mode=nwCat'";/>
        <input type="submit" class="submitEspacado" value="{$LANG.voltar}"
               onclick="window.location = '{$includePATH}admin/index.php'";/>
    </div>
</div>
{include file="page_footer.tpl"}