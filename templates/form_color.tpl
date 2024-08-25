<script type="text/javascript" src="{$includePATH}js/api_cores.js"></script>
<script type="text/javascript" src="{$includePATH}js/jscolor.min.js"></script>
<script type="text/javascript" src="{$includePATH}js/api_cores_aux.js"></script>
<div class="content" style="text-align: left; height: auto;">
    <div class="tituloLateral" style="width: inherit; float: none; font-size: larger; margin: 0;">Seleção de Cores</div>
    <div class="lineForm">
        <div class="formLateral" style="width: 100%;">
            <form id="colorForm" action="javascript: void(0);">
                <input type="hidden" id="selectedColor" value="" />
                <input type="hidden" name="idPagina" id="idPagina" value="{$idPagina}">
                <div style="width: 100%;">
                    <div style="float: left;">
                        <div class="tituloCategoria">Elementos</div>
                        <div class="interior">
                            <select id="selectElemento" size="6">
                                {section name=opt loop=$elementosColoridos}
                                <option value="{$elementosColoridos[opt].cookieElemento}" style="background-color: var(--theme-{$elementosColoridos[opt].cookieElemento});">{$elementosColoridos[opt].descricaoElemento}</option>
                                {/section}
                            </select>
                            <script>
                            function setColor(item, index) {
                                let aCor = getThemeColorHSP(item.style.backgroundColor);
                                item.style.color = aCor;
                            }
                            var select = document.getElementById('selectElemento');
                            Array.from(select.options).forEach(setColor);
                            </script>
                        </div>
                    </div>
                    <div style="float: left;">
                        <div class="tituloCategoria" style="width: 100%; display: block;">Cores</div>
                        <div style="display:flex; background-color: transparent;">
                            <div id="options" style="display:flex">
                                <div style="display: block">
                                <button id="rainbowButton" class="submit reverseButton" onClick="toggleColorMode(this);">{$svg_rainbow} Pick a Color</button>
                                <button id="pantone" class="submit reverseButton fa-swatchbook" onClick="toggleColorMode(this);">Pantone</button>
                                <button id="material" class="submit reverseButton" onClick="toggleColorMode(this);">{$svg_google}</svg>Material Design</button>
                                </div>
                            </div>
                            <div id="pickercontainer" style="display:flex">
                                <button id="colorPicker" data-jscolor="{}" style="display: none;"></button>
                                <div class="boxContorno" id="boxCores">
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
                        <div id="previewColorPicked"></div>
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
                </div>
            </form>
        </div><!-- class=formLateral" -->
    </div><!-- class=lineForm-->
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
}
</script>
