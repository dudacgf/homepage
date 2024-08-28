<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{if $displayImagemTitulo == '1'}<div class="logo"><img src='{$includePATH}imagens/logo_shires.png'/></div>{/if}
<div class="titulo" style="width: 100%">{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</div>
<div class="content" style="display: flex;">
{section name=dc loop=$descricoesCategorias}
<div class="containerCategoria">
  <div class="tituloCategoria">{$descricoesCategorias[dc].categoria}</div>
  {section name=dg loop=$descricoesGrupos[dc].grupos}
  {assign var="grupo" value=$descricoesGrupos[dc].grupos[dg]}
  {if $grupo.idtipoGrupo == '1'}
  <div class="interior">
        {include file="page_grupo_detalhe.tpl"}
  </div>
  {elseif $grupo.idtipoGrupo == '2'}
  <div class="clickable" id="{$grupo.grupo|replace:' ':'_'}">
      <div class="headclickable fa-square-plus" style="cursor: pointer;" onclick="this.parentElement.classList.toggle('expanded');">
         {$grupo.grupo}
      </div>
      {include file="page_grupo_detalhe.tpl"}
  </div>
  {elseif $grupo.idtipoGrupo == '3'}
    <div class="expandable">
      <div class="headexpandable fa-circle-plus">
        {$grupo.grupo}
      </div>
      {include file="page_grupo_detalhe.tpl"}
    </div>
  {/if}
  {/section}
</div>
{/section}
</div>
{if !$displaySelectColor}
<div style="width: 100%; height: 20px; margin: 0; padding: 0; border: 0; background-color: var(--theme-tituloBB);"></div>
{/if}
