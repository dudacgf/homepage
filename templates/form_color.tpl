<script type="text/javascript" src="{$includePATH}js/cores.js"></script>
<script type="text/javascript" src="{$includePATH}js/jscolor.min.js"></script>
<script type="text/javascript">
function onChangeSelectColorForm() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#zzSelectColorForm');
  const selectCF = document.querySelector('#SelectColorForm');

  preview.style.backgroundColor = selectCF.value;
  zzselect.value = selectCF.value;
}

function onChangeColorPicker() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#zzSelectColorForm');
  const colorPicker = document.querySelector('#colorPicker');

  preview.style.backgroundColor = colorPicker.jscolor.valueElement.value;
  zzselect.value = colorPicker.jscolor.valueElement.value;
}

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
<div class="content" style="text-align: left;">
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
                            <select id="elementoSelector" size="5">
                                {section name=opt loop=$elementosColoridos}
                                <option value="{$elementosColoridos[opt].cookieElemento}">{$elementosColoridos[opt].descricaoElemento}</option>
                                {/section}
                            </select>
                        </div>
                    </div>
                    <div style="float: left;">
                        <div class="tituloCategoria">Cores
                        </div>
                        <div class="interior">
                            <button id="rainbowButton" onClick="document.querySelector('#colorPicker').jscolor.show();" style="width: 200px; margin-left: 1px; padding-left: 15px; padding-right: 15px; padding-top: 3px; background-image: url('{$includePATH}imagens/ColorPicker.png'); background-size: auto; color: var(--theme-dark);">Pick a Color</button>
                            <button id="colorPicker" data-jscolor="{}" style="display: none; max-width: 1px; max-height: 1px;"></button>
                            <select id="SelectColorForm" size="5" style="clear: both; width: 200px;">
    {section name=opt loop=$paresCores}
                                <option value="{$paresCores[opt].valorCor}" style="color: {$paresCores[opt].hspCor}; background-color: {$paresCores[opt].valorCor}">{$paresCores[opt].nomeCor}</option>
    {/section}                
                            </select>
                            <script>document.querySelector('#SelectColorForm').addEventListener('change', onChangeSelectColorForm);</script>
                        </div>
                    </div>
                    <div style="float: left;">
                        <div id="previewColorPicked" style="border: 1px solid var(--theme-medium); width: 180px; height: 28px; margin-left: 3px; margin-bottom: 12px;"></div>
                        <input type="button" class="submit" style="width: 180px; margin-left: 3px; margin-bottom: 15px; padding-left: 15px; padding-right: 15px; padding-top: 3px;" onClick="alterarRootVar();" value="Alterar!" /><br />
                        <input type="button" class="submit" style="width: 180px; margin-left: 3px; margin-bottom: 15px; padding-left: 15px; padding-right: 15px; padding-top: 3px;" onClick="restaurarRootVar();" value="Restaurar!" /><br />
                        <input type="button" class="submit" style="width: 180px; margin-left: 3px; padding-left: 15px; padding-right: 15px; padding-top: 3px;" onClick="restaurarRootcssPagina();" value="Restaurar Página!" />
                    </div>
                    <div style="float: right; margin-right: 5%;">
                        <input type="button" class="submit" style="width: 180px; margin-left: 3px; padding-left: 15px; padding-right: 15px; padding-top: 3px;" onClick="novoEstilo();" value="Salvar Estilo!" />
                    </div>
                </div>
            </form>
        </div><!-- class=formLateral" -->
    </div><!-- class=lineForm-->
</div><!-- class=content -->
