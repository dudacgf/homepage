/**
 * Executa uma chamada API via fetch assíncrono
 *
 * @param {string} metodo - o metodo api a ser executado
 * @param {json} options - dict json com opções para submissão (body, method etc)
 *
 * @returns {json} - o json retornado pela execução do método action
 */
async function chamadaAPI(metodo, options = {}) {
    const url = window.includePATH + 'api/' + metodo + '.php';

    try {
        const response = await fetch(url, options);
        
        if (!response.ok) 
            return JSON.parse(`{"status": "error", "message": "${response.status}"}`);

        let responseData = await response.json();
        return JSON.parse(responseData);

    } catch(err) {
        return JSON.parse(`{"status": "error", "message": "${err.message}. que tal usar base64?"}`);
    };
};

