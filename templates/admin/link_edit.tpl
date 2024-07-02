{include file="page_header.tpl"}

<script type="text/javascript">
<!--
	function doAction(pressed) {ldelim}
		mode = document.getElementById('mode').value;
		if (mode == 'crLnk') {ldelim}
			switch (pressed) {ldelim}
				case '{$LANG.gravar}':
					document.getElementById('mode').value = 'crLnk';
					break;
				case '{$LANG.cancelar}':
					document.getElementById('mode').value = 'clWnd';
					break;
			{rdelim}
		{rdelim} else {ldelim}
			switch (pressed) {ldelim}
				case '{$LANG.gravar}':
					document.getElementById('mode').value = 'svLnk';
					break;
				case '{$LANG.cancelar}':
					document.getElementById('mode').value = 'clWnd';
					break;
			{rdelim}
		{rdelim}
		document.edLnk.submit();
	{rdelim}

-->
</script>
	<td>
	 	<form name="edLnk" id="edLnk" action="elemento_edit.php">
{if $criarElemento}
			<input type="hidden" name="mode" id="mode" value="crLnk" />
{else}
			<input type="hidden" name="mode" id="mode" value="svLnk" />
			<input type="hidden" name="idElm" value="{$elemento.idElemento}" />
{/if}
			<input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
			<table width="400pt"><tr><td><table style=" border: 0pt; width: 100%;">
			<tr>
				<td colspan="10" class="titulo">{$LANG.configuracao}</td>
			</tr><tr>
				<th class="categoria">{$LANG.hp_links_descricaoLink}</th>
				<td><input type="text" class="FormExtra" size=30 name="descricaoLink" value="{$elemento.descricaoLink}" tabindex="1" /></td>
				<th class="categoria">&nbsp;</th>
			</tr><tr>
				<th class="categoria">{$LANG.hp_links_linkURL}</th>
				<td><input type="text" class="FormExtra" size=30 name="linkURL" value="{$elemento.linkURL}" tabindex="2" /></td>
				<th class="categoria">&nbsp;</th>
			</tr><tr>
				<th class="categoria">{$LANG.hp_links_dicaLink}</th>
				<td><input type="text" class="FormExtra" size=30 name="dicaLink" value="{$elemento.dicaLink}" tabindex="3" /></td>
				<th class="categoria">&nbsp;</th>
			</tr><tr>
				<td colspan="10" class="titulo"><table style=" border: 0pt; width: 100%;"><tr>
					<th class="categoria">
						<input id="urlElementoSSL" type="checkbox" name="urlElementoSSL" {if $elemento.urlElementoSSL == '1'}checked{/if} tabindex="4"/>
						<label for="urlElementoSSL">{$LANG.hp_links_urlElementoSSL}</label>
					</th>
					<th class="categoria">
						<input id="urlElementoSVN" type="checkbox" name="urlElementoSVN" {if $elemento.urlElementoSVN == '1'}checked{/if} tabindex="4"/>
						<label for="urlElementoSVN">{$LANG.hp_links_urlElementoSVN}</label>
					</th>
					<th class="categoria">
						<input id="localLink" type="checkbox" name="localLink" {if $elemento.localLink == '1'}checked{/if} tabindex="4"/>
						<label for="localLink">{$LANG.hp_links_localLink}</label>
					</th>
				</tr></tbody></table></td>
			</tr><tr>
				<th class="categoria">{$LANG.hp_links_targetLink}</th>
				<td><input type="text" class="FormExtra" size=30 name="targetLink" id="targetLink" value="{$elemento.targetLink}" tabindex="5" /></td>
				<th class="categoria">&nbsp;</th>
			</tr>
			<tr><th class="categoria" colspan="10" style=" Text-Align: center;">
				<input type="submit" class="submit" name="go" value="{$LANG.gravar}" onclick="javascript: doAction('{$LANG.gravar}')" tabindex="6" /> :: 
				<input type="submit" class="submit" name="go" value="{$LANG.cancelar}" onclick="javascript: doAction('{$LANG.cancelar}')" tabindex="7" />
			</td></tr></table>
		</form>
	</td>
{include file="page_footer.tpl"}

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
