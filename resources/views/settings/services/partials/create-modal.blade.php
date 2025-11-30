<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Crear servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('services.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Nombre de servicio</label>
                                <input id="create-name" type="text" name="name" class="form-control name" placeholder="Ingrese el nombre del servicio">
                                <span class="text-danger errors" id="create-name-error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create-price">Monto</label>
                                <div class="input-group mt-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-left">S/.</span>
                                    </div> 
                                    <input
                                        id="create-price"
                                        type="text" 
                                        name="price"
                                        class="form-control rounded-right"
                                        placeholder="00.00"
                                    >
                                    <span class="text-danger errors" id="create-price-error"></span>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Periodo de cobro</label>
                                <select id="create-chargePeriod" name="chargePeriod" class="form-control">
                                    <option value="" selected="">Seleccionar periodo</option>
                                    {{-- <option value="MENSUAL">MENSUAL</option> --}}
                                    <option value="ANUAL">ANUAL</option>
                                </select>
                                <span class="text-danger errors" id="create-chargePeriod-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-check" id="create-checkboxContainer">
                        {{-- checkbox condicional --}}
                    </div>
                    <div class="row" id="create-finesContainer">
                        {{-- finesInputs condicional --}}
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
    @vite(['resources/js/settings/services/createService.js'])
@endpush