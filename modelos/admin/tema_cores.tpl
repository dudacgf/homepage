    <div class="menuBarra">
        <div class="menuBarraItem" onClick="executarNoFrameTema([alterarRootVar]);">
        <i class="fa-solid fa-droplet"></i>
        Alterar
        </div>
        <div class="menuBarraItem" onClick="executarNoFrameTema([restaurarRootVar]);">
        <i class="fa-solid fa-droplet-slash"></i>
        Restaurar
        </div> 
    </div>
    <div class="boxContent">
        <div class="tituloClaro">Item a alterar</div>
        <div class="blockElemento">
            <div class="contentElemento" id="selectElemento">
                {section name=vr loop=$variaveisRoot}
                <label class="boxRadioLabel" id="lbl_{$variaveisRoot[vr].rootvar}" style="font-size: 1rem;">
                <input class="boxRadio" type="radio" id="{$variaveisRoot[vr].rootvar}" name="selectElemento" value="{$variaveisRoot[vr].rootvar}" onClick="executarNoFrameTema([onChangeElementoBoxElementoCor], false);"/>
                {$variaveisRoot[vr].descricao}
                </label> 
                {/section}
            </div>
        </div>
    </div>
    <div class="boxContent faixaIntermediaria"></div>
    <div class="boxContent">
        <div style="display: flex; width: 100%; margin-top: 1rem;">
            <div id="previewElementoPicked" class="previewColor"></div>
            <div id="previewColorPicked" class="previewColor"></div>
        </div>
    </div>
    <div class="boxContent">
        <div class="tituloClaro">Cor a atribuir</div>
        <div class="wrapperOnwrapper">
                <input type="text" id="selectedColor" style="display: none;">
                <div class="wrapper">
                    <div class="tabs">
                        <div class="tab">
                            <input type="radio" name="css-tabs" id="tab-paleta" class="tab-switch" checked>
                            <label for="tab-paleta" class="tab-label fa-palette">Paleta</label>
                            <div class="tab-content">
                                <div class="boxPaleta" style="width: 201px;">
                                        <div class="contentCor" id="boxContentPaleta" style="width: 181px;"></div>
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
                        <div class="tab">
                            <input type="radio" name="css-tabs" id="tab-jsinput" class="tab-switch">
                            <label for="tab-jsinput" class="tab-label fa-pencil"></label>
                            <div class="tab-content" id="inputcontainer">
                                <div class="boxPaleta">
                                    <div class="boxInput">
                                        <div id="previewInput" class="previewInput"></div>
                                        <input type="input" class="colorInput" id="colorInput" name="colorInput" value="#FFFF00" {literal}pattern="#[0-9a-fA-F]{6}"{/literal} onInput="updateColorOut();">
                                    </div>
                                    <pre id="colorOut" class="colorOut"></prev>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
<script>
// Este aparece já aberto, sem o campo de input nem preview 
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
