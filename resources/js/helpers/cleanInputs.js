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

// export function cleanInputs(container) {
//     container.querySelectorAll('input, select, textarea').forEach(input => {
//         // const errorSpan = input.closest('.form-group')?.querySelector('.errors')

//         // Buscar el ancestro más cercano que sea .form-group o .input-group
//         const group = input.closest('.form-group') || input.closest('.input-group');
//         // const errorSpan = group ? group.querySelector('.errors') : null;
//         const errorSpan = group ? group.querySelector(`#${input.id}-error, .errors`) : null;

//         input.addEventListener('input', () => {
//             input.classList.remove('is-invalid');
//             if (errorSpan) errorSpan.textContent = '';
//         });

//         if (input.tagName === 'SELECT') {
//             input.addEventListener('change', () => {
//                 input.classList.remove('is-invalid');
//                 if (errorSpan) errorSpan.textContent = '';
//             });
//         }
//     });
// }

export function cleanInputs(container) {
    container.querySelectorAll('input, select, textarea').forEach(input => {
        // 1. Intentamos encontrar el span de error con el ID específico
        //    (e.g., #create-period_day-error)
        const specificIdError = document.getElementById(`${input.id}-error`);
        
        // 2. Si no existe ese ID, buscamos el error genérico dentro del grupo.
        const group = input.closest('.form-group') || input.closest('.input-group');
        const genericError = group ? group.querySelector('.errors') : null;
        
        // Asignamos el error específico si existe, de lo contrario, el genérico
        const errorSpan = specificIdError || genericError;

        // ... El resto del código de los eventos permanece igual
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

        // 🔹 Caso especial: Datetimepicker
        let datepickerDiv = null;
        const nextElement = input.nextElementSibling;

        // Comprueba si el siguiente elemento existe y coincide con el selector de datetimepicker
        if (nextElement && nextElement.matches('div[data-toggle="datetimepicker"]')) {
            datepickerDiv = nextElement;
            datepickerDiv.addEventListener('click', () => {
                input.classList.remove('is-invalid');
                if (errorSpan) errorSpan.textContent = '';
            })
        }
    })
}