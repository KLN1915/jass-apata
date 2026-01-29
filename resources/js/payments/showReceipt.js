$(document).on('click', '.btnReceipt', async function (e) {
    const modalBody = document.getElementById('receiptModalBody');
    const paymentId = this.getAttribute('data-id')

    modalBody.innerHTML = `
        <iframe
            src="/payments/${paymentId}/receipt"
            width="100%"
            height="500"
            style="border:none;"
        ></iframe>
    `;

    $('#receiptModal').modal('show')
})