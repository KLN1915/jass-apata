<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Registrar pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('payments.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="create-contract_id">Buscar contrato</label>
                                <select class="form-control" name="contract_id" id="create-contract_id">
                                    {{-- Autocomplete --}}
                                </select>
                                <span class="text-danger errors" id="create-contract_id-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><b>Deudas de servicio</b></h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="debtsTable" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Periodo</th>
                                            <th>Monto</th>
                                            <th>Interés</th>
                                            <th>Total</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center">Seleccione un contrato</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="text-bold text-right">SUB-TOTAL: <span id="subtotal1" class="text-bold">--</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="card card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><b>Deudas adicionales</b></h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" id="additionalDebts">
                                <table id="addDebtsTable" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Concepto</th>
                                            <th>Monto</th>
                                            <th>A cuenta</th>
                                            <th>Resta</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center">Seleccione un contrato</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="dynamicInputs">
                                <!--inputs dinamicos-->
                            </div>                            
                            <p class="text-bold text-right">SUB-TOTAL: <span id="subtotal2" class="text-bold">--</span></p>
                        </div>
                    </div>
                    <div class="card card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><b>Servicios adicionales</b></h3>
                        </div>
                        <div class="card-body">
                            <div id="dynamicServices">
                                <!--inputs dinamicos-->
                            </div>
                            <p class="text-bold text-right">SUB-TOTAL: <span id="subtotal3" class="text-bold">--</span></p>
                        </div>
                    </div>
                    <span class="text-danger errors" id="create-main_validation-error"></span>
                    <h5 class="fs-1 text-bold text-right">TOTAL: <span id="total" class="text-bold">--</span></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
    </script>
    @vite(['resources/js/payments/makePayment.js'])
@endpush
