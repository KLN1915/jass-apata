import { resetModal } from '../helpers/resetModal'
// import { cleanInputs } from '../helpers/cleanInputs'

const createModal = document.getElementById('createModal')
const form = document.getElementById('createForm')

let debtIds = []

let subTotalDebts = 0.00
let subTotalAddDebts = 0.00
let subTotalServices = 0.00

const subtotal1 = document.getElementById("subtotal1")
const subtotal2 = document.getElementById("subtotal2")
const subtotal3 = document.getElementById("subtotal3")

function rebootSubtotals(){
    subTotalDebts = 0.00
    subTotalAddDebts = 0.00
    subTotalServices = 0.00

    subtotal1.innerHTML = subTotalDebts.toFixed(2)
    subtotal2.innerHTML = subTotalAddDebts.toFixed(2)
    subtotal3.innerHTML = subTotalServices.toFixed(2)
}

function recalculateTotal(){
    const total = document.getElementById("total")
    let result = subTotalDebts + subTotalAddDebts + subTotalServices

    total.innerHTML = result.toFixed(2)
}

// Opciones de Select2
$('#createModal').on('shown.bs.modal', function() {
    $('#create-contract_id').select2({
        placeholder: 'Escribir código, titular o dirección',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#createModal'), // Esto es clave
        ajax: {
            url: '/getContracts', // Ruta en Laravel
            dataType: 'json',
            delay: 250, // Retraso en la búsqueda
            data: function (params) {
                return {
                    search: params.term // Enviar el término de búsqueda al backend
                };
            },
            processResults: function (data) {
                return {
                    results: data // Devuelve los resultados esperados por Select2
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        language: {
            inputTooShort: function () {
                return 'Escribe al menos 2 caracteres';
            },
            noResults: function () {
                return 'No se encontraron resultados';
            },
            searching: function () {
                return 'Buscando...';
            },
            errorLoading: function () {
                return 'Error al cargar los resultados';
            }
        }
    });

    // Workaround: elimina los eventos que bloquean el scroll
    $('.selectOccupation').on('select2:open', function(e) {
        $(e.target).parents().off('scroll.select2');
        $(window).off('scroll.select2');
    });

    // --- LÓGICA DE LIMPIEZA DE ERRORES DEL SELECT2 ---
    const select2Element = $('#create-contract_id');
    // 1. Obtener el span de error específico (usando el ID del select)
    const errorSpan = document.getElementById(`${select2Element.attr('id')}-error`);
    const $errorSpan = $(errorSpan);

    // 2. Evento: Cuando el usuario selecciona una opción
    select2Element.on('select2:select', function() {
        select2Element.removeClass('is-invalid');
        if ($errorSpan.length) $errorSpan.text('');
    });
});

const addServicesContent = document.getElementById('dynamicServices')
document.addEventListener('DOMContentLoaded', () => {

    axios.get('getAdditionalServices')
        .then(response => {
            addServicesContent.innerHTML = ''

            const addServices = response.data

            if(addServices.length){
                addServices.forEach(service => {
                    const div = document.createElement('div')
                    div.classList.add('input-group', 'mb-3')
                    div.innerHTML = `
                        <div class="input-group-prepend">
                            <span class="input-group-text">${service.name}</span>
                            <span class="input-group-text">S/.</span>
                        </div>
                        <input name="additionalService[${service.id}]" id="input-additionalService-${service.id}" type="text" data-type="addService" class="form-control" placeholder="00.00">
                        <span class="text-danger errors" id="error-additionalService-${service.id}"></span>
                    `
                    addServicesContent.appendChild(div)
                })
            }

            const inputs = addServicesContent.querySelectorAll('input')
            inputs.forEach(input => addInputEvent(input))
        })
})

//agregar deudas del contrato
$('#create-contract_id').on('select2:select', function (e) {
    let contractId = e.params.data.id; // Select2 guarda el id aquí
    const debtsContent = document.querySelector('#debtsTable tbody')
    const addDebtsContent = document.querySelector('#addDebtsTable tbody')
    const dynamicInputs = document.querySelector('#dynamicInputs')
    rebootSubtotals()
    recalculateTotal()

    if(contractId){
        axios.get(`/debts/${contractId}`)
            .then(response => {
                debtsContent.innerHTML = ''
                const debts = response.data.debts.debtsData

                if(debts.length != 0){
                    debts.forEach((debt, index) => {
                        const row = document.createElement('tr')
                        row.innerHTML = `
                            <td>${index+1}</td>
                            <td>${debt.period}</td>
                            <td>${debt.amount}</td>
                            <td>5.00</td>
                            <td>${debt.subTotal}</td>
                            <td style="text-align: center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button data-type="debt" data-id="${debt.id}" data-total="${debt.subTotal}" type="button" class="btn btn-success btn-sm btnPayDebt">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>                                 
                                </div>
                            </td>
                        `
                        debtsContent.appendChild(row)
                    })
                }else{
                    debtsContent.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center">Sin deudas</td>
                        </tr>
                    `
                }

                addDebtsContent.innerHTML = ''
                dynamicInputs.innerHTML = ''
                const addDebts = response.data.additional_debts.addDebtsData

                if(addDebts.length != 0){
                    addDebts.forEach((addDebt, index) => {
                    const row = document.createElement('tr')
                    row.innerHTML = `
                        <td>${index+1}</td>
                        <td>${addDebt.concept}</td>
                        <td>${addDebt.original_amount}</td>
                        <td>${addDebt.amount_payed}</td>
                        <td>${addDebt.subTotal}</td>
                        <td style="text-align: center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button data-type="addDebt" data-concept="${addDebt.concept}" data-id="${addDebt.id}" data-total="${addDebt.subTotal}" type="button" class="btn btn-success btn-sm btnPayAddDebt">
                                    <i class="fas fa-plus-circle"></i>
                                </button>                                          
                            </div>
                        </td>
                    `
                    addDebtsContent.appendChild(row)
                })
                }else{
                    addDebtsContent.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center">Sin deudas adicionales</td>
                        </tr>
                    `
                }                        
            })
            .catch(error => {
                debtsContent.innerHTML = 'Sin deudas disponibles'
                console.error('Error al cargar deudas:', error)
            });

        const inputs = addServicesContent.querySelectorAll('input')
        inputs.forEach(input => input.value = '')
        debtIds = []
    }
});

const debtsTableBody = document.querySelector('#debtsTable tbody');
const addDebtsTableBody = document.querySelector('#addDebtsTable tbody');

function evaluateAction(btn){
    if (btn.classList.contains('btn-success')) {
        return "add"
    } else {
        return "subtract"
    }
}

function toggleButtonState(action, btn, row) {
    const icon = btn.querySelector('i');
    if (action == "add") {
        //cambiar estilo de fila
        row.classList.add('table-primary')
        //cambiar boton
        btn.classList.remove('btn-success');
        btn.classList.add('btn-danger');
        if (icon) { icon.classList.remove('fa-plus-circle'); icon.classList.add('fa-minus-circle'); }
    } else if(action == "subtract"){
        //cambiar estilo de fila
        row.classList.remove('table-primary')
        //cambiar boton
        btn.classList.remove('btn-danger');
        btn.classList.add('btn-success');
        if (icon) { icon.classList.remove('fa-minus-circle'); icon.classList.add('fa-plus-circle'); }
    }
}

function addOrSubtract(action, type, amount){
    if(action === "add"){
        if(type == "debt"){
            return subTotalDebts += amount
        }else{
            return subTotalAddDebts += amount
        }
    } else if(action === "subtract"){
        if(type == "debt"){
            return subTotalDebts -= amount
        }else{
            return subTotalAddDebts -= amount
        }
    }
}

function updateSubtotal(action, btn){
    let type = btn.dataset.type
    let amount = parseFloat(btn.dataset.total)

    if(type == "debt"){
        subtotal1.innerHTML = addOrSubtract(action, type, amount).toFixed(2)
    }else if(type == "addDebt"){
        const inputContainer = document.getElementById(`addInput-${btn.dataset.id}`)
        const input = inputContainer.querySelector('input')
        amount = parseFloat(input.value)
        subtotal2.innerHTML = addOrSubtract(action, type, amount).toFixed(2)
    }
}

function addOrRemoveId(action, btn){
    let id = btn.dataset.id

    if(action == 'add'){
        if(!debtIds.includes(id)){
            debtIds.push(id)
        }
    }else{
        let index = debtIds.indexOf(id)
        if(index !== -1){
            debtIds.splice(index, 1)
        }
    }

    console.log(debtIds)
}

const mainValidationSpan = document.getElementById('create-main_validation-error')

// Delegación de eventos para botones de deudas normales
if (debtsTableBody) {
    debtsTableBody.addEventListener('click', function(e) {
        //limpiar error de main-validation
        mainValidationSpan.innerHTML = ''
        //funcionalidad del boton
        const btn = e.target.closest('.btnPayDebt')
        const row = btn.parentElement.parentElement.parentElement
        
        if (!btn) return

        let action = evaluateAction(btn)
        toggleButtonState(action, btn, row)
        addOrRemoveId(action, btn)
        updateSubtotal(action, btn)
        recalculateTotal()
    });
}

function toggleInput(action, btn){
    const container = document.getElementById('dynamicInputs')
    const newInput = document.createElement('div')
    newInput.classList.add('input-group', 'mb-3')
    newInput.id = `addInput-${btn.dataset.id}`
    newInput.innerHTML = `
        <div class="input-group-prepend">
            <span class="input-group-text">Paga de ${btn.dataset.concept}: </span>
            <span class="input-group-text">S/.</span>
        </div>
        <input
            name="additionalDebt[${btn.dataset.id}]"
            id="input-additionalDebt-${btn.dataset.id}"
            type="text"
            class="form-control"
            placeholder="00.00"
            value="${btn.dataset.total}"
            data-type="${btn.dataset.type}"
        >
        <span class="text-danger errors" id="error-additionalDebt-${btn.dataset.id}"></span>
    `

    if(action == 'add'){
        container.appendChild(newInput)
        const input = container.querySelector(`#addInput-${btn.dataset.id}`)
        addInputEvent(input.querySelector('input'))
    }else if(action == 'subtract'){
        const input = container.querySelector(`#addInput-${btn.dataset.id}`)
        if(input){
            input.remove()
        }
    }
}

function addInputEvent(input){
    input.addEventListener('input', () => {
        //limpiar error de main-validation
        mainValidationSpan.innerHTML = ''
        
        //quitar error
        let cleanId = input.id.replace('input-', '')
        let spanError = document.querySelector(`#error-${cleanId}`)

        spanError.innerHTML = ''
        input.classList.remove('is-invalid')

        //recalcular subtotal y total
        let type = input.dataset.type
        
        if(type == 'addDebt'){
            const container = document.getElementById('dynamicInputs')
            subTotalAddDebts = recalculateSubtotal(container)
            subtotal2.innerHTML = subTotalAddDebts.toFixed(2)
        }else if(type == 'addService'){
            const container = document.getElementById('dynamicServices')
            subTotalServices = recalculateSubtotal(container)
            subtotal3.innerHTML = subTotalServices.toFixed(2)
        }

        recalculateTotal()
    })
}

function recalculateSubtotal(container){
    let total = 0.00

    const inputs = container.querySelectorAll('input')
    inputs.forEach(input => {
        const value = parseFloat(input.value)
        if (!isNaN(value)) {
            total += value
        }
    })

    return total
}

// Delegación de eventos para botones de deudas adicionales
if (addDebtsTableBody) {
    addDebtsTableBody.addEventListener('click', function(e) {
        //limpiar error de main-validation
        mainValidationSpan.innerHTML = ''
        //funcionalidad del boton
        const btn = e.target.closest('.btnPayAddDebt')
        const row = btn.parentElement.parentElement.parentElement

        if (!btn) return

        let action = evaluateAction(btn)
        toggleButtonState(action, btn, row)
        if(action == 'add'){
            toggleInput(action, btn)
            updateSubtotal(action, btn)

            recalculateTotal()
        }else if(action == "subtract"){
            updateSubtotal(action, btn)
            toggleInput(action, btn)

            recalculateTotal()
        }        
    });
}

//Limpiar modal
$('#createModal').on('hidden.bs.modal', () => {
    // startDebtsContainer.innerHTML = ''
    const dynamicInputsContainer = createModal.querySelector('#dynamicInputs')
    dynamicInputsContainer.innerHTML = ''

    debtsTableBody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center">Seleccione un contrato</td>
        </tr>
    `

    addDebtsTableBody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center">Seleccione un contrato</td>
        </tr>
    `

    resetModal('createModal')
    rebootSubtotals()
    recalculateTotal()

    const $contractSelect = $('#create-contract_id');
    $contractSelect.val(null).trigger('change');

    debtIds = []
})

function showErrors(errors, formType){
    Object.keys(errors).forEach(key => {
        const [baseKey, index] = key.split('.') // ej: directions.0 → baseKey = directions, index = 0

        if (index !== undefined) {
            // Campos dinámicos
            const input = document.querySelector(`#input-${baseKey}-${index}`)
            const errorSpan = document.querySelector(`#error-${baseKey}-${index}`)
    
            if (input) input.classList.add('is-invalid')
            if (errorSpan) errorSpan.textContent = errors[key][0]
        } else {
            // Campos simples
            const input = document.querySelector(`#${formType}-${baseKey}`)
            const errorSpan = document.querySelector(`#${formType}-${baseKey}-error`)
    
            if (input) input.classList.add('is-invalid')
            if (errorSpan) errorSpan.textContent = errors[key][0]
        }
    });
}

//Envio de datos
const url = form.action
form.addEventListener('submit', (e) => {
    e.preventDefault()
    const formData = new FormData(form)

    //debtIds
    debtIds.forEach(id => {
        formData.append('debtIds[]', id)
    })

    //total
    formData.append('total',
        subTotalDebts + subTotalAddDebts + subTotalServices
    )

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