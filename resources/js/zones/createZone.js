import { resetModal } from '../helpers/resetModal'
import { cleanInputs } from '../helpers/cleanInputs';

const form = document.querySelector('#createForm')
const url = form.action

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
//     })

//     // fetch(url, {
//     //     method: 'POST',
//     //     body: formData,
//     //     headers:{
//     //         token,
//     //         'Accept': 'application/json'
//     //     }
//     // })
//     axios.post(url, formData)
//     .then(response => {
//         if(!response.ok){
//             return response.json().then(err => { throw err })
//         }
//         return response.json()
//     })
//     .then(result => {
//         Swal.close();

//         Swal.fire({
//             title: 'Éxito',
//             text: result.message,
//             type: 'success',
//             confirmButtonText: 'OK'
//         }).then(() => {
//             // Redirige al index, por ejemplo, a la ruta '/clients'
//             window.location.href = url;
//         });
//     })
//     .catch(err => {
//         Swal.close();
//         Swal.fire({
//             title: 'Error',
//             text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
//             type: 'error',
//             confirmButtonText: 'OK'
//         });
//         if (err.errors) {
//             console.log(err.errors); // Aquí estarán las validaciones
//             document.querySelector('#nameError').textContent = err.errors.name[0];
//         }
//     });
// })

//Enviar datos
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(form);

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
            Swal.close();

            Swal.fire({
                title: 'Éxito',
                text: response.data.message, // Axios ya parsea JSON
                type: 'success',
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
                type: 'error',
                confirmButtonText: 'OK'
            });

            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                console.log(errors);

                if (errors.name) {
                    document.querySelectorAll('.name')[0].classList.add('is-invalid')
                    document.querySelectorAll('.nameError')[0].textContent = errors.name[0]
                }
            }
        });
});

//Limpiar inputs al ingresar datos
cleanInputs(form)

//Limpiar modal
$('#createModal').on('hidden.bs.modal', () => {
    resetModal('createModal')
});