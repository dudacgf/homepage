<div class="lineForm">
	<div class="tituloLateral">
		Colors
	</div>
	<div class="formLateral">
		<span id="xHttpResponse" style="display: none;"></span>
		<form id="colorForm" action="javascript: wColorFormAction()"><div>
			<input type="hidden" name="id" id="id" value="{$idPagina}">
			<select id="elementSelector" size="5">
{section name=opt loop=$elementosColoridos}
{if $elementosColoridos[opt].descricaoElemento != ''}
				<option value="{$elementosColoridos[opt].idElementoColorido}">{$elementosColoridos[opt].descricaoElemento}</option>
{/if}
{/section}
			</select>
			<select id="zzSelectColorForm" size="5" style="width: 200px";>
{section name=opt loop=$paresCores}
				<option value="{$paresCores[opt].valorCor}" style="background-color: {$paresCores[opt].valorCor}">{$paresCores[opt].nomeCor}</option>
{/section}				
			</select>
			<a href="javascript: adicionarCookedStyle('{$includePATH}');" name="Alterar!">Alterar!</a>&nbsp;&nbsp;
			<a href="javascript: deletarCookedStyle('{$includePATH}');" name="Alterar!">Restaurar!</a>&nbsp;&nbsp;
			<a href="javascript: restaurarPagina('{$includePATH}')" name="link">Restaura Página!</a>
		</div></form>
	</div>
</div>
