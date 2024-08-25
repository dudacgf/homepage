function onChangeselectColor() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#selectedColor');
  const selectCF = document.querySelector('#selectColor');

  preview.style.backgroundColor = selectCF.value;
  preview.style.color = HSP(selectCF.value);
  preview.innerHTML =selectCF.value;
  zzselect.value = selectCF.value;
}

function onChangeColorPicker() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#selectedColor');
  const colorPicker = document.querySelector('#colorPicker');

  preview.style.backgroundColor = colorPicker.jscolor.valueElement.value;
  preview.style.color = HSP(colorPicker.jscolor.valueElement.value);
  preview.innerHTML = '<div class="textMiddle">Custom Color<br/>' + colorPicker.jscolor.valueElement.value + '</div>';
  zzselect.value = colorPicker.jscolor.valueElement.value;
}
function toggleColorMode(idBotaoOpcaoModo) {
    if (idBotaoOpcaoModo.id == 'rainbowButton') {
       document.getElementById('boxCores').style.display = 'none';
       document.querySelector('#colorPicker').jscolor.show(); 
    } else if (idBotaoOpcaoModo.id == 'pantone') {
       document.getElementById('boxCores').style.display = 'block';
       document.querySelector('#colorPicker').jscolor.hide(); 
    }
}
function boxCorClick(nomeCor, valorCor, hspCor) {
    const bp = document.getElementById('previewColorPicked');
    const sc = document.getElementById('selectedColor');
    
    sc.value = valorCor;
    bp.style.backgroundColor = valorCor;
    bp.style.color = hspCor;
    bp.innerHTML = '<div class="textMiddle">' + nomeCor + '<br/>' + valorCor.toUpperCase() + '</div>';
}

