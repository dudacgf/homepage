<div class=content style="display: flex; flex: 0 1 auto; align-items: center; container-type: inline-size;">
    <input type=file id=uploadFile_{$fileField} class=inputFile>
    <label for=uploadFile_{$fileField} class="botao fa-upload-file" id=labelFile_{$fileField}>
    <i class="fa-solid fa-file-import"></i>
        Escolha um arquivo
    </label>
    <button id=botaoSubmit_{$fileField}>Carregar Arquivo</button>
    <label id=resultadoProcesso_{$fileField} class="resultadoProcesso"></label>
</div>
<script>
document.getElementById('uploadFile_{$fileField}').addEventListener('change', function(e) {
    updateLabel('{$fileField}');
}, false);

document.getElementById('botaoSubmit_{$fileField}').addEventListener('click', function(e) {
    e.preventDefault();
    carregarArquivo('{$fileField}');
}, true);
</script>
