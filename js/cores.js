/*----------------------------------------------------------

  cores.js
  (c) ecgf - 2005

  Rotinas para definição de cor de um ou mais elementos dado seu id ou sua classe.

  note que este script depende fortemente dos estilos 
  definidos em $hp_homepage_path . estilos.css

----------------------------------------------------------*/

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
 * pixColrUrl - retorna uma url para a rotina que desenha e colore os 
 *               círculos concêntricos utilizados como background-image
 *
 * recebe:
 * aColor - uma cor html válida em quaquer format ('#rrggbb', 'violet', 'rgb(255, 255, 255)', 'hsl(250, 80%, 35%)' etc)
 *
 * retorna:
 * a url para a rotina de colorir os círculos concêntricos
 */
function picColorUrl(aColor) {
    var ctx = document.createElement("canvas").getContext("2d");
    ctx.fillStyle = aColor;
    color = ctx.fillStyle;
    return 'url("../drawing/background.php?r=' + hex2i(color.substr(1, 2)) + '&g=' + hex2i(color.substr(3, 2)) + '&b=' + hex2i(color.substr(5, 2)) + '")';
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
        r = eval("(" + responseData + ")");

        return r;

    } catch(err) {
        return JSON.parse('{"status": "error", "message": "' + err.message + '"}');
    };
};

/*
 * alterarRootVar - altera propriedade (cor) de uma variável definida em :root
 *
 * recebe:
 * não recebe nada mas utiliza os campos de id idPagina, elementoSelector, zzSelectColorForm no formulário
 *
 * retorna:
 * nada, mas um toast com o resultado será exibido
 */
const alterarRootVar = async () => {
    const aPagina = document.getElementById('idPagina').value;
    const root_var = document.getElementById('elementoSelector').value;
    const color = document.getElementById('zzSelectColorForm').value;

    let formData = new FormData();
    formData.append('idPagina', aPagina);
    formData.append('root_var', root_var);
    formData.append('color', color);
    let r = await colorAction('addColorCookie', {body: formData, method: 'POST'});
    if (r.status == 'success') {
        const root_css = document.querySelector(':root');
        root_css.style.setProperty("--theme-" + root_var, (root_var == 'picColor'?picColorUrl(color):color));
    } 
    createToast(r.status, r.message);
}

/*
 * restaurarRootVar - restaurar propriedade (cor) de uma variável definida em :root
 *
 * recebe:
 * não recebe nada mas utiliza os campos de id idPagina, elementoSelector no formulário
 *
 * retorna:
 * nada, mas um toast com o resultado será exibido
 */
const restaurarRootVar = async () => {
    const aPagina = document.getElementById('idPagina').value;
    const root_var = document.getElementById('elementoSelector').value;

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
 *      que foram alteradas anteriormente
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
    let r = await colorAction('resetPagina', {body: formData, method: 'POST'});
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
