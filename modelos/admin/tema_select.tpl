{include file="page_header.tpl"}
<body class="{$temaPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
</script>
<input type="hidden" id="idTema" value="" />
<div class="contentSelecao">
    <div class="tituloSelecao">
        {$LANG.selecionarTema}
    </div>
    <div class="contornoSelecao">
        <div class="boxSelecao" id="boxSelecao">
            {section name=t loop=$temas}
            <label class="boxSelecaoLabel" style="font-size: 1.1rem;">
            <input class="boxRadio noselect" type="radio"  id="{$temas[t].id}" name="selectTema" value="{$temas[t].id}" onClick="document.getElementById('idTema').value = this.value;" style="user-select: none;"/>
                {$temas[t].nome} [{$temas[t].comentario}]
            </label> 
            {/section}
        </div>
    </div>
    <div class=barra>
        <input type="submit" value="{$LANG.confirmar}" onclick="window.location = '{$includePATH}admin/tema_edit.php?mode=edTema&idTema=' + document.getElementById('idTema').value";/> 
        <input type="submit" value="{$LANG.novoTema}" onclick="window.location = '{$includePATH}admin/tema_edit.php?mode=nwTema'";/>
        <input type="submit" value="{$LANG.voltar}" onclick="window.location = '{$includePATH}admin/index.php'";/>
    </div>
</div>
{include file="page_footer.tpl"}
