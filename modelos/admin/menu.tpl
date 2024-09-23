<div class="menuDropdownContainer" style="float: right;">
  <button class="menuButton fa-solid fa-bars"></button>
  <div class="menuDropDown">
{section name=dc loop=$menuCategorias}
<div class="containerCategoria">
  <div class="tituloCategoria">{$menuCategorias[dc].categoria}</div>
  {section name=dg loop=$menuGrupos[dc].grupos}
  {assign var="grupo" value=$menuGrupos[dc].grupos[dg]}
  <div class="interior">
    {strip}
    {section name=el loop=$grupo.elementos}
        {assign var="elemento" value=$grupo.elementos[el]}
        {if $elemento.tipoElemento == 1}
        <a href="{if $elemento.urlElementoSSL}
                     https://
                  {else}
                     http://
                  {/if}
                  {if $elemento.localLink}
                     {$smarty.server.SERVER_NAME}/{$includePATH}
                  {/if}
                  {$elemento.linkURL}"{if $elemento.targetLink != ''} target="{$elemento.targetLink}"{/if} title="{$elemento.dicaLink} [ {$elemento.linkURL} ]">
            {$elemento.descricaoLink}</a>
        {elseif $elemento.tipoElemento == 3}
            {if $elemento.breakBefore == 1}
              <br />
            {/if}
            {if $elemento.descricaoSeparador != ''}
              <span class="separador">{$elemento.descricaoSeparador}</span><br />
            {/if}
        {else}
          não é nada não...<br />
        {/if}
    {/section}
    {/strip}
  </div>
  {/section}
</div>
{/section}
  </div>
</div> 
