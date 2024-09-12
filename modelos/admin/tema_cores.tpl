    <div class="menuBarra">
        <div class="menuBarraItem" onClick="executarNoFrameTema([alterarRootVar]);">
        <span class="material-symbols-sharp">format_color_fill</span>
        Alterar
        </div>
        <div class="menuBarraItem" onClick="executarNoFrameTema([restaurarRootVar]);">
        <span class="material-symbols-sharp">format_color_reset</span>
        Restaurar
        </div> 
    </div>
    <div class="boxContent" style="float: left; clear: both; width: 100%;">
        <div class="tituloClaro">Item a alterar</div>
        <div class="blockElemento" style="float: left; clear: both; width: 100%; padding-top: 0;">
            <div class="contentElemento" id="selectElemento" style="float: left; clear: both; margin: 0;">
                {section name=vr loop=$variaveisRoot}
                <label class="boxRadioLabel" id="lbl_{$variaveisRoot[vr].rootvar}" style="font-size: 1rem;">
                <input class="boxRadio" type="radio" id="{$variaveisRoot[vr].rootvar}" name="selectElemento" value="{$variaveisRoot[vr].rootvar}" onClick="executarNoFrameTema([onChangeElementoBoxElementoCor], false);" style="font-size: 0.9rem; line-height: auto;"/>
                {$variaveisRoot[vr].descricao}
                </label> 
                {/section}
            </div>
        </div>
    </div>
    <div style="width: 100%; height: 5px; margin: 0; margin-top: 10px; padding: 0; border: 0; background-color: var(--cor-tituloBB);"></div>
    <div class="boxContent" style="width: 100%";>
        <div style="display: flex; width: 100%; margin-top: 1rem;">
            <div id="previewElementoPicked" style="float: left; min-width: 150px; display: block"></div>
            <div id="previewColorPicked" style="float: right; min-width: 150px; display: block"></div>
        </div>
    </div>
    <div class="boxContent" style="width: 100%;">
        <div class="tituloClaro">Cor a atribuir</div>
        <div style="text-align: left; display: flex; width: 100%; height: 200px;">
                <input type="text" id="selectedColor" style="display: none;">
                <div class="wrapper">
                    <div class="tabs">
                        <div class="tab">
                            <input type="radio" name="css-tabs" id="tab-paleta" class="tab-switch" checked>
                            <label for="tab-paleta" class="tab-label fa-palette">Paleta</label>
                            <div class="tab-content">
                                <div style="width: 201px; border: 1px solid #c0c0c0; border-radius: 6px; padding: 6px;">
                                        <div class="contentCor" id="boxContentPaleta" style="width: 181px; border: 1px solid #c0c0c0; border-radius: 0; margin: 10px;"></div>
                                </div>    
                            </div>
                        </div>
                        {section name=p loop=$paresCores}
                        {include file='admin/box_cores_detail.tpl' nomePaleta=$paresCores[p].nomePaleta paresCores=$paresCores[p].paleta size=$paresCores[p].size fa_icon=$paresCores[p].fa_icon}
                        {/section}
                        <div class="tab" onClick="document.getElementById('colorPicker').jscolor.show();">
                            <input type="radio" name="css-tabs" id="tab-jscolor" class="tab-switch">
                            <label for="tab-jscolor" class="tab-label fa-eye-dropper">Spectrum</label>
                            <div class="tab-content" id="pickercontainer">
                                <div id="colorPicker" data-jscolor="{}"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
<script>
jscolor.presets.default = {
    format: 'hex',
    valueElement: '#selectedColor',
    previewElement: '#selectedColor',
    required: false,
    backgroundColor: 'transparent',
    previewPosition: 'right',
    container: '#pickercontainer',
    position: 'bottom',
    onChange: onChangeColorPicker,
    hideOnLeave: false,
    shadow: false,
    height: 151,
    width: 181,
    palette: getThemePalette,
    zIndex: 0,
}
</script>
