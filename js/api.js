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
 * exibirFormDiv - chamada por editarElemento. 
 *
 * exibe o form carregado para criação/edição de um elemento (link, form, separador, imagem, template)
 *
*****/
function exibirFormDiv(formHTML) {
    document.getElementById('form_div').innerHTML = formHTML;
    document.getElementById('form_div').style.display = 'block';
};

/*****
 * ocultarFormDiv - chamada ao final de uma edição/criação de elementos
 *
 * oculta o form carregado em exibirFormDiv
*****/
function ocultarFormDiv() {
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
    const url = window.includePATH + 'api/getGrp.php?idGrp=' + idGrp;

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

