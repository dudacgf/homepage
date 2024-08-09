<div class="lineForm">
	<div class="tituloLateral">
		Colors
	</div>
	<div class="formLateral">
		<span id="xHttpResponse" style="display: none;"></span>
		<form id="colorForm" action="javascript: wColorFormAction()"><div>
			<input type="hidden" name="idPagina" id="idPagina" value="{$idPagina}">
			<select id="elementoSelector" size="5">
{section name=opt loop=$elementosColoridos}
{if $elementosColoridos[opt].descricaoElemento != ''}
				<option value="{$elementosColoridos[opt].cookieElemento}">{$elementosColoridos[opt].descricaoElemento}</option>
{/if}
{/section}
			</select>
			<select id="zzSelectColorForm" size="5" style="width: 200px";>
{section name=opt loop=$paresCores}
				<option value="{$paresCores[opt].valorCor}" style="background-color: {$paresCores[opt].valorCor}">{$paresCores[opt].nomeCor}</option>
{/section}				
			</select>
            <input type="button" class="submit" onClick="alterarRootVar();" value="Alterar!" />
            <input type="button" class="submit" onClick="restaurarRootVar();" value="Restaurar!" />
            <input type="button" class="submit" onClick="restaurarRootcssPagina();" value="Restaurar Página!" />
		</div></form>
	</div>
</div>
