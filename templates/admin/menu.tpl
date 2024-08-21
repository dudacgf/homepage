<div class="menuDropdownContainer" style="float: right;">
  <button class="menuButton"><i class="fa-solid fa-bars"></i></button>
  <div class="menuDropDown">
{section name=dc loop=$menuCategorias}
<div class="containerCategoria">
  <div class="tituloCategoria">{$menuCategorias[dc].categoria}</div>
  {section name=dg loop=$menuGrupos[dc].grupos}
  {assign var="grupo" value=$menuGrupos[dc].grupos[dg]}
  <div class="interior">
        {include file="page_grupo_detalhe.tpl"}
  </div>
  {/section}
</div>
{/section}
  </div>
</div> 
