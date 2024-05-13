{include file="page_header.tpl"}

<td class="categoria" style=" width: 100pt; vertical-align: top; text-align: center;" >
{if $arquivo.tipo == 'flv'}
	<div class="interior" onmouseover="this.setAttribute('{$classAttribute}', 'expanded')" onmouseout="this.setAttribute('{$classAttribute}', 'interior')">
		<object type="application/x-shockwave-flash" data="/flv/flvplayer.swf?file=/flv/flvprovider.php?mov={$arquivo.url}" 
			width="330pt;" height="260pt;" wmode="transparent" >
			<param name="movie" value="/flv/flvplayer.swf?file=/flv/flvprovider.php?mov={$arquivo.url}"/>
			<param name="wmode" value="transparent" />
		</object>	
	</div>
{else}
	<div class="interior" onmouseover="this.setAttribute('{$classAttribute}', 'expanded')" onmouseout="this.setAttribute('{$classAttribute}', 'interior')">
		<img src="{$arquivo.image}" alt="" title="" border="0" vspace="10">
	</div>
{/if}
</td>

{include file="page_footer.tpl"}

{* // vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
