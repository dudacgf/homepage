{include file="page_header.tpl"}
<div style="float: left; padding: 15px;">
<span style="font-weight: bold;">'/' .htaccess<br /></span>
<span style="font-family: monospace; white-space: pre-line; text-align: left;">{$root_ht}</span>
</div>
<div style="clear: left; padding: 15px;">
<span style="font-weight: bold;">'/admin/' .htaccess<br /></span>
<span style="font-family: monospace; white-space: pre-line; text-align: left;">{$admin_ht}</span>
</div>

<div style="float: left; padding: 15px;">
<button class="submit" onclick="window.location = '{$includePATH}/homepage.php';">HOMEPAGE</button>
</div>
{include file="page_footer.tpl"}

