const submitBtn = document.getElementById('submit');
const totalDiv = document.getElementById('totalDiv');
const totalPara = document.getElementById('total');

let totalCount = 0;
let selectedBoxes = 0;

document.querySelector('table').addEventListener('click', (e) => {
    if (e.target.tagName === 'INPUT') return;
    const row = e.target.tagName === 'TR' ? e.target : e.target.parentNode;
    const rowAmount = parseInt(row.querySelector(':nth-last-child(2)').innerHTML);
    const childCheckbox = row.querySelector('input[type="checkbox"]');

    childCheckbox.checked = !childCheckbox.checked;

    if (childCheckbox.checked == true) {
        selectedBoxes++;
        totalCount += rowAmount;
    }

    if (selectedBoxes > 0 && childCheckbox.checked !== true) {
        selectedBoxes--;
        totalCount -= rowAmount;
    }

    // enable button if boxes are checked
    if (selectedBoxes > 0) {
        submitBtn.disabled = false;
        totalDiv.hidden = false;
    }

    // disable buttons if no boxes are checked
    if (selectedBoxes === 0) {
        submitBtn.disabled = true;
        totalDiv.hidden = true;
    }

    totalPara.innerHTML = `Totaal geselecteerd: ${totalCount}`;
});