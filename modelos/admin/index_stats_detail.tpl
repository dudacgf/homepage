<div class="content">
<div class="itemLateral">{$title}</div>
<div class="item left">{$totalLinks} links diferentes visitados</div>
<div class="contentstats" style="margin-bottom: 0;">
    <div class="itemLateral" style="width: 16.8%;">Site</div>
    <div class="itemLateral" style="width: 16.8%;">Visitas</div>
    <div class="itemLateral" style="width: 16.8%;">+ recente</div>
    <div class="itemLateral" style="width: 16.8%;">+ antiga</div>
</div>
<div class="contentstats" style="display: block; margin: 0;">
    {section name=lv loop=$listaLinks}
    <div class="itemstats" style="clear: both;">{$listaLinks7dias[lv].descricaoElemento}</div>
    <div class="itemstats right">{$listaLinks7dias[lv].NumVisitas}</div>
    <div class="itemstats right">{$listaLinks7dias[lv].ultimaVisita}</div>
    <div class="itemstats right">{$listaLinks7dias[lv].primeiraVisita}</div>
    {/section}
</div>
</div>

