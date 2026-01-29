let modalBody = document.getElementById('nullModalBody');
let modalBtn = document.getElementById('modalBtn');

$(document).on('click', '.btnNull', async function (e) {
    const paymentId = this.getAttribute('data-id')
    const urlDetails = `payments/${paymentId}/edit`

    axios.get(urlDetails)
        .then(response => {
            // limpiar listeners
            let newBtn = modalBtn.cloneNode(true)
            modalBtn.parentNode.replaceChild(newBtn, modalBtn)
            modalBtn = newBtn

            let total = response.data.total
            let created_at = response.data.created_at

            let currentTitular = response.data.contract.direction.client.current_titular.names_lastnames
            let direction = response.data.contract.direction.name
            let code = response.data.contract.code

            modalBody.innerHTML = `
                <p>
                    El pago del contrato <span class="text-bold">${code} - ${currentTitular} - ${direction}</span>
                    , con fecha: <span class="text-bold">${created_at}</span> y con un monto de <span class="text-bold">S/.${total}</span> será anulado.
                </p>
            `
            modalBtn.addEventListener('click', () => {
                const url = 'payments'

                axios.post(`${url}/${paymentId}/null-payment`)
                    .then(response => {
                        Swal.fire('Éxito', response.data.message, 'success')
                            .then(() => location.reload())
                    })
                    .catch(error => {
                        Swal.fire('Error', 'No se pudo anular el pago', 'error')
                    })
            })

            $('#nullModal').modal('show')
        })
})