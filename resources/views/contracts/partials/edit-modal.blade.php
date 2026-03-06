<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar contrato</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="ml-4 mt-3"><em><b>Indicación:</b> Los <b class="text-danger">*</b> son campos obligatorios</em></div>
            <form action="{{ route('contracts.store') }}" method="POST" id="editForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{-- <label for="edit-associated_id">Cliente o institución</label> --}}
                                <label for="edit-associated_id">Cliente</label>
                                {{-- <select class="form-control" name="associated_id" id="edit-associated_id">
                                    Autocomplete
                                </select> --}}
                                <input 
                                    disabled
                                    type="text"
                                    name="associated_id"
                                    id="edit-associated_id"
                                    type="text"
                                    class="form-control"
                                    data-id=""
                                >
                                {{-- <span class="text-danger errors" id="edit-associated_id-error"></span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit-directionSelect">Elegir dirección</label>
                                <select id="edit-directionSelect" name="direction_id" class="form-control">
                                    <option value="" selected>Seleccionar asociado para buscar direcciones</option>
                                    {{-- opciones del select --}}
                                </select>                              
                                <span class="text-danger errors" id="edit-direction_id-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit-selectService">Elegir servicio</label>
                                <select id="edit-selectService" name="service_id" class="form-control selectService">
                                    {{-- opciones del backend --}}
                                </select>
                                <span class="text-danger errors" id="edit-service_id-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="">Datos de contrato</label>
                            <div class="card card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                                        <li class="pt-2 px-3"><h3 class="card-title">Este asociado es: </h3></li>
                                        <li class="nav-item">
                                            <a class="nav-link active" id="edit-existing-section-tab" data-toggle="pill" href="#edit-existing-section" role="tab" aria-controls="edit-existing-section" aria-selected="true">Existente</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="edit-new-section-tab" data-toggle="pill" href="#edit-new-section" role="tab" aria-controls="edit-new-section" aria-selected="false">Nuevo</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-two-tabContent">
                                        <div class="tab-pane fade show active" id="edit-existing-section" role="tabpanel" aria-labelledby="edit-existing-section-tab">
                                            <div class="row">
                                                <div class="form-group col-md-6 col-7 ms-md-0 ms-4" id="fecha-instalacion">
                                                    <label for="edit-start_date">Fecha de Inicio<span style="color: red">*</span></label>
                                                    <div class="input-group date mt-0" id="datetimepicker2" data-target-input="nearest">
                                                        <input 
                                                            type="text"
                                                            name="start_date"
                                                            id="edit-start_date"
                                                            class="form-control datetimepicker-input"
                                                            data-target="#datetimepicker2"
                                                            placeholder="dd-mm-aaaa"
                                                        >
                                                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                    <span class="text-danger errors" id="edit-start_date-error"></span>
                                                </div>
                                                <div class="col-md-6 col-7 ms-md-0 mt-md-0 ms-4 mt-2">
                                                    <input type="checkbox" class="cursor-pointer" id="edit-debts_since" value="1"/>
                                                    <label for="edit-debts_since" class="cursor-pointer">Con deuda desde:</label>
                                                    <div id="edit-startDebtsContainer">
                                                        {{-- <input type="number" name="start_debt_date" class="form-control" min="1980" max="2050" placeholder="Ejm: 2000"> --}}
                                                    </div>
                                                </div>   
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <hr class="mt-4">
                                                <div class="col-md-6 col-8 ms-md-0 ms-4">
                                                    <label for="edit-installation_cost">Costo de Instalación</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">S/.</span>
                                                        </div>
                                                        <input id="edit-installation_cost" type="string" class="form-control" name="installation_cost" placeholder="00.00">
                                                        <span class="text-danger errors" id="edit-installation_cost-error"></span>
                                                        {{-- <div class="input-group-append">
                                                            <span class="input-group-text">.00</span>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-8 ms-md-0 ms-4" id="monto-cuenta">
                                                    <label for="edit-amount_payed">Monto cancelado</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">S/.</span>
                                                        </div>
                                                        <input id="edit-amount_payed" type="string" class="form-control" name="amount_payed" placeholder="00.00">
                                                        <span class="text-danger errors" id="edit-amount_payed-error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="edit-new-section" role="tabpanel" aria-labelledby="edit-new-section-tab">
                                            <div class="col-md-5 col-8 ms-md-0 ms-4" id="costo-instalacion">
                                                <label for="edit-new_installation_cost">Costo de Instalación<span style="color: red">*</span></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">S/.</span>
                                                    </div>
                                                    <input type="string" id="edit-new_installation_cost" class="form-control" name="new_installation_cost" placeholder="00.00">
                                                    <span class="text-danger errors" id="edit-new_installation_cost-error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        // Opciones de datePicker
        $('#editModal').on('shown.bs.modal', function() {
            moment.locale('es')
            $('#datetimepicker2').datetimepicker({
                format: 'DD-MM-YYYY', // solo fecha (usa 'L LT' para fecha y hora)
                locale: 'es',
                useCurrent: false
            });
        });
    </script>
    {{-- @vite(['resources/js/clients/editClient.js']) --}}
@endpush