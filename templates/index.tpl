{include file="page_header.tpl"}
<div class="content">
{if !isset($edicaoPagina)}<table style="text-align: center;"><tr>{/if}
{include file="page_body.tpl"}
{if !isset($edicaoPagina)}</tr></table>{/if}
</div>
{if $displayGoogle == 1}
	{include file="form_google.tpl"}
{/if}
{if $displayFindaMap == 1}
	{include file="form_findamap.tpl"}
{/if}
{if $displaySelectColor == 1}
	{include file="form_color.tpl"}
{/if}
{if $displayDicionario == 1}
	{include file="form_dicionario.tpl"}
{/if}
{if $displayFortune == 1}
	{include file="fortune.tpl"}
{/if}
{include file="page_footer.tpl"}

