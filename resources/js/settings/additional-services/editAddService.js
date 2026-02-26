import { resetModal } from '../../helpers/resetModal'
import { cleanInputs } from '../../helpers/cleanInputs';
// import { showErrors } from '../helpers/showErrors';

const btns = document.querySelectorAll('.addService-btnEdit')

btns.forEach((btn) => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id

        const form = document.getElementById(`editServiceForm-${id}`)

        // Guardamos la URL en dataset
        form.dataset.url = form.action + '/' + id  

        // Guardamos el id
        form.dataset.id = id  
        
        // form.addEventListener('submit', (e) => {
        //     e.preventDefault()
        //     const formData = new FormData(form)

        //     Swal.fire({
        //         title: 'Guardando...',
        //         text: 'Por favor, espera mientras guardamos los datos.',
        //         allowOutsideClick: false,
        //         onOpen: () => {
        //             Swal.showLoading();
        //         }
        //     });
            
        //     axios.post(url, formData)
        //         .then(response => {
        //             Swal.close()
        
        //             Swal.fire({
        //                 title: 'Éxito',
        //                 text: response.data.message,
        //                 icon: 'success',
        //                 confirmButtonText: 'OK'
        //             }).then(() => {
        //                 window.location.href = '/services';
        //             });
        //         })
        //         .catch(error => {
        //             Swal.close();
        
        //             Swal.fire({
        //                 title: 'Error',
        //                 text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
        //                 icon: 'error',
        //                 confirmButtonText: 'OK'
        //             });
        
        //             if (error.response && error.response.status === 422) {
        //                 const errors = error.response.data.errors
                        
        //                 // form.querySelector('name-error').textContent = errors.name
        //                 // showErrors(errors, 'create')
        //             }
        //         });
        // })

        //Limpiar inputs al ingresar datos
        // cleanInputs(form)
    })
})

const editNameInputs = document.querySelectorAll('.edit-addService-name')
editNameInputs.forEach((nameInput) => {
    nameInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.toUpperCase()
    })
})

document.addEventListener('submit', (e) => {
    const form = e.target

    if (!form.id.startsWith('editServiceForm-')) return

    e.preventDefault()

    const url = form.dataset.url
    const formData = new FormData(form)
    formData.append('id', form.dataset.id)
    formData.append('_method', 'PUT')

    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras guardamos los datos.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
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
                window.location.href = '/services';
            })
        })
        .catch(error => {
            Swal.close()
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar los datos.',
                icon: 'error'
            })
        })
})


// //Limpiar modal
// $('#addServiceModal').on('hidden.bs.modal', () => {
//     resetModal('addServiceModal')
//     // form.querySelector('#create-checkboxContainer').innerHTML = ''
//     // form.querySelector('#create-finesContainer').innerHTML = ''
// });