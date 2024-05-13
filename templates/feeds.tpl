<!-- saved from url="http://www.theshirescompany.com" -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
<head>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<link rel="stylesheet" type="text/css" href="../estilos/estilos.css" title="default" />
<script type="text/javascript" src="/js/rotinas.js"></script>
<title>test feed</title>
</head>
<body class="wAzul">
<table>
	<tr><td>
	{section name=f loop=$rssfeeds}
	{assign var="rssURL" value=$rssfeeds[f].rssURL}
	{assign var="ig" value=$rssfeeds[f].itemstograb}
	{assign var="is" value=$rssfeeds[f].itemToShow}
		{rssload source="$rssURL" items="$ig" cachelt="900"}
		<div class="expanded" style="color: darkblue;">
			<a href="{$rss[$is].link}">{$rss[$is].title}<span class="popup"><span style="text-align: left; font-weight: bold;">{$rss[$is].title}</span><br />{$rss[$is].description|truncate:300:"[...]"}</span></a>
		</div>
	{/section}
	</td></tr>
</table>
	</body>
</html>

{php} // vi: set tabstop=4 shiftwidth=4 showmatch nowrap: {/php}
