{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)}onload="createToast({$smarty.cookies.iconAlerta|default:'info'}, '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
{if $displayImagemTitulo == '1'}<div class="logo"><img src='{$includePATH}imagens/logo_shires.png'/ ></div>{/if}
<div  class="titulo">
  {if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}
</div>
<div class="content">
<div class="itemLateral">{$LANG.NumPaginas}</div>
<div class="item" style="text-align: left;">{$numPaginas}</div>
<div class="itemLateral">{$LANG.NumCategorias}</div>
<div class="item" style="text-align: left;">{$numCategorias}</div>
<div class="itemLateral">{$LANG.NumGrupos}</div>
<div class="item" style="text-align: left;">{$numGrupos}</div>
<div class="itemLateral">{$LANG.NumLinks}</div>
<div class="item" style="text-align: left;">{$numLinks}</div>
<div class="itemLateral">{$LANG.NumForms}</div>
<div class="item" style="text-align: left;">{$numForms}</div>
<div class="itemLateral">{$LANG.NumImagens}</div>
<div class="item" style="text-align: left;">{$numImagens}</div>
<div class="itemLateral">{$LANG.NumTemplates}</div>
<div class="item" style="text-align: left;">{$numTemplates}</div>
<div class="itemLateral">{$LANG.NumFortunes}</div>
<div class="item" style="text-align: left;">{$numFortunes}</div>
</div>
<div class="tituloSecao">Estatísticas de visitas</div>
<br />
{include 'admin/index_stats_detail.tpl' title='Últimos 7 dias' totalLinks=$totalLinks7dias listaLinks=$listaLinks7dias}
<br />
{include 'admin/index_stats_detail.tpl' title='Último mês' totalLinks=$totalLinks1mes listaLinks=$listaLinks1mes}
{include file="page_footer.tpl"}
