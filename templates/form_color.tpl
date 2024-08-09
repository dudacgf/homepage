<div class="tituloLateral" style="width: 100%; float: none; font-size: larger;">
    Seleção de Cores
</div>
<div class="lineForm">
	<div class="formLateral">
		<form id="colorForm" action="javascript: wColorFormAction()"><div>
			<input type="hidden" name="idPagina" id="idPagina" value="{$idPagina}">
            <button class="submit" style="padding-left: 15px; padding-right: 15px; padding-top: 3px;">Elementos</button>
			<select id="elementoSelector" size="5">
{section name=opt loop=$elementosColoridos}
				<option value="{$elementosColoridos[opt].cookieElemento}">{$elementosColoridos[opt].descricaoElemento}</option>
{/section}
			</select>
            <button class="submit" style="padding-left: 15px; padding-right: 15px; padding-top: 3px;">Cores</button>
			<select id="zzSelectColorForm" size="5" style="width: 200px;">
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
