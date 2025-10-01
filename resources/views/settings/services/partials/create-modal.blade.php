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
                                    {{-- <option value="1">MENSUAL</option> --}}
                                    <option value="12">ANUAL</option>
                                </select>
                                <span class="text-danger errors" id="create-chargePeriod-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-check" id="create-checkboxContainer">
                        {{-- checkbox condicional --}}
                    </div>
                    <div class="row" id="create-finesContainer">
                        {{-- <div class="col-md-8">
                            <label for="">Fecha de vencimiento</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Cada</span>
                                </div>
                                <input type="number" class="form-control col-md-3" min="1" max="31" placeholder="dd">
                                <select name="period" class="form-control">
                                    <option value="" selected="">Seleccionar mes</option>
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="name">Interés</label>
                            <div class="input-group">
                                <div class="input-group mt-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-left" id="basic-addon2">S/.</span>
                                    </div> 
                                    <input 
                                        type="text" 
                                        name="price" 
                                        class="form-control"
                                        placeholder="00.00"
                                    >
                                    <span class="text-danger errors create-permanence-error"></span>
                                </div>
                            </div>
                        </div> --}}
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