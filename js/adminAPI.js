/**
 * realiza ações sobre categorias durante a edição de uma pagina 
 * (editar, deslocar para cima ou para baixo, excluir)
 *
 * @param {string} metodo - método a ser executado
 * @param {number} idPagina - id da Página sendo editada
 * @param {number} idCat - id da categoria afetada 
 *
 * @returns {void} nada. uma mensagem será exibida via toast e a lista de categorias será atualizada
 *
 */
const editarCategoria = async (metodo, idPagina, idCat) => {
    event.preventDefault();

    const fakeForm = new FormData();
    fakeForm.append('id', idPagina);
    fakeForm.append('idCat', idCat);

    let r = await chamadaAPI(metodo, {method: 'POST', body: fakeForm});

    if (r.status == 'success')
        reloadCategorias(idPagina);

    createToast(r.status, r.message);
}

/**
 * realiza ações sobre grupos durante a edição  de uma categoria 
 * (deslocar para cima ou para baixo, excluir, incluir)
 *
 * @param {string} metodo - método a ser executado
 * @param {number} idCat - id da categoria afetada 
 * @param {number} idGrp - id do grupo afetada 
 *
 * @returns {void} nada. uma mensagem será exibida via toast e a lista de grupos será atualizada
 *
 */
const editarGrupo = async (metodo, idCat, idGrp) => {
    event.preventDefault();

    const fakeForm = new FormData();
    fakeForm.append('idCat', idCat);
    fakeForm.append('idGrp', idGrp);

    let r = await chamadaAPI(metodo, {method: 'POST', body: fakeForm});

    if (r.status == 'success')
        reloadGrupos(idCat);

    createToast(r.status, r.message);
}

/**
 * realiza ações sobre elementos durante a edição de um grupo
 * (editar, deslocar para cima ou para baixo, excluir)
 *
 * @param {string} metodo - método a ser executado
 * @param {number} idElm - id do elemento afetadao
 * @param {number} idGrp - id do grupo afetada 
 *
 * @returns {void} nada. uma mensagem será exibida via toast e a lista de elementos será atualizada
 *
 * 
 *
 */
const editarElemento = async (metodo, idElm, idGrp) => {
    event.preventDefault();

    if (metodo == 'excluirElemento') {
        response = confirm('Confirma exclusão do elemento?');
        if (!response) 
            return;
    }

    const fakeForm = new FormData();
    fakeForm.append('idElm', idElm);
    fakeForm.append('idGrp', idGrp);

    let r = await chamadaAPI(metodo, {method: 'POST', body: fakeForm});

    if (r.status == 'success')
        switch (metodo) {
            case 'editarElemento':
            case 'novoLink':
            case 'novoForm':
            case 'novoSeparador':
            case 'novaImagem':
            case 'novoTemplate':
                exibirFormDiv(r.message);
                break;
            case 'redefinirPosicoesElementos':
            case 'ascenderElemento':
            case 'descenderElemento':
            case 'excluirElemento':
                reloadElementos();
                createToast(r.status, r.message);
        }
    else
        createToast(r.status, r.message);
};

/*****
 * gravarElemento - executa as ações dos forms dos elementos.
 *
 * chamada quando se clica em gravar em um dos formulários de criação/edição
 * de elementos do grupo (link, form, separador, imagem, template)
*****/
const gravarElemento  = async (aForm) => {
    // hide the form
    document.getElementById('form_div').style.display = 'none';

    const form = document.getElementById(aForm);
    const formData = new FormData(form);
    const metodo = formData.get('mode');

    let r = await chamadaAPI(metodo, {method: 'POST', body: formData});
    createToast(r.status, r.message);
    
    if (r.status == 'success') {
        r= await reloadElementos();
        if (r.status != 'success')
            createToast(r.status, r.message);
    }

    ocultarFormDiv();
}

/**
 * convert a base64 encoded string to utf8 unicode string
 *
 * @param {string} str - the base64 encoded string
 *
 * @returns {string} str decoded to utf8 unicode
 *
 * from: https://stackoverflow.com/questions/30106476/using-javascripts-atob-to-decode-base64-doesnt-properly-decode-utf-8-strings#30106551
 */
function btoutf8(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function(c) {
                 return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}

/*****
 * exibirFormDiv - chamada por editarElemento. 
 *
 * exibe o form carregado para criação/edição de um elemento (link, form, separador, imagem, template)
 *
*****/
function exibirFormDiv(formHTML) {
    document.getElementById('invisivel_div').style.display = 'block';
    document.getElementById('invisivel_div').style.visibility = 'visible';
    document.getElementById('form_div').innerHTML = btoutf8(formHTML);
    document.getElementById('form_div').style.display = 'block';
};

/*****
 * ocultarFormDiv - chamada ao final de uma edição/criação de elementos
 *
 * oculta o form carregado em exibirFormDiv
*****/
function ocultarFormDiv() {
    document.getElementById('invisivel_div').style.display = 'none';
    document.getElementById('invisivel_div').style.visibility = 'hidden';
    document.getElementById('form_div').innerHTML = '';
    document.getElementById('form_div').style.display = 'none';
}

/*****
 * reloadCategorias - recarrega a divisão que exibe as categorias de uma página
 *
*****/
async function reloadCategorias(idPagina) {
    const metodo = 'obterCategorias';
    const fakeForm = new FormData();

    fakeForm.append('idPagina', idPagina);

    let r = await chamadaAPI(metodo, {method: 'POST', body: fakeForm});

    if (r.status != 'success') 
        return r;

    const catDiv = document.querySelector('#categorias_div');
    catDiv.classList.toggle('fadeinout');
    catDiv.innerHTML = btoutf8(r.message);
    setTimeout( () => { catDiv.classList.toggle('fadeinout'); }, 2000);

    return r;
}

/*****
 * reloadGrupos - recarrega a divisão que exibe os grupos de uma categoria
 *
*****/
async function reloadGrupos(idCat) {
    const metodo = 'obterGrupos';
    const fakeForm = new FormData();

    fakeForm.append('idCat', idCat);

    let r = await chamadaAPI(metodo, {method: 'POST', body: fakeForm});

    if (r.status != 'success') 
        return r;

    const grpDiv = document.querySelector('#grupos_div');
    grpDiv.classList.toggle('fadeinout');
    grpDiv.innerHTML = btoutf8(r.message);
    setTimeout( () => { grpDiv.classList.toggle('fadeinout'); }, 2000);

    return r;
}

/*****
 * reloadElementos - recarrega a divisão que exibe os elementos de um grupo
 *
*****/
async function reloadElementos() {
    const metodo = 'obterElementos';
    const idGrp = document.getElementById('idGrp').value;
    const fakeForm = new FormData();

    fakeForm.append('idGrp', idGrp);

    let r = await chamadaAPI(metodo, {method: 'POST', body: fakeForm});

    if (r.status != 'success') 
        return r;

    const elDiv = document.querySelector('#elementos_div');
    elDiv.classList.toggle('fadeinout');
    elDiv.innerHTML = btoutf8(r.message);
    setTimeout( () => { elDiv.classList.toggle('fadeinout'); }, 2000);

    return r;
}

/******** CARGA DE ARQUIVOS ***********/

/*
 * Diminui fontSize de um elemento até que o texto caiba ou até que se
 * atinja um tamanho mínimo
 *
 * @param {object} el - elemento a ser manipulado
 * @param {string} tamanhoInicial - fontSize inicial do elemento, em qualquer notação css válida
 * @param {int} tamanhoMinimo - fontSize mínimo para o elemento (inteiro pois getComputedStyle devolve em px)
 *
 * não retorna nada mas o elemento terá sua fonte diminuída até caber ou até atingir tamanhoMinimo
 */
function makeItFit(el, tamanhoInicial, tamanhoMinimo) {
    el.style.fontSize = tamanhoInicial;
    while ((el.scrollHeight > el.clientHeight || el.scrollWidth > el.clientWidth) &&
           (parseInt(getComputedStyle(el).fontSize) >= tamanhoMinimo))
        el.style.fontSize = parseFloat(getComputedStyle(el).fontSize) * 0.9 + "px";
}

/*
 * Atualiza o elemento 'Label form=' de um 'input type=file' com o nome do arquivo selecionado
 *
 * @param {string} fileField - sufixo do campo 'input type=file'
 *
 */
function updateLabel(fileField) {
    const label = document.getElementById('labelFile_' + fileField);
    const uploadField = document.getElementById('uploadFile_' + fileField);

    if (uploadField.files.length > 0) {
        label.innerHTML= '<i class="fa-solid fa-file-import"></i>' + uploadField.files[0].name;
        makeItFit(label, '2cqw', 12);
    }
}

/*
 * envia o arquivo para processamento
 *
 * @param {string} fileField - sufixo do campo 'input type=file' que armazena o arquivo selecionado
 *
 */
async function carregarArquivo(fileField) {
    const files = document.getElementById('uploadFile_' + fileField).files;
    if (files.length == 0) {
        createToast('info', 'Selecione um arquivo a ser carregado');
        return false;
    }

    const file = files[0];
    const formData = new FormData();
    formData.append('inputFile[]', file, file.name);

    let r = await chamadaAPI('carregar' + fileField, {
           method: 'POST', body: formData,
        });

    if (r.status == 'success' && r.hasOwnProperty('resultadoProcesso')) {
        var functionFormatarResultado = window['formatarResultado_' + fileField];
        const resultadoProcesso = document.getElementById('resultadoProcesso_' + fileField);
        resultadoProcesso.innerHTML = functionFormatarResultado(r.resultadoProcesso);
        makeItFit(resultadoProcesso, '1cqw', 10);
    }

    createToast(r.status, r.message);
}


