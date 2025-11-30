import { showErrors } from '../helpers/showErrors'

// const modalBody = stateModal.querySelector('#stateModalBody')
// const modalBtn = stateModal.querySelector('#modalBtn')

// $(document).on('click', '.btnChangeState', async function (e) {
//     // e.stopPropagation()
//     const contractId = this.getAttribute('data-id')
//     const type = this.getAttribute('data-type')
//     const urlDetails = `contracts/${contractId}/edit`

//     axios.get(urlDetails)
//         .then(response => {
//             let code = response.data.contract.code
//             let currentTitular = response.data.contract.direction.client.current_titular.names_lastnames
//             let direction = response.data.contract.direction.name

//             modalBtn.classList.remove('btn-info', 'btn-danger')
//             modalBtn.removeAttribute('form')

//             switch (type) {
//                 case 'deactivate':
//                     modalBody.innerHTML = `
//                         <p>El contrato <span id="contract-info" class="font-weight-bold">
//                             ${code} - ${currentTitular} - ${direction}
//                         </span> será suspendido</p>
//                     `
//                     modalBtn.classList.add('btn-danger')
//                     modalBtn.textContent = 'Suspender'
//                     break
//                 case 'activate':
//                     modalBody.innerHTML = `
//                         <p>El contrato <span id="contract-info" class="font-weight-bold">
//                             ${code} - ${currentTitular} - ${direction}
//                         </span> será reactivado</p>
//                         <form id="reconexionForm">
//                             <div class="form-group">
//                                 <label for="reconexion_amount">Monto de reconexión</label>
//                                 <input type="text" placeholder="00.00" class="form-control" id="reconexion_amount">
//                                 <span class="text-danger errors" id="activate-reconexion_amount-error"></span>
//                             </div>
//                         </form>
//                     `
//                     modalBtn.setAttribute('form', 'reconexionForm')
//                     modalBtn.classList.add('btn-info')
//                     modalBtn.textContent = 'Reactivar'
//                     break
//             }

//             $('#stateModal').modal('show');
//         })
//         .catch(error => {
//             Swal.fire('Error', 'No se pudo cargar la información del contrato', 'error');
//             console.error(error)
//         })

//     modalBtn.addEventListener('click', () => {
//         const url = `contracts`

//         switch (type) {
//             case 'deactivate':
//                 axios.post(`${url}/${contractId}/change-contract-state`)
//                     .then(response => {
//                         Swal.fire({
//                             title: 'Éxito',
//                             text: response.data.message,
//                             icon: 'success',
//                             confirmButtonText: 'OK'
//                         })
//                     })
//                     .then(() => {
//                         location.reload()
//                     })
//                     .catch(error => {
//                         Swal.close()
//                         Swal.fire({
//                             title: 'Error',
//                             text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
//                             icon: 'error',
//                             confirmButtonText: 'OK'
//                         })
//                     })

//                 break
//             case 'activate':
//                 const reconexionForm = modalBody.querySelector('#reconexionForm')
//                 reconexionForm.addEventListener('submit', (e) => {
//                     e.preventDefault()
//                     const form = e.target
//                     const formData = new FormData(form)

//                     Swal.fire({
//                         title: 'Guardando...',
//                         text: 'Por favor, espera mientras guardamos los datos.',
//                         allowOutsideClick: false,
//                         onOpen: () => {
//                             Swal.showLoading();
//                         }
//                     });

//                     axios.post(`${url}/${contractId}/change-contract-state`, formData)
//                         .then(response => {
//                             Swal.close();            
//                             Swal.fire({
//                                 title: 'Éxito',
//                                 text: response.data.message, // Axios ya parsea JSON
//                                 icon: 'success',
//                                 confirmButtonText: 'OK'
//                             }).then(() => {
//                                 window.location.href = url
//                             });
//                         }).catch(error => {
//                             Swal.close();
                            
//                             Swal.fire({
//                                 title: 'Error',
//                                 text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
//                                 icon: 'error',
//                                 confirmButtonText: 'OK'
//                             });

//                             if (error.response && error.response.status === 422) {
//                                 const errors = error.response.data.errors;

//                                 showErrors(errors, 'activate')
//                             }
//                         })
//                 })

//                 break
//         }
//     })
// })

let modalBody = document.getElementById('stateModalBody');
let modalBtn = document.getElementById('modalBtn');

$(document).on('click', '.btnChangeState', async function (e) {
    const contractId = this.getAttribute('data-id')
    const type = this.getAttribute('data-type')
    const urlDetails = `contracts/${contractId}/edit`

    axios.get(urlDetails)
        .then(response => {

            // limpiar listeners
            let newBtn = modalBtn.cloneNode(true)
            modalBtn.parentNode.replaceChild(newBtn, modalBtn)
            modalBtn = newBtn

            modalBtn.classList.remove('btn-info', 'btn-danger')
            modalBtn.removeAttribute('form')

            let code = response.data.contract.code
            let currentTitular = response.data.contract.direction.client.current_titular.names_lastnames
            let direction = response.data.contract.direction.name

            switch (type) {
                case 'deactivate':
                    modalBody.innerHTML = `
                        <p>El contrato <span id="contract-info" class="font-weight-bold">
                            ${code} - ${currentTitular} - ${direction}
                        </span> será suspendido</p>
                    `;
                    modalBtn.classList.add('btn-danger')
                    modalBtn.textContent = 'Suspender'

                    modalBtn.addEventListener('click', () => {
                        const url = `contracts`;

                        axios.post(`${url}/${contractId}/change-contract-state`)
                            .then(response => {
                                Swal.fire('Éxito', response.data.message, 'success')
                                    .then(() => location.reload())
                            })
                            .catch(error => {
                                Swal.fire('Error', 'No se pudo suspender el contrato', 'error')
                            })
                    })
                    break;

                case 'activate':
                    modalBody.innerHTML = `
                        <p>El contrato <span id="contract-info" class="font-weight-bold">
                            ${code} - ${currentTitular} - ${direction}
                        </span> será reactivado</p>
                        <form id="reconexionForm">
                            <div class="form-group">
                                <label>Monto de reconexión</label>
                                <input type="text" placeholder="00.00" class="form-control" id="reconexion_amount" name="reconexion_amount">
                                <span class="text-danger errors" id="activate-reconexion_amount-error"></span>
                            </div>
                        </form>
                    `;
                    modalBtn.classList.add('btn-info')
                    modalBtn.setAttribute('form', 'reconexionForm')
                    modalBtn.textContent = 'Reactivar'

                    modalBtn.addEventListener('click', () => {
                        const url = `contracts`
                        const form = document.getElementById('reconexionForm')
                        const formData = new FormData(form)

                        Swal.fire({
                            title: 'Guardando...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        })

                        axios.post(`${url}/${contractId}/change-contract-state`, formData)
                            .then(response => {
                                Swal.fire('Éxito', response.data.message, 'success')
                                    .then(() => window.location.href = url)
                            })
                            .catch(error => {
                                Swal.close()
                                Swal.fire('Error', 'Revisa los campos', 'error')

                                if (error.response?.status === 422) {
                                    showErrors(error.response.data.errors, 'activate')
                                }
                            })
                    })
                    break;
            }

            $('#stateModal').modal('show')
        })
})