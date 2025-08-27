// export function cleanInputs(formId) {
//     const formEl = document.getElementById(formId);
//     if (!formEl) return;

//     const formGroups = formEl.querySelectorAll('.form-group');

//     formGroups.forEach(formGroup => {
//         const input = formGroup.querySelector('input, select, textarea');
//         const errorSpan = formGroup.querySelector('span.text-danger.errors');

//         if (input) {
//             input.addEventListener('input', () => {
//                 input.classList.remove('is-invalid');
//                 if (errorSpan) errorSpan.innerText = '';
//             });
//         }
//     });
// }

export function cleanInputs(container) {
    container.querySelectorAll('input, select, textarea').forEach(input => {
        const errorSpan = input.closest('.form-group')?.querySelector('.errors');
        
        input.addEventListener('input', () => {
            input.classList.remove('is-invalid');
            if (errorSpan) errorSpan.textContent = '';
        });

        if (input.tagName === 'SELECT') {
            input.addEventListener('change', () => {
                input.classList.remove('is-invalid');
                if (errorSpan) errorSpan.textContent = '';
            });
        }
    });
}
