import { resetModal } from '../helpers/resetModal'
import { cleanInputs } from '../helpers/cleanInputs'
import { showErrors } from '../helpers/showErrors'
import { enableIfChange } from '../helpers/enableIfChanged'

const form = document.querySelector('#editForm')
const url = form.action
let contractType = 'existing'

//Obtener opciones para Servicios
document.addEventListener('DOMContentLoaded', () => {
    const servicesSelect = document.querySelector('#edit-selectService');

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
})

//insertar input de 'deuda desde'
const debtsSinceCheckbox = form.querySelector('#edit-debts_since')
let startDebtsContainer = form.querySelector('#edit-startDebtsContainer')
debtsSinceCheckbox.addEventListener('change', (e) => {
    if(e.target.checked){
        // startDebtsContainer.innerHTML = '<input id="create-start_debt_year" type="number" name="start_debt_date" class="form-control" min="1980" max="2050" placeholder="Ejm: 2000">'
        startDebtsContainer.innerHTML = `
            <div class="input-group mt-0 mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Año</span>
                </div>
                <input type="number" id="edit-debt_since" class="form-control" name="debt_since" placeholder="Ejm: 2000" min="1980" max="2050">
                <span class="text-danger errors" id="edit-debt_since-error"></span>
            </div>
            `
        cleanInputs(startDebtsContainer)
    }else{
        startDebtsContainer.innerHTML = ''
    }
})

//borrar inputs al pulsar boton 'existente' o 'nuevo'
document.getElementById('edit-existing-section-tab').addEventListener('click', () => {
    contractType = 'existing'
    const existingContainer = form.querySelector('#edit-existing-section')
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

document.getElementById('edit-new-section-tab').addEventListener('click', () => {
    contractType = 'new'
    const newContainer = form.querySelector('#edit-new-section')
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

function getClientDirections(associeatedId, ownDirectionId){
    const directionSelect = document.querySelector('#edit-directionSelect')

    if (associeatedId) {
        axios.get(`/clients/${associeatedId}`)
            .then(response => {
                const directions = response.data.directions
                const filteredDirections = directions.filter(direction => direction.contracted !== 1 || direction.id === ownDirectionId)
                directionSelect.innerHTML = '<option value="" selected>Seleccione la dirección</option>'
                filteredDirections.forEach(direction => {
                    const option = document.createElement('option');
                    option.value = direction.id;
                    option.textContent = direction.name;
                    // Si es la dirección actual, márcala como seleccionada
                    if (direction.id === ownDirectionId) {
                        option.selected = true;
                    }
                    directionSelect.appendChild(option);
                });
                
            })
            .catch(error => {
                directionSelect.innerHTML = '<option disabled selected value="" class="text-danger">Sin direcciones disponibles</option>'
                console.error('Error al cargar direcciones:', error)
            });
    }
}

form.addEventListener('submit', (e) => {
    e.preventDefault()
    const form = e.target
    const formData = new FormData(form);
    const contractId = form.dataset.id
    formData.append('contractType', contractType)
    formData.append('_method', 'PATCH')

    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras guardamos los datos.',
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post(`${url}/${contractId}`, formData)
        .then(response => {
            Swal.close();            
            Swal.fire({
                title: 'Éxito',
                text: response.data.message, // Axios ya parsea JSON
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = url
            });
        })
        .catch(error => {
            Swal.close();

            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;

                showErrors(errors, 'edit')
            }
        });
});

//Limpiar inputs al ingresar datos
cleanInputs(form)

$(document).on('click', '.btnEdit', async function (e){
    const contractId = this.getAttribute('data-id')
    const urlEdit = `contracts/${contractId}/edit`

    //agregar id en data-id del form
    form.setAttribute('data-id', contractId)

    axios.get(urlEdit)
        .then(response => {
            //nombre
            document.getElementById('edit-associated_id').value = response.data.contract.direction.client.current_titular.names_lastnames
            //carga de direcciones
            getClientDirections(response.data.contract.direction.client.id, response.data.contract.direction.id)
            //servicio
            document.getElementById('edit-selectService').value = response.data.contract.service.id

            switch(response.data.contract.type){
                case "new":
                    $('#edit-new-section-tab').tab('show')
                    contractType = 'new'
                    //costo instalacion
                    document.getElementById('edit-new_installation_cost').value = response.data.installation.original_amount
                    break
                case "existing":
                    $('#edit-existing-section-tab').tab('show')
                    contractType = 'existing'
                    //fecha de inicio
                    const [year, month, day] = response.data.contract.start_date.split('-')
                    document.getElementById('edit-start_date').value = `${day}-${month}-${year}`
                    //inicio de deuda
                    console.log('year', year)
                    const checkbox = document.getElementById('edit-debts_since')
                    checkbox.checked = false
                    checkbox.dispatchEvent(new Event('change'))
                    if(response.data?.debt){
                        if(year != response.data?.debt.period){
                            checkbox.checked = true
                            checkbox.dispatchEvent(new Event('change'))
                            document.getElementById('edit-debt_since').value = response.data.debt.period               
                        }
                    }
                    //costo de instalacion
                    const originalAmount = response.data.installation.original_amount
                    const amountPayed = response.data.installation.amount_payed
                    document.getElementById('edit-installation_cost').value = originalAmount
                    document.getElementById('edit-amount_payed').value = amountPayed
                    break
            }

            enableIfChange(form)

            $('#editModal').modal('show')
        })
})

//Limpiar modal
$('#editModal').on('hidden.bs.modal', () => {
    //volver a deshabilitar submit
    const submitBtn = form.querySelector('button[type="submit"]')
    submitBtn.disabled = true

    resetModal('editModal')
});