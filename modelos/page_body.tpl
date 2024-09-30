<body class="{$temaPagina}" {if isset($smarty.cookies.showAlerta)}onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
<div class="titulo">{if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}<span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span></div>
<div class="content" style="display: flex;">
    {section name=dc loop=$descricoesCategorias}
    <div class="containerCategoria">
          <div class="tituloCategoria">{$descricoesCategorias[dc].categoria}</div>
          {section name=dg loop=$descricoesGrupos[dc].grupos}
          {assign var="grupo" value=$descricoesGrupos[dc].grupos[dg]}
          <div class="interior">
              {if $grupo.idtipoGrupo == '2'}
                <div class="headclickable fa-square-plus" onclick="this.parentElement.classList.toggle('expanded');">
                  {$grupo.grupo}
                </div>
                <p>
              {elseif $grupo.idtipoGrupo == '3'}
                <div class="headexpandable fa-circle-plus">
                  {$grupo.grupo}
                </div>
                <p>
              {/if}
              {include file="page_grupo_detalhe.tpl"}
          </div>
          {/section}
    </div>
    {/section}
</div>
<div class="barraFinal"></div>
