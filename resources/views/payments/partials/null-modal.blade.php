<div class="modal fade" id="nullModal" tabindex="-1" role="dialog" aria-labelledby="nullModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nullModalLabel">Anular pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="nullModalBody">
                {{-- <p>El pago del contrato: <span id='payment-info' class="text-bold"></span> será anulado</p> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="modalBtn" class="btn btn-danger">Confirmar</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    @vite(['resources/js/payments/nullPayment.js'])
@endpush