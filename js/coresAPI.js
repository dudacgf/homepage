/*--------------------------------------------------------------------------------

  cores.js
  (c) ecgf - 2005-2024

  Rotinas para definição de cor de um ou mais elementos dado seu id ou sua classe.

  depende fortemente dos estilos definidos em estilos/colorbase.css

--------------------------------------------------------------------------------*/

/** 
 * usa um fillStyle de uma canvas para obter uma cor em formato #RRGGBB
 *
 * @param {string} umaCor - uma cor em qualquer formato válido html/css
 *
 * @returns {string} a cor em formato #RRGGBB. se *umaCor* não estiver num formato válido, retorna #000000
 *
 */
function colorToHex(umaCor) {
    const c = document.createElement("canvas");
    const ctx = c.getContext("2d");

    ctx.fillStyle = umaCor;
    aCorHex = ctx.fillStyle;

    c.remove();
    return aCorHex;
}


/**
 * calcula o HSP [Highly Sensitive Poo equation from http://alienryderflex.com/hsp.html]
 *
 * @param {string} aCorHex - cor em formato hex [#RRGGBB]
 *
 * @returns {string }  * #000000 para cores mais claras ou #FFFFFF para cores mais escuras
 */
function HSP(aCorHex) {
    let r = parseInt(aCorHex.substr(1,2), 16);
    let g = parseInt(aCorHex.substr(3,2), 16);
    let b = parseInt(aCorHex.substr(5,2), 16);

    let hsp = Math.sqrt((0.299 * r * r) + (0.587 * g * g) + (0.114 * b * b));

    if (hsp > 128)
        hspColor = '#000000';
    else
        hspColor = '#ffffff';

    return hspColor;
}


/**
 * Obtém a cor de uma das variáveis de cor para o estilo css atual
 *
 * @param {string} umaCor - o nome de uma root-var de cor
 *
 * @returns {string) - a cor definida para essa variável em formato #RRGGBB
 */
const getThemeColor = (umaCor) => {
    const root = document.querySelector(':root');

    return colorToHex(getComputedStyle(root).getPropertyValue('--theme-' + umaCor));
}


/**
 * obtém o HSP [Highly Sensitive Poo equation from http://alienryderflex.com/hsp.html]
 * de uma das root-vars de cor para o estilo css atual
 *
 * @param {string} umaCor - o nome de uma variável de cor
 *
 * @returns {string }  * #000000 para cores mais claras ou #FFFFFF para cores mais escuras
 */
const getThemeColorHSP = (umaCor) => {
    const root = document.querySelector(':root');

    if (umaCor.startsWith('var(--theme-'))
        umaCor = umaCor.slice(12, -1);

    const aCorHex = colortoHex(getComputedStyle(root).getPropertyValue('--theme-' + umaCor));

    return HSP(aCorHex);
}

/**
 * obtém as cores das variáveis de cor para o estilo css atual
 *
 * @returns {string[]} - cores das variáveis de cor no format #RRGGBB
 */
const getThemePalette = () => {
    var c = document.createElement("canvas");
    var ctx = c.getContext("2d");
    const root = document.querySelector(':root');
    const cookieStyles = document.getElementsByClassName('boxRadio');
    var cores = [];

    for (var i = 0; i < cookieStyles.length; i++) {
        var umaCor = '--theme-' + cookieStyles[i].id;
        ctx.fillStyle = getComputedStyle(root).getPropertyValue(umaCor);
        if (!cores.includes(ctx.fillStyle))
            cores[cores.length] = ctx.fillStyle; 
    }

    c.remove();
    return cores.sort();
}

/**
 * Obtém todos os pares [cor root-var, cor definida] para a página atual
 *
 * @returns {Array} - dictionary com as cores das variáveis de cor no format #RRGGBB
 */
const getAllThemeColorPairs = () => {
    var c = document.createElement("canvas");
    var ctx = c.getContext("2d");
    const root = document.querySelector(':root');
    const cookieStyles = document.getElementById('selectElemento').getElementsByClassName('boxRadio');
    var cores = {};

    for (const cookieStyle of cookieStyles) {
        umaCor = '--theme-' + cookieStyle.value;
        ctx.fillStyle = getComputedStyle(root).getPropertyValue(umaCor);
        cores[cookieStyle.value] = ctx.fillStyle; 
    }

    c.remove();
    return cores;
}

/**
 * altera propriedade (cor) de uma variável definida em :root
 *
 * @param {} não recebe nada mas utiliza os campos de id idPagina, selectElemento, selectedColor no formulário
 *
 */
const alterarRootVar = async () => {
    const aPagina = document.getElementById('idPagina').value;
    const root_var = document.getElementById('selectElemento').getAttribute('value');
    const color = document.getElementById('selectedColor').value;

    if (root_var == "" | color == "") {
        createToast('warning', 'Elemento a ser alterado ou nova cor não selecionados');
        return;
    }

    let formData = new FormData();
    formData.append('idPagina', aPagina);
    formData.append('root_var', root_var);
    formData.append('color', color);
    let r = await chamadaAPI('addColorCookie', {body: formData, method: 'POST'});
    if (r.status == 'success') {
        const root_css = document.querySelector(':root');
        const preview = document.getElementById('previewElementoPicked');
        
        root_css.style.setProperty("--theme-" + root_var, color);
        preview.style.backgroundColor = 'var(--theme' + color + ')';
        preview.style.color = HSP(color);
        preview.innerHTML = '<div class="textMiddle">--theme-' + root_var + '<br />' + color.toUpperCase() + '</div>';
    } 
    createToast(r.status, r.message);
}

/**
 * restaura propriedade (cor) de uma variável definida em :root
 *
 * @param {} - não recebe nada mas utiliza os campos de id idPagina, selectElemento no formulário
 *
 * @returns {} - nada, mas um toast com o resultado será exibido
 */
const restaurarRootVar = async () => {
    const aPagina = document.getElementById('idPagina').value;
    const root_var = document.getElementById('selectElemento').getAttribute('value');

    if (root_var == "") {
        createToast('warning', 'Elemento a ser restaurado não selecionado');
        return;
    }

    let formData = new FormData();
    formData.append('idPagina', aPagina);
    formData.append('root_var', root_var);
    let r = await chamadaAPI('delColorCookie', {body: formData, method: 'POST'});
    if (r.status == 'success') {
        const root = document.querySelector(':root');
        root.style.setProperty("--theme-" + root_var, '');

        const color = window.getComputedStyle(root).getPropertyValue('--theme-' + root_var);
        const preview = document.getElementById('previewElementoPicked');
        preview.style.backgroundColor = color;
        preview.style.color = HSP(color);
        preview.innerHTML = '<div class="textMiddle">--theme-' + root_var + '<br />' + color.toUpperCase() + '</div>';
    } 
    createToast(r.status, r.message);
}

/**
 * restaurar propriedade (cor) de todas as variáveis definidas em :root
 * que foram alteradas anteriormente
 *
 * @param {} - não recebe nada mas utiliza o campo de id idPagina no formulário
 *
 * @returns {} - nada, mas um toast com o resultado será exibido
 */
const restaurarRootcssPagina = async () => {
    const aPagina = document.getElementById('idPagina').value;

    let formData = new FormData();
    formData.append('idPagina', aPagina);
    let r = await chamadaAPI('delAllColorCookies', {body: formData, method: 'POST'});
    if (r.status == 'success') {
        const root_css = document.querySelector(':root');
        var all_vars = [];
        for (var i = 0; i < root_css.style.length; i++) {
            if (root_css.style.item(i).startsWith('--theme-')) {
                all_vars.push(root_css.style.item(i));
            }
        }
        for (var i = 0; i < all_vars.length; i++) {
            root_css.style.setProperty(all_vars[i], ''); 
        }
    } 
    createToast(r.status, r.message); 
}

/**
 * chamada para preparar e exibir form de salvamento de estilo
 */
const novoEstilo = async () => {
    const idPagina = document.getElementById('idPagina').value;

    const formData = new FormData();
    formData.append('idPagina', idPagina);

    let r = await chamadaAPI('novoEstilo', {method: 'POST', body: formData});

    if (r.status == 'success')
        exibirFormDiv(r.message);
    else
        createToast(r.status, r.message);
}

/** 
 * verifica se um estilo já existe
 *
 * @param {string} nomeEstilo - o nome do estilo a se verificar se existe
 *
 * @returns {boolean} - true se o estilo já existe, false em contrário
 *
 */
const existeEstilo = async(nomeEstilo) => {
    const formData = new FormData();
    formData.append('nomeEstilo', nomeEstilo);

    let r = await chamadaAPI('existeEstilo', {method: 'POST', body: formData});

    if (r.status = 'info' && r.message == 'existente') 
        return true;
    else
        return false;
}

/**
 * salvarEstilo - chamada após exibição do form de salvamento de estilos
 */
const salvarEstilo = async () => {
    const idPagina = document.getElementById('idPagina').value;
    const nomeEstilo = document.getElementById('nomeEstilo').value;
    const comentarioEstilo = document.getElementById('comentarioEstilo').value;

    // não preciso mais do form. pode ocultar independente do resultado
    ocultarFormDiv();

    if (nomeEstilo == '' | comentarioEstilo == '') {
        createToast('warning', 'Nome do Estilo ou comentário não definidos');
        return ;
    }

    let existe = await existeEstilo(nomeEstilo);

    if (existeEstilo(nomeEstilo) && (!confirm('Já existe um estilo com esse nome. Sobrepõe?'))) {
        createToast('info', 'Estilo não foi salvo');
        return ;
    }

    const formData = new FormData();
    formData.append('idPagina', idPagina);
    formData.append('nomeEstilo', nomeEstilo);
    formData.append('comentarioEstilo', comentarioEstilo);
    formData.append('paresDeCores', JSON.stringify(getAllThemeColorPairs()));
    let r = await chamadaAPI('salvarEstilo', {method: 'POST', body: formData});

    createToast(r.status, r.message);
}

/**
 * Exibe cor selecionada no jscolors preview de cores e guarda a cor para posterior uso
 *
 * @listens evento de clique em uma cor do quadro do jscolor
 *
 */
function onChangeColorPicker() {
  const preview = document.querySelector('#previewColorPicked');
  const select = document.querySelector('#selectedColor');
  const colorPicker = document.querySelector('#colorPicker');

  preview.style.backgroundColor = colorPicker.jscolor.valueElement.value;
  preview.style.color = HSP(colorPicker.jscolor.valueElement.value);
  preview.innerHTML = '<div class="textMiddle">Custom Color<br/>' + colorPicker.jscolor.valueElement.value + '</div>';
  select.value = colorPicker.jscolor.valueElement.value;
}

/**
 * Exibe cor selecionada em um dos boxes de cores (menos o jscolors acima) 
 * e guarda a cor para posterior uso
 *
 * @listens evento de clique em um dos boxCores (paleta atual, pantone, material)
 *
 */ 
function boxCorClick(nomeCor, valorCor, hspCor) {
    const bp = document.getElementById('previewColorPicked');
    const sc = document.getElementById('selectedColor');
    
    sc.value = valorCor;
    bp.style.backgroundColor = valorCor;
    bp.style.color = hspCor;
    bp.innerHTML = '<div class="textMiddle">' + nomeCor + '<br/>' + valorCor.toUpperCase() + '</div>';
}

/**
 * Exibe root-var e cor selecionada na lista de elementos coloridos e guarda
 * a root-var para posterior uso
 *
 * @listens evento de clique no #boxElementos
 *
 */
function onChangeElementoBoxElementoCor(oboxCor) {
    const preview = document.querySelector('#previewElementoPicked');
    const aCor = getThemeColor(oboxCor.id);
    const sElemento = document.getElementById('selectElemento');

    sElemento.setAttribute('value', oboxCor.id);
    preview.style.backgroundColor = 'var(--theme-' + oboxCor.id + ')';
    preview.style.color = HSP(aCor);
    preview.innerHTML = '<div class="textMiddle">--theme-' + oboxCor.id + '<br />' + aCor.toUpperCase() + '</div>';
}

/** 
 * critica o input do campo inputHEX e, quando ok, exibe a cor definida 
 * e guarda a cor para posterior uso
 *
 * @listens evento de clique no boxElementos
 *
 */
function onChangeInputHEX() {
  const inputHex = document.getElementById('inputHEX');
  const aCor = inputHex.value;

  if (! /^#[0-9A-F]{6}$/i.test(aCor)) 
    return;

  const preview = document.querySelector('#previewColorPicked');
  const select = document.querySelector('#selectedColor');

  preview.style.backgroundColor = aCor;
  preview.style.color = HSP(aCor);
  preview.innerHTML = '<div class="textMiddle">Definida<br/>' + aCor + '</div>';
  select.value = aCor;
}

/**
 * reage ao clique em uma das opções de definição de cor
 *
 * @listen evento de clique nos buttons ou label da coluna de opções
 */
function toggleColorMode(idBotaoOpcaoModo) {
    if (idBotaoOpcaoModo.id == 'paletaAtualPicker') {
       document.getElementById('boxCoresPaleta').style.display = 'block';
       document.getElementById('boxCores').style.display = 'none';
       document.getElementById('boxCoresMD').style.display = 'none';
       document.getElementById('colorPicker').jscolor.hide(); 
    } else if (idBotaoOpcaoModo.id == 'jscolorPicker') {
       document.getElementById('boxCoresPaleta').style.display = 'none';
       document.getElementById('boxCores').style.display = 'none';
       document.getElementById('boxCoresMD').style.display = 'none';
       document.getElementById('colorPicker').jscolor.show(); 
    } else if (idBotaoOpcaoModo.id == 'pantonePicker') {
       document.getElementById('boxCoresPaleta').style.display = 'none';
       document.getElementById('boxCores').style.display = 'block';
       document.getElementById('boxCoresMD').style.display = 'none';
       document.getElementById('colorPicker').jscolor.hide(); 
    } else if (idBotaoOpcaoModo.id == 'materialPicker') {
       document.getElementById('boxCoresPaleta').style.display = 'none';
       document.getElementById('boxCores').style.display = 'none';
       document.getElementById('boxCoresMD').style.display = 'block';
       document.getElementById('colorPicker').jscolor.hide(); 
    } else if (idBotaoOpcaoModo.id == 'hexPicker') {
       document.getElementById('boxCoresPaleta').style.display = 'none';
       document.getElementById('boxCores').style.display = 'none';
       document.getElementById('boxCoresMD').style.display = 'none';
       document.getElementById('colorPicker').jscolor.hide(); 
    }
}

/**
 * carrega todas as cores (únicas) das cores root-var da página atual e
 * as dispões no boxContentPaleta
 *
 * chamada durante a carga da página
 */
function carregarPaletaAtual() {
    const bcp = document.getElementById('boxContentPaleta');
    const themeColors = getThemePalette();

    for (const cor of themeColors) {
        newOption = '<div class="cor" style="background-color: ' + cor + '; margin: 1px; border: 0.5px solid #d0d0d0;" ' +
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