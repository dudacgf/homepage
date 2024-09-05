{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo"><img src='{$includePATH}imagens/logo_shires.png'/ ></div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
<input type="hidden" id="idGrp" value="" />
<div class="contentSelecao">
    <div class="tituloSelecao">
        {$LANG.selecionarGrupo}
    </div>
    <div class="contornoSelecao">
        <div class="boxSelecao" id="boxSelecao">
		{section name=g loop=$grupos}
            <label class="boxSelecaoLabel">
            <input class="boxRadio noselect" type="radio"  id="{$grupos[g].idGrupo}" name="selectgrupo" value="{$grupos[g].idGrupo}" onClick="document.getElementById('idGrp').value = this.value;" style="user-select: none;"/>
            {$grupos[g].descricaoGrupo} :: {if $grupos[g].grupoRestrito == 1}[ restrição - {$grupos[g].restricaoGrupo} ]{else}[ sem restrição ]{/if}
            </label> 
		{/section}
        </div>
    </div>
    <div class="interior" style="text-align: center; padding-top: 4pt; margin: 1.5rem;">
        <input type="submit" class="submitEspacado" value="{$LANG.confirmar}"
               onclick="window.location = '{$includePATH}admin/grupo_edit.php?mode=edGrp&idGrp=' + document.getElementById('idGrp').value";/> 
        <input type="submit" class="submitEspacado" value="{$LANG.novoGrupo}"
               onclick="window.location = '{$includePATH}admin/grupo_edit.php?mode=nwGrp'";/>
        <input type="submit" class="submitEspacado" value="{$LANG.voltar}"
               onclick="window.location = '{$includePATH}admin/index.php'";/>
    </div>
</div>
{include file="page_footer.tpl"}
