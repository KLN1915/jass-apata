$(document).on('click', '.btnDebts', async function (e) {
    const modalBody = document.getElementById('debtsModalBody');
    const contractId = this.getAttribute('data-id')

    modalBody.innerHTML = `
        <iframe
            src="/debts/${contractId}/bill"
            width="100%"
            height="500"
            style="border:none;"
        ></iframe>
    `;

    $('#debtsModal').modal('show')
})