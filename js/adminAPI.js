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

/*****
 * exibirFormDiv - chamada por editarElemento. 
 *
 * exibe o form carregado para criação/edição de um elemento (link, form, separador, imagem, template)
 *
*****/
function exibirFormDiv(formHTML) {
    document.getElementById('invisivel_div').style.display = 'block';
    document.getElementById('invisivel_div').style.visibility = 'visible';
    document.getElementById('form_div').innerHTML = formHTML;
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
    document.getElementById('categorias_div').innerHTML = r.message;
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
    grpDiv.innerHTML = r.message;
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
    elDiv.innerHTML = r.message;
    setTimeout( () => { elDiv.classList.toggle('fadeinout'); }, 2000);

    return r;
}