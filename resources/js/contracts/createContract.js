import { resetModal } from '../helpers/resetModal'
import { cleanInputs } from '../helpers/cleanInputs'
import { showErrors } from '../helpers/showErrors'

const form = document.querySelector('#createForm')
const url = form.action
let contractType = 'existing'

//Obtener opciones para Servicios
document.addEventListener('DOMContentLoaded', () => {
    const servicesSelect = document.querySelector('#create-selectService');

    if (servicesSelect) {
        axios.get('/getServices')
            .then(response => {
                const services = response.data;
                servicesSelect.innerHTML = '<option value="" selected>Seleccionar servicio</option>';
                services.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service.id;
                    option.textContent = `${service.name} - ${service.charge_period} - ${service.price}`;
                    servicesSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar servicios:', error));
    }
});

//insertar input de 'deuda desde'
const debtsSinceCheckbox = form.querySelector('#create-debts_since')
let startDebtsContainer = form.querySelector('#startDebtsContainer')
debtsSinceCheckbox.addEventListener('change', (e) => {
    if(e.target.checked){
        // startDebtsContainer.innerHTML = '<input id="create-start_debt_year" type="number" name="start_debt_date" class="form-control" min="1980" max="2050" placeholder="Ejm: 2000">'
        startDebtsContainer.innerHTML = `
            <div class="input-group mt-0 mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Año</span>
                </div>
                <input type="number" id="create-debt_since" class="form-control" name="debt_since" placeholder="Ejm: 2000" min="1980" max="2050">
                <span class="text-danger errors" id="create-debt_since-error"></span>
            </div>
            `
        cleanInputs(startDebtsContainer)
    }else{
        startDebtsContainer.innerHTML = ''
    }
})

//borrar inputs al pulsar boton 'existente' o 'nuevo'
document.getElementById('create-existing-section-tab').addEventListener('click', () => {
    contractType = 'existing'
    const existingContainer = form.querySelector('#create-existing-section')
    const inputs = existingContainer.querySelectorAll('input')
    debtsSinceCheckbox.checked = false
    startDebtsContainer.innerHTML = ''
    inputs.forEach(input => {
        input.value = ''
        input.classList.remove('is-invalid')
        const inputId = input.id
        const errorSpan = existingContainer.querySelector(`#${inputId}-error`)
        if(errorSpan){
            errorSpan.textContent = ''
        }
    })
})

document.getElementById('create-new-section-tab').addEventListener('click', () => {
    contractType = 'new'
    const newContainer = form.querySelector('#create-new-section')
    const inputs = newContainer.querySelectorAll('input')
    inputs.forEach(input => {
        input.value = ''
        input.classList.remove('is-invalid')
        const inputId = input.id
        const span = newContainer.querySelector(`#${inputId}-error`)
        if(span){
            span.innerText = ''
        }
    })
})

//Enviar al backend
form.addEventListener('submit', (e) => {
    e.preventDefault()
    const formData = new FormData(form)
    formData.append('contractType', contractType)

    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras guardamos los datos.',
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    })

    axios.post(url, formData)
        .then(response => {
            Swal.close()

            Swal.fire({
                title: 'Éxito',
                text: response.data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = url
            })
        })
        .catch(error => {
            Swal.close()

            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
                icon: 'error',
                confirmButtonText: 'OK'
            })

            if(error.response && error.response.status === 422){
                const errors = error.response.data.errors

                showErrors(errors, 'create')
            }
        })
})

//Limpiar inputs al ingresar datos
cleanInputs(form)

//Limpiar modal
$('#createModal').on('hidden.bs.modal', () => {
    startDebtsContainer.innerHTML = ''
    resetModal('Modal')

    const $associatedSelect = $('#create-associated_id');
    $associatedSelect.val(null).trigger('change');
})