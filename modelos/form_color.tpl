<script type="text/javascript" src="{$includePATH}js/coresAPI.js"></script>
<script type="text/javascript" src="{$includePATH}js/jscolor.min.js"></script>
<div class="content" style="text-align: left; height: auto;">
    <div class="tituloCores">Seleção de Cores</div>
        <input type="hidden" id="selectedColor" value="" />
        <input type="hidden" name="idPagina" id="idPagina" value="{$idPagina}">
        <div class="flex100">
            <div class="boxContent">
                <div class="tituloCategoria">
                Item a alterar
                </div>
                <div class="blockElemento">
                    <div class="contentElemento" id="selectElemento">
                        {section name=vr loop=$variaveisRoot}
                        <label class="boxRadioLabel"id="lbl_{$variaveisRoot[vr].rootvar}" >
                        <input class="boxRadio" type="radio" id="{$variaveisRoot[vr].rootvar}" name="selectElemento" value="{$variaveisRoot[vr].rootvar}" onClick="onChangeElementoBoxElementoCor(this);" />
                        {$variaveisRoot[vr].descricao}
                        </label> 
                        {/section}
                    <script>
                    const bxrLabels = document.getElementsByClassName('boxRadio');
                    for (var i = 0;  i < bxrLabels.length; i++) 
                        bxrLabels[i].parentElement.style.setProperty('--elemento-cor', 'var(--theme-' + bxrLabels[i].id + ')');
                    </script>
                    </div>
                </div>
            </div>
            <div class="boxContent">
                <div class="tituloCategoria" style="width: 100%; display: block; text-align: center;">
                Preview
                </div>
                <div style="display: block; width: 100%">
                    <div id="previewElementoPicked"></div>
                    <div id="previewColorPicked"></div>
                </div>
            </div>
            <div class="boxContent">
                <div class="tituloCategoria" style="width: 100%; display: block; text-align: left; padding-left: 10px;">
                Cor a atribuir
                </div>
                <div style="display:flex; background-color: transparent;">
                    <div style="display:flex">
                        <div id="options" style="display: block">
                        <button id="paletaAtualPicker" class="reverseButton" onClick="toggleColorMode(this);">{$svg_palette} Paleta Atual</button>
                        <button id="jscolorPicker" class="reverseButton" onClick="toggleColorMode(this);">{$svg_hue} Personalizada</button>
                        <button id="pantonePicker" class="reverseButton" onClick="toggleColorMode(this);">{$svg_pantone} Pantone</button>
                        <button id="materialPicker" class="reverseButton" onClick="toggleColorMode(this);">{$svg_google} Material Design</button>
                        {literal}
                        <label id="hexPicker" for"inputHEX" class="reverseButton fa-pencil" style="font-weight: 800; font-size: 1.1rem; padding-left: 15px;"; onClick="toggleColorMode(this);">Defina:<br />
                        <input type="text" id="inputHEX" style="width: 90%;" pattern="^#(?:[0-9a-fA-F]{6})$" onInput="onChangeInputHEX();" title="Cor no format hex [#RRGGBB]"/>
                        </label>
                        {/literal}
                        </div>
                    </div>
                    <div id="pickercontainer" style="display:flex">
                        <div class="boxContorno" id="boxCoresPaleta">
                            <div class="blockCor">
                            <div class="contentCor" id="boxContentPaleta">
                                <script>carregarPaletaAtual();</script>
                            </div>
                            </div>
                        </div>
                        <button id="colorPicker" data-jscolor="{}" style="display: none;"></button>
                        <div class="boxContorno" id="boxCores" style="display: none;">
                            <div class="blockCor">
                            <div class="contentCor" id="boxContent">
                                {section name=pc loop=$pcPantone}
                                <div class="cor" style="background-color: {$pcPantone[pc].cor}" onClick="boxCorClick('{$pcPantone[pc].nome}', '{$pcPantone[pc].cor}','{$pcPantone[pc].hspCor}')"></div>
                                {/section}
                            </div>
                            </div>
                        </div>
                        <div class="boxContorno" id="boxCoresMD" style="width: 210px; display: none;">
                            <div class="blockCor" style="width: 210px;">
                            <div class="contentCor" id="boxContentMD" style="width: 210px;">
                                {section name=pc loop=$pcMaterial}
                                <div class="cor" style="background-color: {$pcMaterial[pc].cor}" onClick="boxCorClick('{$pcMaterial[pc].nome}', '{$pcMaterial[pc].cor}','{$pcMaterial[pc].hspCor}')"></div>
                                {/section}
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="menuBarra">
            <div class="menuBarraItem" onClick="alterarRootVar();">
                :: Alterar ::
            </div>
            <div class="menuBarraItem" onClick="restaurarRootVar();">
                :: Restaurar ::
            </div> 
            <div class="menuBarraItem" onClick="restaurarRootcssPagina();">
                :: Restaurar Página ::
            </div> 
            <div class="menuBarraItem" onClick="novoTema();">
                :: Salvar Estilo ::
            </div>
        </div>
</div><!-- class=content -->
<script>
jscolor.presets.default = {
    format: 'hex',
    mode: 'HVS', 
    valueElement: '#selectedColor',
    required: false,
    backgroundColor: 'transparent',
    previewPosition: 'right',
    container: '#pickercontainer',
    position: 'bottom',
    onChange: onChangeColorPicker,
    hideOnLeave: false,
    shadow: false,
    height: 127,
    width:131,
    zIndex: 0,
}
</script>
