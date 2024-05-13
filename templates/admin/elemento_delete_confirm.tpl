{include file="page_header.tpl"}

<script type="text/javascript">
<!--
	function doForm() {ldelim}
		window.opener.location.reload();
		window.close();
	{rdelim}
-->
</script>


	<th class="categoria" colspan="10">Excluir elemento de grupo</th>
</tr>

<tr>
	<th class="categoria" colspan="10">Descrição</th>
<tr>

<tr>
	<td class="categoria">
		Clique, por enquanto, no botão. para sair. daqui.
	</td><td>
	 	<form name="exElm" id="exElm" action="javascript: doForm()">
			<input type="hidden" name="mode" value="dlElm">
			<input type="hidden" name="idGrp" value="{$grupo.idGrupo}" />
			<input type="hidden" name="posGrupo" value="{$elemento.posGrupo}" />
			<input type="submit" class="submit" name="go" value="{$LANG.excluir}" />
		</form>
	</td>
</tr>
{if !$criarElemento}
<tr>
	<th class="categoria">{$elemento.descricaoElemento}</th>

{/if}	
{include file="page_footer.tpl"}

{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
