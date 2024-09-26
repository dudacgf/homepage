{include file="page_header.tpl"}
<body class="{$temaPagina}"{if isset($smarty.cookies.showAlerta)} onload="createToast('{$smarty.cookies.iconAlerta|default:info}', '{$smarty.cookies.msgAlerta}');"{/if}>
{include file="admin/menu.tpl"}
<div  class="titulo">
  {if $displayImagemTitulo == '1'}<div class="logo">{strip}{$logo_shires}{/strip}</div>{/if}
  <span>{if !isset($tituloTabelaAlternativo)}{$tituloTabela}{else}{$tituloTabelaAlternativo}{/if}</span>
</div>
<input type="hidden" id="idTema" value="" />
<div class="subTitulo">Fortunes</div>
{include file="admin/box_upload_file.tpl" fileField="Fortunes"}
<script>
function formatarResultado_Fortunes(resultadoProcesso) {
    return  '<p>Resultados do processamento</p>Processadas: ' + resultadoProcesso.totalQuotes +
            '<br/>Criadas: ' + resultadoProcesso.totalCreated +
            '<br/>Recusadas por tamanho: ' + resultadoProcesso.totalFailSize +
            '<br/>Repetidas: ' + resultadoProcesso.totalFailExists +
            '<br/>Vazias: ' + resultadoProcesso.totalFailNull;
}
</script>
<div class="subTitulo">Paletas de cor</div>
{include file="admin/box_upload_file.tpl" fileField="Paletas"}
</div>
<script>
function formatarResultado_Paletas(resultadoProcesso) {
    return  '<p>Resultados do processamento</p>Processadas: ' + resultadoProcesso.totalCores +
            '<br/>Criadas: ' + resultadoProcesso.totalCriadas +
            '<br/>Repetidas: ' + resultadoProcesso.totalRepetidas +
            '<br/>Falhas: ' + resultadoProcesso.totalFalhas;
}
</script>
<div class="barraFinal"></div>
{include file="page_footer.tpl"}
