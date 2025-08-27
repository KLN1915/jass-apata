export function showErrors(errors){
    Object.keys(errors).forEach(key => {
        const [baseKey, index] = key.split('.'); // ej: directions.0 → baseKey = directions, index = 0
        const formType = 'create'; // o 'edit'
    
        if (index !== undefined) {
            // Campos dinámicos
            const inputs = document.querySelectorAll(`.${formType}-${baseKey}`);
            const errorSpans = document.querySelectorAll(`.${formType}-${baseKey}-error`);
    
            if (inputs[index]) inputs[index].classList.add('is-invalid');
            if (errorSpans[index]) errorSpans[index].textContent = errors[key][0];
        } else {
            // Campos simples
            const input = document.querySelector(`#${formType}-${baseKey}`);
            const errorSpan = document.querySelector(`#${formType}-${baseKey}-error`);
    
            if (input) input.classList.add('is-invalid');
            if (errorSpan) errorSpan.textContent = errors[key][0];
        }
    });
}
