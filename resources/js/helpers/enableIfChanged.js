export function enableIfChange(form){
    const submitBtn = form.querySelector('button[type="submit"]')

    const enableBtn = () => {
        submitBtn.disabled = false;
        submitBtn.classList.remove("disabled");
    };

    //seleccionar y asignar evento a botones + y -
    form.querySelectorAll('button').forEach((btn) => {
        if (btn.querySelector('i.fas.fa-minus') || btn.querySelector('i.fas.fa-plus')) {
            console.log('evento agregado al boton + o -')
            btn.addEventListener('click', enableBtn)
        }
    });

    // Escuchar cualquier cambio en inputs, selects y textareas
    form.addEventListener("input", enableBtn)
    form.addEventListener("change", enableBtn)

    // Detectar cambios en los Select2
    $(form).find("#edit-occupation").each(function () {
        $(this).on("select2:select select2:unselect select2:clear", enableBtn);
    });
}