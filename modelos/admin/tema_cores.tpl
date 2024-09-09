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
    <div style="width: 100%; height: 5px; margin: 0; margin-top: 10px; padding: 0; border: 0; background-color: var(--theme-tituloBB);"></div>
    <div class="boxContent">
        <div style="display: flex; width: 100%; margin-top: 1rem;">
            <div id="previewElementoPicked" style="float: left; min-width: 150px; display: block"></div>
            <div id="previewColorPicked" style="float: right; min-width: 150px; display: block"></div>
        </div>
    </div>
    <div class="boxContent">
        <div class="tituloClaro">Cor a atribuir</div>
        <div style="display:flex; background-color: transparent; margin-bottom: 1rem;">
            <div style="display:flex: min-height: 60vmin;">
                <div id="options" style="display: block; margin-right: 0px;">
                <button id="paletaAtualPicker" class="reverseButton" onClick="toggleColorMode(this);">{$svg_palette} Paleta Atual</button>
                <button id="jscolorPicker" class="reverseButton" onClick="toggleColorMode(this);">{$svg_hue} Personalizada</button>
                <button id="pantonePicker" class="reverseButton" onClick="toggleColorMode(this);">{$svg_pantone} Pantone</button>
                <button id="materialPicker" class="reverseButton" onClick="toggleColorMode(this);">{$svg_google} Material Design</button>
                {literal}
                <label id="hexPicker" for"inputHEX" class="reverseButton fa-pencil" style="font-weight: 800; font-size: 1.1rem; padding-left: 5px;"; onClick="toggleColorMode(this);">Defina:<br />
                <input type="text" id="inputHEX" style="width: 90%;" pattern="^#(?:[0-9a-fA-F]{6})$" onInput="onChangeInputHEX();" title="Cor no format hex [#RRGGBB]"/>
                </label>
                {/literal}
                </div>
            </div>
            <div id="pickercontainer" style="display:flex; float: right; margin-left: 2px;">
                <div class="boxContorno" id="boxInputHEX" style="display: none;">
                    <div class="blockCor">
                    <div class="contentCor" id="boxContentInputHEX" style="display: flex; text-align: center; align-items: center;">
                        <div style="flex: 0 1 auto; display: flex; min-width: 100%; text-align: center;">Digite uma cor #RRGGBB</div>
                    </div>
                    </div>
                </div>
                <div class="boxContorno" id="boxCoresPaleta">
                    <div class="blockCor">
                    <div class="contentCor" id="boxContentPaleta">
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
