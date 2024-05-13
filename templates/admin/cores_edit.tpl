{include file="page_header.tpl"}
<div class="content">
{if !isset($edicaoPagina)}<table style="text-align: center;"><tr>{/if}
{include file="page_body.tpl"}
{if !isset($edicaoPagina)}</tr></table>{/if}
</div>

<div class="lineForm" style="height: 120px; float: left; clear: both; margin-top: 10px;" >
	<div class="tituloLateral" style="width: 98%; float: none;">
		Sele&ccedil;ão de Cores
	</div>
	<div class="formLateral" style="width: 100%; float: none;">
		<form id="colorForm" action="javascript: wColorFormAction()">
		<span id="xHttpResponse" style="display: none;"></span>
		<input type="hidden" id="id" name="id" value="{$idPagina}" />
		<input type="hidden" id="response" value="">
		<div style="float: left;">
			<select id="elementSelector" size="7">
{section name=opt loop=$elementosColoridos}
{if $elementosColoridos[opt].descricaoElemento != ''}
				<option value="{$elementosColoridos[opt].idElementoColorido}">{$elementosColoridos[opt].descricaoElemento}</option>
{/if}
{/section}
			</select>
			<select id="zzSelectColorForm" size="7">
{section name=opt loop=$paresCores}
				<option value="{$paresCores[opt].valorCor}" style="background-color: {$paresCores[opt].valorCor}">{$paresCores[opt].nomeCor}</option>
{/section}				
			</select>
		</div>
		<div style="float: left;">
			<a href="javascript: adicionarCookedStyle();" name="Alterar!">Alterar!</a><br />
			<a href="javascript: deletarCookedStyle();" name="Alterar!">Restaurar!</a><br />
			<a href="javascript: restaurarPagina()" name="link">Restaura Página!</a>
		</div>
		</form>
	</div>
</div>

{include file="fortune.tpl"}

{include file="page_footer.tpl"}
{* vi: set tabstop=4 shiftwidth=4 showmatch nowrap: *}
