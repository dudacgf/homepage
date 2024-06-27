{strip}
{section name=el loop=$grupo.elementos}
{assign var="elemento" value=$grupo.elementos[el]}
{if $elemento.tipoElemento == 1}  
      <a href="
      {if $elemento.urlElementoSSL}
        https://
      {elseif $elemento.urlElementoSVN}
	  	svn+ssh://
   	  {else}
        http://
      {/if}
      {if $elemento.localLink}
        {$smarty.server.SERVER_NAME}/{$includePATH}
      {/if}
      {$elemento.linkURL}" {if $elemento.targetLink != ''} target="{$elemento.targetLink}"{/if} title="{$elemento.dicaLink} [ {$elemento.linkURL} ]">
    {$elemento.descricaoLink}</a>
  {elseif $elemento.tipoElemento == 2}
    <form method="get" id="{$elemento.nomeForm}" action="{$elemento.acao}"><div>
    <input type="text" size="{$elemento.tamanhoCampo}" name="{$elemento.nomeCampo}" />
    <a href="javascript: document.{$elemento.nomeForm}.submit(); " name="link" title="{$elemento.acao}">
		{$elemento.descricaoForm}</a><br /></div></form> 
  {elseif $elemento.tipoElemento == 3}
    {if $elemento.breakBefore == 1}
      <br />
    {/if}
    {if $elemento.descricaoSeparador != ''}
      <span class="separador">{$elemento.descricaoSeparador}</span><br />
    {/if}
  {elseif $elemento.tipoElemento == 4}
      <a href="{if !$elemento.localLink}http://{/if}{$elemento.urlImagem}"><img src="{if !$elemento.localLink}http://{/if}{$elemento.urlImagem}" alt="{$elemento.descricaoImagem}" width="96" height="72" />
    <br />{$elemento.descricaoImagem}</a><p />
{*---- {elseif $elemento.tipoElemento == 5}
  {assign var="rssURL" value=$elemento.rssURL}
  {assign var="is" value=$elemento.rssItemNum}
    {rssload source="$rssURL" items="5" cachelt="900"}
    <a href="{$rss[$is].link}" style="white-space: normal;">{$rss[$is].title|truncate:30:"..."}<span class="popup">
    <span style="text-align: left; font-weight: bold; ">{$rss[$is].title}</span><br />
    {$rss[$is].decoded_description|truncate:300:"[...]"}<br /></span></a> --*}
{elseif $elemento.tipoElemento == 6}
  {include file=$elemento.nomeTemplate}
{else}
  não é nada não...
{/if}
{/section}
{/strip}
