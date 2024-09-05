{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo"><img src='{$includePATH}imagens/logo_shires.png'/ ></div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
</script>
<input type="hidden" id="id" value="" />
<div class="contentSelecao">
    <div class="tituloSelecao">
        {$LANG.selecionarPagina}
    </div>
    <div class="contornoSelecao">
        <div class="boxSelecao" id="boxSelecao">
            {section name=pag loop=$paginas}
            <label class="boxSelecaoLabel">
            <input class="boxRadio noselect" type="radio"  id="{$paginas[pag].idPagina}" name="selectPagina" value="{$paginas[pag].idPagina}" onClick="document.getElementById('id').value = this.value;" style="user-select: none;"/>
                {$paginas[pag].tituloPagina} [{$paginas[pag].tituloTabela}]
            </label> 
            {/section}
        </div>
    </div>
    <div class="interior" style="text-align: center; padding-top: 4pt; margin: 1.5rem;">
        <input type="submit" class="submitEspacado" value="{$LANG.confirmar}"
               onclick="window.location = '{$includePATH}admin/page_edit.php?mode=edPag&id=' + document.getElementById('id').value";/> 
        <input type="submit" class="submitEspacado" value="{$LANG.novaPagina}"
               onclick="window.location = '{$includePATH}admin/page_edit.php?mode=nwPag'";/>
        <input type="submit" class="submitEspacado" value="{$LANG.voltar}"
               onclick="window.location = '{$includePATH}admin/index.php'";/>
    </div>
</div>
{include file="page_footer.tpl"}
