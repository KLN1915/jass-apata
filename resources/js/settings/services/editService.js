import { cleanInputs } from '../../helpers/cleanInputs'
import { showErrors } from '../../helpers/showErrors'

const form = document.getElementById('editForm')
const url = form.action

//forzar nombre de servicio en mayusculas
const nameInput = form.querySelector('#edit-name')
nameInput.addEventListener('input', (e) => {
    e.target.value = e.target.value.toUpperCase()
})

const periodSelect = form.querySelector('#edit-chargePeriod')
const checkboxContainer = form.querySelector('#edit-checkboxContainer')
const finesContainer = form.querySelector('#edit-finesContainer')

function insertAnnualCheckbox(){
    finesContainer.innerHTML = ''
    checkboxContainer.innerHTML = `
        <input value="1" name="lateFee" type="checkbox" class="form-check-input" id="edit-fineCheckbox">
        <label class="form-check-label" for="edit-fineCheckbox">Cobrar multa mensualmente</label>
    `
}

periodSelect.addEventListener('change', (e) => {
    if(e.target.value !== ""){
        const selectValue = e.target.value
        switch(selectValue){
            case "MENSUAL":
                finesContainer.innerHTML = ''
                checkboxContainer.innerHTML = `
                    <input value="1" name="lateFee" type="checkbox" class="form-check-input" id="edit-fineCheckbox" name="lateFee">
                    <label class="form-check-label" for="edit-fineCheckbox">Cobrar multa</label>
                `
                // insertMonthlyInputs()
                break
            case "ANUAL":
                insertAnnualCheckbox()
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
                        <input id="edit-period_day" name="period_day" type="number" class="form-control col-md-4" min="1" max="31" placeholder="dd">
                        <select id="edit-period_month" name="period_month" class="form-control">
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
                        <span id="edit-period_day-error" class="text-danger errors"></span>
                        <span id="edit-period_month-error" class="text-danger errors"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="edit-latefee_amount">Multa</label>
                    <div class="input-group">
                        <div class="input-group mt-0">
                            <div class="input-group-prepend">
                                <span class="input-group-text rounded-left" id="basic-addon2">S/.</span>
                            </div> 
                            <input 
                                id="edit-latefee_amount"
                                type="text" 
                                name="latefee_amount"
                                class="form-control rounded-right"
                                placeholder="00.00"
                            >
                            <span class="text-danger errors" id="edit-latefee_amount-error"></span>
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

$(document).on('click', '.btnEdit', async function (e){
    const serviceId = this.getAttribute('data-id')
    const urlEdit = `services/${serviceId}/edit`

    //agregar id en data-id del form
    form.setAttribute('data-id', serviceId)

    axios.get(urlEdit)
        .then(response => {
            //nombre
            document.getElementById('edit-name').value = response.data.name
            //precio
            document.getElementById('edit-price').value = response.data.price
            //periodo
            let chargePeriod = response.data.charge_period
            if(chargePeriod == 'ANUAL'){
                document.getElementById('edit-chargePeriod').value = chargePeriod
                insertAnnualCheckbox()
            }
            //checkbox
            let lateFee = response.data.late_fee
            if(lateFee){
                const checkbox = document.getElementById('edit-fineCheckbox')
                checkbox.checked = lateFee
                insertAnnualInputs()
                checkbox.dispatchEvent(new Event('change'))

                //inputs de multa
                const endDate = response.data.late_fee.end_date

                const [year, month, day] = endDate.split('-')

                form.querySelector('#edit-period_day').value = day
                form.querySelector('#edit-period_month').value = month

                form.querySelector('#edit-latefee_amount').value = response.data.late_fee.amount
            }

            $('#editModal').modal('show')
        })
})

form.addEventListener('submit', (e) => {
    e.preventDefault()
    const formData = new FormData(form);
    const serviceId = form.dataset.id
    formData.append('serviceId', serviceId)
    formData.append('_method', 'PATCH')

    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras guardamos los datos.',
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post(`${url}/${serviceId}`, formData)
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
})