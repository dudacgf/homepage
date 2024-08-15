function onChangeselectColor() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#selectedColor');
  const selectCF = document.querySelector('#selectColor');

  preview.style.backgroundColor = selectCF.value;
  zzselect.value = selectCF.value;
}

function onChangeColorPicker() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#selectedColor');
  const colorPicker = document.querySelector('#colorPicker');

  preview.style.backgroundColor = colorPicker.jscolor.valueElement.value;
  zzselect.value = colorPicker.jscolor.valueElement.value;
}

