<script type="text/javascript" src="{$includePATH}js/api_cores.js"></script>
<script type="text/javascript" src="{$includePATH}js/jscolor.min.js"></script>
<script type="text/javascript" src="{$includePATH}js/api_cores_aux.js"></script>
<script>
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
    container: '#rainbowButton', 
    position: 'bottom',
    onChange: onChangeColorPicker,
}
</script>
<div class="content" style="text-align: left; height: auto;">
    <div class="tituloLateral" style="width: inherit; float: none; font-size: larger; margin: 0;">Seleção de Cores</div>
    <div class="lineForm">
        <div class="formLateral" style="width: 100%;">
            <form id="colorForm" action="javascript: void(0);">
                <input type="hidden" id="zzSelectColorForm" value="" />
                <input type="hidden" name="idPagina" id="idPagina" value="{$idPagina}">
                <div style="width: 100%;">
                    <div style="float: left;">
                        <div class="tituloCategoria">Elementos</div>
                        <div class="interior">
                            <select id="elementoSelector" size="6">
                                {section name=opt loop=$elementosColoridos}
                                <option value="{$elementosColoridos[opt].cookieElemento}">{$elementosColoridos[opt].descricaoElemento}</option>
                                {/section}
                            </select>
                        </div>
                    </div>
                    <div style="float: left;">
                        <div class="tituloCategoria">Cores</div>
                        <div class="interior">
                            <button id="rainbowButton" onClick="document.querySelector('#colorPicker').jscolor.show();" style="width: 200px; margin-left: 1px; padding-left: 15px; padding-right: 15px; padding-top: 3px; background-image: url('{$includePATH}imagens/ColorPicker.png'); background-size: auto; color: var(--theme-dark);">Pick a Color</button>
                            <button id="colorPicker" data-jscolor="{}" style="display: none;"></button>
                            <select id="SelectColorForm" onChange="onChangeSelectColorForm();" size="6" style="clear: both; width: 200px;">
                                {section name=opt loop=$paresCores}
                                <option value="{$paresCores[opt].valorCor}" style="color: {$paresCores[opt].hspCor}; background-color: {$paresCores[opt].valorCor}">{$paresCores[opt].nomeCor}</option>
                                {/section}                
                            </select>
                        </div>
                    </div>
                    <div style="display: inline-grid; float: left;">
                        <div id="previewColorPicked" style="border: 1px solid var(--theme-medium); width: 180px; height: 28px; margin-left: 3px; margin-bottom: 6px;"></div>
                        <button class="submit colorButton check" onClick="alterarRootVar();">Alterar</button>
                        <button class="submit colorButton xmark" onClick="restaurarRootVar();">Restaurar</button>
                        <button class="submit colorButton trash" onClick="restaurarRootcssPagina();">Restaurar Página</button>
                        <button class="submit colorButton floppy" onClick="novoEstilo();">Salvar Estilo</button>
                    </div>
                </div>
            </form>
        </div><!-- class=formLateral" -->
    </div><!-- class=lineForm-->
</div><!-- class=content -->
