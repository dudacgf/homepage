function onChangeSelectColorForm() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#zzSelectColorForm');
  const selectCF = document.querySelector('#SelectColorForm');

  preview.style.backgroundColor = selectCF.value;
  zzselect.value = selectCF.value;
}

function onChangeColorPicker() {
  const preview = document.querySelector('#previewColorPicked');
  const zzselect = document.querySelector('#zzSelectColorForm');
  const colorPicker = document.querySelector('#colorPicker');

  preview.style.backgroundColor = colorPicker.jscolor.valueElement.value;
  zzselect.value = colorPicker.jscolor.valueElement.value;
}

