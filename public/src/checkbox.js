document.querySelector('table').addEventListener('click', (e) => {
    if (e.target.tagName === 'INPUT') return;
    const row = e.target.tagName === 'TR' ? e.target : e.target.parentNode;
    const childCheckbox = row.querySelector('input[type="checkbox"]');
    childCheckbox.checked = !childCheckbox.checked;
});