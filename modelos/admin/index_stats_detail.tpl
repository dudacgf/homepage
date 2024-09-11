<div class="content">
<div class="itemLateral">{$title}</div>
<div class="item left">{$totalLinks} links diferentes visitados</div>
<div class="contentstats">
    <div class="itemLateral left" style="width: 16.8%;">Site</div>
    <div class="itemLateral" style="width: 16.8%;">Visitas</div>
    <div class="itemLateral" style="width: 16.8%;">+ recente</div>
    <div class="itemLateral" style="width: 16.8%;">+ antiga</div>
</div>
<div class="contentstats" style="display: block; margin: 0;">
    {section name=lv loop=$listaLinks}
    <div class="itemstats left" style="clear: both; width: 16.8%">{$listaLinks[lv].descricaoElemento}</div>
    <div class="itemstats right" style="width: 16.8%">{$listaLinks[lv].NumVisitas}</div>
    <div class="itemstats right" style="width: 16.8%">{$listaLinks[lv].ultimaVisita}</div>
    <div class="itemstats right" style="width: 16.8%">{$listaLinks[lv].primeiraVisita}</div>
    {/section}
</div>
</div>

