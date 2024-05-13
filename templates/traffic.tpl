{include file="page_header.tpl"}

	<td class="categoria" style=" width: 100pt; vertical-align: top; text-align: center;" >
		<div class="interior" onmouseover="this.setAttribute('{$classAttribute}', 'expanded')" onmouseout="this.setAttribute('{$classAttribute}', 'interior')">
			<img src="{$relativePATH}traffic/getrrdgraph.php?start=15min">
		</div>
	</td>
	<td class="categoria" style=" width: 100pt; vertical-align: top; text-align: center;" >
		<div class="interior" onmouseover="this.setAttribute('{$classAttribute}', 'expanded')" onmouseout="this.setAttribute('{$classAttribute}', 'interior')">
			<img src="{$relativePATH}traffic/getrrdgraph.php?start=1hour">
		</div>
	</td>
</tr>
<tr>
	<td class="categoria" style=" width: 100pt; vertical-align: top; text-align: center;" >
		<div class="interior" onmouseover="this.setAttribute('{$classAttribute}', 'expanded')" onmouseout="this.setAttribute('{$classAttribute}', 'interior')">
			<img src="{$relativePATH}traffic/getrrdgraph.php?start=1day">
		</div>
	</td>
	<td class="categoria" style=" width: 100pt; vertical-align: top; text-align: center;" >
		<div class="interior" onmouseover="this.setAttribute('{$classAttribute}', 'expanded')" onmouseout="this.setAttribute('{$classAttribute}', 'interior')">
			<img src="{$relativePATH}traffic/getrrdgraph.php?start=1week">
		</div>
	</td>
</tr>
<tr>
	<td colspan="10" class="titulo">
		&nbsp;
	</td>

{include file="page_footer.tpl"}

{* // vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
