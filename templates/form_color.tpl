<script type="text/javascript" src="{$includePATH}js/cores.js"></script>
<script type="text/javascript" src="{$includePATH}js/jscolor.min.js"></script>
<script type="text/javascript">
jscolor.presets.default = {
    format: 'hex', 
    valueElement: '#zzSelectColorForm', 
    required: false, 
    palette: getAllThemeColors(), 
    backgroundColor: getThemeColor('dark'), 
    previewPosition: 'right',
    closeButton: true,
    closeText: 'Fechar',
    buttonColor: getThemeColor('light'),
}
</script>
<div class="tituloLateral" style="width: 100%; float: none; font-size: larger;">
    Seleção de Cores
</div>
<div class="lineForm">
    <div class="formLateral" style="width: 100%;">
        <form id="colorForm" action="javascript: wColorFormAction()">
        <input type="hidden" id="zzSelectColorForm" value="" />
        <div style="width: 100%;">
            <div style="float: left;">
                <input type="hidden" name="idPagina" id="idPagina" value="{$idPagina}">
                <button class="submit" style="margin-left: 3px; padding-left: 15px; padding-right: 15px; padding-top: 3px;">Elementos</button>
                <select id="elementoSelector" size="5">
{section name=opt loop=$elementosColoridos}
                    <option value="{$elementosColoridos[opt].cookieElemento}">{$elementosColoridos[opt].descricaoElemento}</option>
{/section}
                </select>
            </div>
            <div style="float: left;">
                <button class="submit" style="width: 180px; margin-left: 3px; padding-left: 15px; padding-right: 15px; padding-top: 3px;">Cores</button><br /><br />
                <button id="colorPicker" data-jscolor="{}" style="width: 180px; margin-left: 3px; padding-left: 15px; padding-right: 15px; padding-top: 3px;">Pick a Color</button>
            </div>
            <div style="float: left;">
                <select id="SelectColorForm" onChange="document.getElementById('zzSelectColorForm').value = document.getElementById('SelectColorForm').value;" size="5" style="clear: both; width: 200px;">
{section name=opt loop=$paresCores}
                    <option value="{$paresCores[opt].valorCor}" style="background-color: {$paresCores[opt].valorCor}">{$paresCores[opt].nomeCor}</option>
{/section}                
                </select>
            </div>
            <div style="float: left;">
                <input type="button" class="submit" style="width: 180px; margin-left: 3px; margin-bottom: 15px; padding-left: 15px; padding-right: 15px; padding-top: 3px;" onClick="alterarRootVar();" value="Alterar!" /><br />
                <input type="button" class="submit" style="width: 180px; margin-left: 3px; margin-bottom: 15px; padding-left: 15px; padding-right: 15px; padding-top: 3px;" onClick="restaurarRootVar();" value="Restaurar!" /><br />
                <input type="button" class="submit" style="width: 180px; margin-left: 3px; padding-left: 15px; padding-right: 15px; padding-top: 3px;" onClick="restaurarRootcssPagina();" value="Restaurar Página!" />
            </div>
            <div style="float: right; margin-right: 5%;">
                <input type="button" class="submit" style="width: 180px; margin-left: 3px; padding-left: 15px; padding-right: 15px; padding-top: 3px;" onClick="novoEstilo();" value="Salvar Estilo!" />
            </div>
        </div>
        </form>
    </div>
</div>
