/*
 * api_cores_aux.js - funções para a interface do formulário de cores
 *
 * (c) ecgf@theshiresco.com - 2003-2024
 *
 * [quem tem medo de nomes grandes de função?]
 */

/*
 * Exibe cor selecionada no jscolors preview de cores e guarda a cor para posterior uso
 */
function onChangeColorPicker() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#selectedColor');
  const colorPicker = document.querySelector('#colorPicker');

  preview.style.backgroundColor = colorPicker.jscolor.valueElement.value;
  preview.style.color = HSP(colorPicker.jscolor.valueElement.value);
  preview.innerHTML = '<div class="textMiddle">Custom Color<br/>' + colorPicker.jscolor.valueElement.value + '</div>';
  zzselect.value = colorPicker.jscolor.valueElement.value;
}

/*
 * Exibe cor selecionada em um dos boxes de cores (menos o jscolors acima) 
 * e guarda a cor para posterior uso
 */ 
function boxCorClick(nomeCor, valorCor, hspCor) {
    const bp = document.getElementById('previewColorPicked');
    const sc = document.getElementById('selectedColor');
    
    sc.value = valorCor;
    bp.style.backgroundColor = valorCor;
    bp.style.color = hspCor;
    bp.innerHTML = '<div class="textMiddle">' + nomeCor + '<br/>' + valorCor.toUpperCase() + '</div>';
}

/* 
 * Exibe root-var e cor selecionada na lista de elementos coloridos e guarda
 * a root-var para posterior uso
 */
function onChangeElementoBoxElementoCor(oboxCor) {
    const preview = document.querySelector('#previewElementoPicked');
    const aCor = getThemeColor(oboxCor.id);
    const sElemento = document.getElementById('selectElemento');

    sElemento.setAttribute('value', oboxCor.id);
    preview.style.backgroundColor = aCor;
    preview.style.color = HSP(aCor);
    preview.innerHTML = '<div class="textMiddle">--theme-' + oboxCor.id + '<br />' + aCor.toUpperCase() + '</div>';
}

/* 
 * critica o input do campo inputHEX e, quando ok, exibe a cor definida 
 * e guarda a cor para posterior uso
 */
function onChangeInputHEX() {
  const inputHex = document.getElementById('inputHEX');
  const aCor = inputHex.value;

  if (! /^#[0-9A-F]{6}$/i.test(aCor)) 
    return;

  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#selectedColor');

  preview.style.backgroundColor = aCor;
  preview.style.color = HSP(aCor);
  preview.innerHTML = '<div class="textMiddle">Definida<br/>' + aCor + '</div>';
  zzselect.value = aCor;
}

/*
 * reage ao clique em uma das opções de definição de cor
 */
function toggleColorMode(idBotaoOpcaoModo) {
    if (idBotaoOpcaoModo.id == 'paletaAtual') {
       document.getElementById('boxCoresPaleta').style.display = 'block';
       document.getElementById('boxCores').style.display = 'none';
       document.getElementById('boxCoresMD').style.display = 'none';
       document.querySelector('#colorPicker').jscolor.hide(); 
    } else if (idBotaoOpcaoModo.id == 'rainbowButton') {
       document.getElementById('boxCoresPaleta').style.display = 'none';
       document.getElementById('boxCores').style.display = 'none';
       document.getElementById('boxCoresMD').style.display = 'none';
       document.querySelector('#colorPicker').jscolor.show(); 
    } else if (idBotaoOpcaoModo.id == 'pantone') {
       document.getElementById('boxCoresPaleta').style.display = 'none';
       document.getElementById('boxCores').style.display = 'block';
       document.getElementById('boxCoresMD').style.display = 'none';
       document.querySelector('#colorPicker').jscolor.hide(); 
    } else if (idBotaoOpcaoModo.id == 'material') {
       document.getElementById('boxCoresPaleta').style.display = 'none';
       document.getElementById('boxCores').style.display = 'none';
       document.getElementById('boxCoresMD').style.display = 'block';
       document.querySelector('#colorPicker').jscolor.hide(); 
    }
}

/*
 * carrega todas as cores (únicas) das cores root-var da página atual e
 * as dispões no boxContentPaleta
 */
function carregarPaletaAtual() {
    const bcp = document.getElementById('boxContentPaleta');
    const themeColors = getThemePalette();

    for (const cor of themeColors) {
        newOption = '<div class="cor" style="background-color: ' + cor + '; margin: 2px; border: 0.5px solid #d0d0d0;" ' +
            'onClick="boxCorClick(\'Paleta Atual\', \'' + cor + '\',\'' + HSP(cor) + '\')"></div>';
        bcp.insertAdjacentHTML("beforeend", newOption);
    }
}
/**
 * @param {number} red - Red component 0..1
 * @param {number} green - Green component 0..1
 * @param {number} blue - Blue component 0..1
 * @return {number[]} Array of HSL values: Hue as degrees 0..360, Saturation and Lightness in reference range [0,100]
 */
function rgbToHsl (red, green, blue) {
    let max = Math.max(red, green, blue);
    let min = Math.min(red, green, blue);
    let [hue, sat, light] = [NaN, 0, (min + max)/2];
    let d = max - min;

    if (d !== 0) {
        sat = (light === 0 || light === 1)
            ? 0
            : (max - light) / Math.min(light, 1 - light);

        switch (max) {
            case red:   hue = (green - blue) / d + (green < blue ? 6 : 0); break;
            case green: hue = (blue - red) / d + 2; break;
            case blue:  hue = (red - green) / d + 4;
        }

        hue = hue * 60;
    }

    // Very out of gamut colors can produce negative saturation
    // If so, just rotate the hue by 180 and use a positive saturation
    // see https://github.com/w3c/csswg-drafts/issues/9222
    if (sat < 0) {
        hue += 180;
        sat = Math.abs(sat);
    }

    if (hue >= 360) {
        hue -= 360;
    }

    return [hue, sat * 100, light * 100];
}
