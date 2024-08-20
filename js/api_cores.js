/*--------------------------------------------------------------------------------

  cores.js
  (c) ecgf - 2005-2024

  Rotinas para definição de cor de um ou mais elementos dado seu id ou sua classe.

  depende fortemente dos estilos definidos em estilos/colorbase.css

--------------------------------------------------------------------------------*/

/*
 * getThemeColor - obtém a cor de uma das variáveis de cor para o estilo css atual
 *
 * recebe: 
 * umaCor - o nome de uma variável de cor
 *
 * retorna:
 * a cor definida para essa variável em formato #RRGGBB
 */
const getThemeColor = (umaCor) => {
    var c = document.createElement("canvas");
    var ctx = c.getContext("2d");
    const root = document.querySelector(':root');

    ctx.fillStyle = getComputedStyle(root).getPropertyValue('--theme-' + umaCor);
    aCorHex = ctx.fillStyle;

    c.remove();
    return aCorHex;
}

/*
 * getThemeColorHSP - obtém o HSP [Highly Sensitive Poo equation from http://alienryderflex.com/hsp.html]
 *                    de uma das variáveis de cor para o estilo css atual
 *
 * recebe:
 * umaCor - o nome de uma variável de cor
 *
 * retorna:
 * #000000 para cores mais claras ou #FFFFFF para cores mais escuras
 */
const getThemeColorHSP = (umaCor) => {
    var c = document.createElement("canvas");
    var ctx = c.getContext("2d");
    const root = document.querySelector(':root');

    if (umaCor.startsWith('var(--theme-'))
        umaCor = umaCor.slice(12, -1);

    ctx.fillStyle = getComputedStyle(root).getPropertyValue('--theme-' + umaCor);
    const aCorHex = ctx.fillStyle;

    let r = parseInt(aCorHex.substr(1,2), 16);
    let g = parseInt(aCorHex.substr(3,2), 16);
    let b = parseInt(aCorHex.substr(5,2), 16);

    let hsp = Math.sqrt((0.299 * r * r) + (0.587 * g * g) + (0.114 * b * b));

    if (hsp > 128)
        hspColor = '#000000';
    else
        hspColor = '#ffffff';
    c.remove();
    return hspColor;
}

/*
 * getThemePalette - obtém as cores das variáveis de cor para o estilo css atual
 *
 * retorna:
 * array com as cores das variáveis de cor no format #RRGGBB
 */
const getThemePalette = () => {
    var c = document.createElement("canvas");
    var ctx = c.getContext("2d");
    const root = document.querySelector(':root');
    const cookieStyles = document.getElementById('selectElemento').options;
    var cores = [];

    for (const cookieStyle of cookieStyles) {
        umaCor = '--theme-' + cookieStyle.value;
        ctx.fillStyle = getComputedStyle(root).getPropertyValue(umaCor);
        if (!cores.includes(ctx.fillStyle))
            cores[cores.length] = ctx.fillStyle; 
    }

    c.remove();
    return cores.sort();
}

/*
 * getAllThemeColorPairs - obtem todos os pares de cores em uso
 *
 * retorna:
 * dictionary com as cores das variáveis de cor no format #RRGGBB
 */
const getAllThemeColorPairs = () => {
    var c = document.createElement("canvas");
    var ctx = c.getContext("2d");
    const root = document.querySelector(':root');
    const cookieStyles = document.getElementById('selectElemento').options;
    var cores = {};

    for (const cookieStyle of cookieStyles) {
        umaCor = '--theme-' + cookieStyle.value;
        ctx.fillStyle = getComputedStyle(root).getPropertyValue(umaCor);
        cores[cookieStyle.value] = ctx.fillStyle; 
    }

    c.remove();
    return cores;
}

/*
 * hex2i - transforma uma string contendo um hexadecimal em um inteiro
 *
 * recebe:
 * f1 - um string contendo uma representação hexadecimal de um número inteiro
 *
 * retorna:
 * f1 convertido para inteiro
 */
function hex2i(f1) {
    f1 = f1.toUpperCase();
    rval = parseInt(f1,16);
    return rval;
}

/*
 * colorAction - 
 *
 * recebe:
 * action - o metodo api a ser executado
 * options - dict json com opções para submissão (body, method etc)
 *
 * retorna:
 * o json retornado pela execução do método action
 */
async function colorAction(action, options = {}) {
    const url = window.includePATH + 'api/' + action + '.php';

    try {
        const response = await fetch(url, options);
        
        let responseData = await response.json();
        let r = eval("(" + responseData + ")");

        return r;

    } catch(err) {
        return JSON.parse('{"status": "error", "message": "' + err.message + '"}');
    };
};

/*
 * alterarRootVar - altera propriedade (cor) de uma variável definida em :root
 *
 * recebe:
 * não recebe nada mas utiliza os campos de id idPagina, selectElemento, selectedColor no formulário
 *
 * retorna:
 * nada, mas um toast com o resultado será exibido
 */
const alterarRootVar = async () => {
    const aPagina = document.getElementById('idPagina').value;
    const root_var = document.getElementById('selectElemento').value;
    const color = document.getElementById('selectedColor').value;

    if (root_var == "" | color == "") {
        createToast('warning', 'Elemento a ser alterado ou nova cor não selecionados');
        return;
    }

    let formData = new FormData();
    formData.append('idPagina', aPagina);
    formData.append('root_var', root_var);
    formData.append('color', color);
    let r = await colorAction('addColorCookie', {body: formData, method: 'POST'});
    if (r.status == 'success') {
        const root_css = document.querySelector(':root');
        root_css.style.setProperty("--theme-" + root_var, color);
    } 
    createToast(r.status, r.message);
}

/*
 * restaurarRootVar - restaurar propriedade (cor) de uma variável definida em :root
 *
 * recebe:
 * não recebe nada mas utiliza os campos de id idPagina, selectElemento no formulário
 *
 * retorna:
 * nada, mas um toast com o resultado será exibido
 */
const restaurarRootVar = async () => {
    const aPagina = document.getElementById('idPagina').value;
    const root_var = document.getElementById('selectElemento').value;

    if (root_var == "") {
        createToast('warning', 'Elemento a ser restaurado não selecionado');
        return;
    }

    let formData = new FormData();
    formData.append('idPagina', aPagina);
    formData.append('root_var', root_var);
    let r = await colorAction('delColorCookie', {body: formData, method: 'POST'});
    if (r.status == 'success') {
        const root_css = document.querySelector(':root');
        root_css.style.setProperty("--theme-" + root_var, '');
    } 
    createToast(r.status, r.message);
}

/*
 * restaurarRootcssPagina - restaurar propriedade (cor) de todas as variáveis definidas em :root
 *                          que foram alteradas anteriormente
 *
 * recebe:
 * não recebe nada mas utiliza o campo de id idPagina no formulário
 *
 * retorna:
 * nada, mas um toast com o resultado será exibido
 */
const restaurarRootcssPagina = async () => {
    const aPagina = document.getElementById('idPagina').value;

    let formData = new FormData();
    formData.append('idPagina', aPagina);
    let r = await colorAction('delAllColorCookies', {body: formData, method: 'POST'});
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

/*
 * novoEstilo - chamada para preparar e exibir form de salvamento de estilo
 */
const novoEstilo = async () => {
    const idPagina = document.getElementById('idPagina').value;

    const formData = new FormData();
    formData.append('idPagina', idPagina);

    let r = await colorAction('novoEstilo', {method: 'POST', body: formData});

    if (r.status == 'success')
        exibirFormDiv(r.message);
    else
        createToast(r.status, r.message);
}

/*
 * salvarEstilo - chamada após exibição do form de salvamento de estilos
 */
const salvarEstilo = async () => {

    function replacer (key, value) {
       return (!!value || value === false || value === '' || typeof value === 'number') ? value : undefined;
    }


    const idPagina = document.getElementById('idPagina').value;
    const nomeEstilo = document.getElementById('nomeEstilo').value;
    const comentarioEstilo = document.getElementById('comentarioEstilo').value;

    if (nomeEstilo == '' | comentarioEstilo == '') {
        createToast('warning', 'Nome do Estilo ou comentário não definidos');
        return ;
    }

    const formData = new FormData();
    formData.append('idPagina', idPagina);
    formData.append('nomeEstilo', nomeEstilo);
    formData.append('comentarioEstilo', comentarioEstilo);
    formData.append('paresDeCores', JSON.stringify(getAllThemeColorPairs()));

    let r = await colorAction('salvarEstilo', {method: 'POST', body: formData});
    ocultarFormDiv();

    createToast(r.status, r.message);
}
