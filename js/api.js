/*****
 * editarElemento - realiza ações sobre um elemento (editar, deslocar para cima ou para baixo, excluir)
 *
 * utilizada quando se clica no nome de um dos elementos de um grupo. chama exibirFormDiv e sai
 *
*****/
const editarElemento = async (apiCall, idElm, idGrp) => {
    event.preventDefault();

    if (apiCall == 'exElm') {
        response = confirm('Confirma exclusão do elemento?');
        if (!response) 
            return;
    }

    const url = window.includePATH + 'api/' + apiCall + '.php';
    const fakeForm = new FormData();
    fakeForm.append('idElm', idElm);
    fakeForm.append('idGrp', idGrp);

    try {
        const response = await fetch(url, {method: 'POST', body: fakeForm});

        const responseData = await response.json();
        r = eval("(" + responseData + ")");

        if (r.status == 'success')
            switch (apiCall) {
                case 'edElm':
                case 'nwLnk':
                case 'nwFrm':
                case 'nwSrp':
                case 'nwImg':
                case 'nwTpt':
                    exibirFormDiv(r.message);
                    break;
                case 'upElm':
                case 'dwElm':
                case 'exElm':
                    reloadElementos();
                    createToast(r.status, r.message);
            }
        else 
            createToast(r.status, r.message);

    } catch (err) {
        createToast('error', err.message);
    };
};

/*****
 * editarCategoria - realiza ações sobre uma categoria (editar, deslocar para cima ou para baixo, excluir)
 *
 * recebe:
 * apiCall - método a ser executado
 * idPagina - id da Página sendo editada
 * idCat - id da categoria afetada 
 *
 * retorna:
 * nada. uma mensagem será exibida via toast e a lista de categorias será atualizada
 *
*****/
const editarCategoria = async (apiCall, idPagina, idCat) => {
    event.preventDefault();

    const url = window.includePATH + 'api/' + apiCall + '.php';
    const fakeForm = new FormData();
    fakeForm.append('id', idPagina);
    fakeForm.append('idCat', idCat);

    try {
        const response = await fetch(url, {method: 'POST', body: fakeForm});

        const responseData = await response.json();
        r = eval("(" + responseData + ")");

        if (r.status == 'success') {
            reloadCategorias(idPagina);
            createToast(r.status, r.message);
        }
        else 
            createToast(r.status, r.message);

    } catch (err) {
        createToast('error', err.message);
    };
}
/*****
 * editarGrupo - realiza ações sobre uma grupo (editar, deslocar para cima ou para baixo, excluir)
 *
 * recebe:
 * apiCall - método a ser executado
 * idCat - id da categoria sendo editada
 * idGrp - id do grupo afetada 
 *
 * retorna:
 * nada. uma mensagem será exibida via toast e a lista de categorias será atualizada
 *
*****/
const editarGrupo = async (apiCall, idCat, idGrp) => {
    event.preventDefault();

    const url = window.includePATH + 'api/' + apiCall + '.php';
    const fakeForm = new FormData();
    fakeForm.append('idCat', idCat);
    fakeForm.append('idGrp', idGrp);

    try {
        const response = await fetch(url, {method: 'POST', body: fakeForm});

        const responseData = await response.json();
        r = eval("(" + responseData + ")");

        if (r.status == 'success') {
            reloadGrupos(idCat);
            createToast(r.status, r.message);
        }
        else 
            createToast(r.status, r.message);

    } catch (err) {
        createToast('error', err.message);
    };
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
 * reloadElementos - recarrega a divisão que exibe os elementos de um grupo
 *
*****/
async function reloadElementos() {
    var r;
    const idGrp = document.getElementById('idGrp').value;
    const url = window.includePATH + 'api/getElms.php?idGrp=' + idGrp;

    try {
        const response = await fetch(url);
        const responseData = await response.json();
        r = eval("(" + responseData + ")");

        if (r.status != 'success') 
            return r;

    } catch (err) {
        return JSON.stringify('{"status": "error", "message": "' + err.message + '"}');
    }

    document.getElementById('elementos_div').innerHTML = r.message;
    return r;
}

/*****
 * reloadCategorias - recarrega a divisão que exibe as categorias de uma página
 *
*****/
async function reloadCategorias(idPagina) {
    var r;
    const url = window.includePATH + 'api/getCtgs.php?id=' + idPagina;

    try {
        const response = await fetch(url);
        const responseData = await response.json();
        r = eval("(" + responseData + ")");

        if (r.status != 'success') 
            return r;

    } catch (err) {
        return JSON.stringify('{"status": "error", "message": "' + err.message + '"}');
    }

    document.getElementById('categorias_div').innerHTML = r.message;
    return r;
}

/*****
 * reloadGrupos - recarrega a divisão que exibe os grupos de uma categoria
 *
*****/
async function reloadGrupos(idCat) {
    var r;
    const url = window.includePATH + 'api/getGrps.php?idCat=' + idCat;

    try {
        const response = await fetch(url);
        const responseData = await response.json();
        r = eval("(" + responseData + ")");

        if (r.status != 'success') 
            return r;

    } catch (err) {
        return JSON.stringify('{"status": "error", "message": "' + err.message + '"}');
    }

    document.getElementById('grupos_div').innerHTML = r.message;
    return r;
}

/*****
 * groupAction - executa as ações dos forms dos elementos.
 *
*****/
const groupAction = async (aForm) => {
    r = await formAction(aForm);
    createToast(r.status, r.message);

    if (r.status == 'success') {
        r = await reloadElementos();
        if (r.status != 'success') 
            createToast(r.status, r.message);
    }
}

/*****
 * formAction - submete um formulário popup e devolve a resposta
 *
 * chamada por grupoAction quando se clica em gravar em um dos formulários de criação/edição
 * de elementos do grupo (link, form, separador, imagem, template)
*****/ 
async function formAction(aform) {
    //event.preventDefault();

    // hide the form
    document.getElementById('form_div').style.display = 'none';

    const form = document.getElementById(aform);
    const formData = new FormData(form);
    const url = window.includePATH + 'api/' + formData.get('mode') + '.php';

    try {
        const response = await fetch(
            url,
            {
                method: 'POST',
                body: formData,
            },
        );
        
        let responseData = await response.json();
        r = eval("(" + responseData + ")");

        ocultarFormDiv();
        return r;

    } catch(err) {
        return JSON.parse('{"status": "error", "message": "' + err.message + '"}');
    };
};

