{include file="page_header.tpl"}
<div class="content" style="display: flex;">
{include file="page_body.tpl"}
</div>
{if $displaySelectColor == 1}
{include file="form_color.tpl"}
{/if}
{if $displayFortune == 1}
{include file="fortune.tpl"}
{/if}
{include file="page_footer.tpl"}

