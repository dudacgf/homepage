<script type="text/javascript" src="{$includePATH}js/api_cores.js"></script>
<script type="text/javascript" src="{$includePATH}js/jscolor.min.js"></script>
<script type="text/javascript" src="{$includePATH}js/api_cores_aux.js"></script>
<div class="content" style="text-align: left; height: auto;">
    <div class="tituloCores">Seleção de Cores</div>
        <input type="hidden" id="selectedColor" value="" />
        <input type="hidden" name="idPagina" id="idPagina" value="{$idPagina}">
        <div class="flex100">
            <div class="boxContent">
                <div class="tituloCategoria">
                Elementos
                </div>
                <div class="blockElemento">
                    <div class="contentElemento" id="selectElemento">
                        {section name=opt loop=$elementosColoridos}
                        <label class="boxRadioLabel"id="lbl_{$elementosColoridos[opt].cookieElemento}" >
                        <input class="boxRadio" type="radio" id="{$elementosColoridos[opt].cookieElemento}" name="selectElemento" value="{$elementosColoridos[opt].cookieElemento}" onClick="onChangeElementoBoxElementoCor(this);" />
                        {$elementosColoridos[opt].descricaoElemento}
                        </label> 
                        {/section}
                    <script>
                    const elementos = document.getElementsByClassName('boxRadioLabel');
                    for (var i = 0;  i < elementos.length; i++) 
                        elementos[i].style.setProperty('--elemento-cor', 'var(--theme-' + (elementos[i].id.replace('lbl_', '')) + ')');
                    </script>
                    </div>
                </div>
            </div>
            <div class="boxContent">
                <div class="tituloCategoria" style="width: 100%; display: block; text-align: center;">
                Preview
                </div>
                <div style="display: flex; width: 100%">
                    <div id="previewElementoPicked"></div>
                    <div id="previewColorPicked"></div>
                </div>
            </div>
            <div class="boxContent">
                <div class="tituloCategoria" style="width: 100%; display: block; text-align: left; padding-left: 10px;">
                Cores
                </div>
                <div style="display:flex; background-color: transparent;">
                    <div id="options" style="display:flex">
                        <div style="display: block">
                        <button id="paletaAtual" class="submit reverseButton" onClick="toggleColorMode(this);">{$svg_palette} Paleta Atual</button>
                        <button id="rainbowButton" class="submit reverseButton" onClick="toggleColorMode(this);">{$svg_hue} Personalizada</button>
                        <button id="pantone" class="submit reverseButton" onClick="toggleColorMode(this);">{$svg_pantone} Pantone</button>
                        <button id="material" class="submit reverseButton" onClick="toggleColorMode(this);">{$svg_google} Material Design</button>
                        {literal}
                        <label for"inputHEX" class="reverseButton fa-pencil" style="font-weight: 800; font-size: 1.1rem; padding-left: 3px;";>Defina:<br />
                        <input type="text" id="inputHEX" pattern="^#(?:[0-9a-fA-F]{6})}$" onInput="onChangeInputHEX();" title="Cor no format hex [#RRGGBB]"/>
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
                                {section name=pc loop=$paresCores}
                                <div class="cor" style="background-color: {$paresCores[pc].valorCor}" onClick="boxCorClick('{$paresCores[pc].nomeCor}', '{$paresCores[pc].valorCor}','{$paresCores[pc].hspCor}')"></div>
                                {/section}
                            </div>
                            </div>
                        </div>
                        <div class="boxContorno" id="boxCoresMD" style="width: 210px; display: none;">
                            <div class="blockCor" style="width: 210px;">
                            <div class="contentCor" id="boxContentMD" style="width: 210px;">
                                {section name=pc loop=$pcMaterial}
                                <div class="cor" style="background-color: {$pcMaterial[pc].valorCor}" onClick="boxCorClick('{$pcMaterial[pc].nomeCor}', '{$pcMaterial[pc].valorCor}','{$pcMaterial[pc].hspCor}')"></div>
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
            <div class="menuBarraItem" onClick="novoEstilo();">
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
    zIndex: 1,
}
</script>
