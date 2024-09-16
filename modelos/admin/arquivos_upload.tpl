{include file="page_header.tpl"}
<body class="{$classPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
<input type="hidden" id="idTema" value="" />
<div class="content" style="text-align: left;">
<div class="subTitulo">Fortunes</div>
{include file="admin/box_upload_file.tpl" fileField="Fortunes"}
</div>
<script>
function formatarResultado_Fortunes(resultadoProcesso) {
    return  '<p>Resultados do processamento</p>Processadas: ' + resultadoProcesso.totalQuotes +
            '<br/>Criadas: ' + resultadoProcesso.totalCreated +
            '<br/>Recusadas por tamanho: ' + resultadoProcesso.totalFailSize +
            '<br/>Repetidas: ' + resultadoProcesso.totalFailExists +
            '<br/>Vazias: ' + resultadoProcesso.totalFailNull;
}
</script>
{include file="page_footer.tpl"}
