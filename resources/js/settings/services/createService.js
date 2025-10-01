import { resetModal } from '../../helpers/resetModal'
import { cleanInputs } from '../../helpers/cleanInputs'
import { showErrors } from '../../helpers/showErrors'

const form = document.getElementById('createForm')
const url = form.action

//forzar nombre de servicio en mayusculas
const nameInput = form.querySelector('#create-name')
nameInput.addEventListener('input', (e) => {
    e.target.value = e.target.value.toUpperCase()
})

const periodSelect = form.querySelector('#create-chargePeriod')
const checkboxContainer = form.querySelector('#create-checkboxContainer')
const finesContainer = form.querySelector('#create-finesContainer')

periodSelect.addEventListener('change', (e) => {
    if(e.target.value !== ""){
        const selectValue = e.target.value
        switch(selectValue){
            case "1":
                finesContainer.innerHTML = ''
                checkboxContainer.innerHTML = `
                    <input value="1" name="lateFee" type="checkbox" class="form-check-input" id="create-fineCheckbox" name="lateFee">
                    <label class="form-check-label" for="create-fineCheckbox">Cobrar multa</label>
                `
                // insertMonthlyInputs()
                break
            case "12":
                finesContainer.innerHTML = ''
                checkboxContainer.innerHTML = `
                    <input value="1" name="lateFee" type="checkbox" class="form-check-input" id="create-fineCheckbox">
                    <label class="form-check-label" for="create-fineCheckbox">Cobrar multa</label>
                `
                insertAnnualInputs()
                break            
        }
    }else{
        checkboxContainer.innerHTML = ''
        finesContainer.innerHTML = ''
    }
})

function insertAnnualInputs(){
    const checkbox = form.querySelector('input[type="checkbox"]')
    checkbox.addEventListener('change', (e) => {
        if(e.target.checked){
            finesContainer.innerHTML = `
                <div class="col-md-8">
                    <label for="">Fecha de vencimiento</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Cada</span>
                        </div>
                        <input id="create-period_day" name="period_day" type="number" class="form-control col-md-4" min="1" max="31" placeholder="dd">
                        <select id="create-period_month" name="period_month" class="form-control">
                            <option value="" selected="">Seleccionar mes</option>
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Setiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                        <span id="create-period_day-error" class="text-danger errors"></span>
                        <span id="create-period_month-error" class="text-danger errors"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="create-latefee_amount">Multa</label>
                    <div class="input-group">
                        <div class="input-group mt-0">
                            <div class="input-group-prepend">
                                <span class="input-group-text rounded-left" id="basic-addon2">S/.</span>
                            </div> 
                            <input 
                                id="create-latefee_amount"
                                type="text" 
                                name="latefee_amount"
                                class="form-control rounded-right"
                                placeholder="00.00"
                            >
                            <span class="text-danger errors" id="create-latefee_amount-error"></span>
                        </div>
                    </div>
                </div>
            `
            cleanInputs(form)
        }else{
            finesContainer.innerHTML = ''
        }
    })
}

form.addEventListener('submit', (e) => {
    e.preventDefault()
    const formData = new FormData(form)

    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras guardamos los datos.',
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post(url, formData)
        .then(response => {
            Swal.close()

            Swal.fire({
                title: 'Éxito',
                text: response.data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = url;
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
                const errors = error.response.data.errors
                
                showErrors(errors, 'create')
            }
        });
})

//Limpiar inputs al ingresar datos
cleanInputs(form)

//Limpiar modal
$('#createModal').on('hidden.bs.modal', () => {
    resetModal('createModal')
    form.querySelector('#create-checkboxContainer').innerHTML = ''
    form.querySelector('#create-finesContainer').innerHTML = ''
});