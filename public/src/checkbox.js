const submitBtn = document.getElementById('submit');
let selectedBoxes = 0;

document.querySelector('table').addEventListener('click', (e) => {
    if (e.target.tagName === 'INPUT') return;
    const row = e.target.tagName === 'TR' ? e.target : e.target.parentNode;
    const childCheckbox = row.querySelector('input[type="checkbox"]');
    childCheckbox.checked = !childCheckbox.checked;

    if (childCheckbox.checked == true) {
        selectedBoxes++;
    }
    if (selectedBoxes > 0 && childCheckbox.checked !== true) {
        selectedBoxes--;
    }

    // enable button if boxes are checked
    if (selectedBoxes > 0) {
        submitBtn.disabled = false;
    }

    // disable buttons if no boxes are checked
    if (selectedBoxes === 0) {
        submitBtn.disabled = true;
    }
});
