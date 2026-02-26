import { resetModal } from '../../helpers/resetModal'
import { cleanInputs } from '../../helpers/cleanInputs';
// import { showErrors } from '../helpers/showErrors';

const form = document.getElementById('addServiceForm')
const url = form.action

//forzar nombre de servicio en mayusculas
const nameInput = form.querySelector('#addService-name')
nameInput.addEventListener('input', (e) => {
    e.target.value = e.target.value.toUpperCase()
})

form.addEventListener('submit', (e)=>{
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
                    window.location.href = '/services';
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
                    
                    // form.querySelector('name-error').textContent = errors.name
                    // showErrors(errors, 'create')
                }
            });
})

//Limpiar inputs al ingresar datos
cleanInputs(form)

//Limpiar modal
$('#addServiceModal').on('hidden.bs.modal', () => {
    resetModal('addServiceModal')
    // form.querySelector('#create-checkboxContainer').innerHTML = ''
    // form.querySelector('#create-finesContainer').innerHTML = ''
});