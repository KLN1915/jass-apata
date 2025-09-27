<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar asociado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('clients.store') }}" method="POST" id="editForm">
                @csrf
                <div class="bs-stepper" id="edit-stepper">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#edit-titular-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="edit-titular-part"
                                id="edit-titular-part-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Datos de titular</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#edit-directions-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="edit-directions-part"
                                id="edit-directions-part-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Direcciones</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="edit-titular-part" class="content" role="tabpanel" aria-labelledby="edit-titular-part-trigger">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="edit-namesLastnames">Nombres y apellidos <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            name="namesLastnames"
                                            id="edit-namesLastnames"
                                            class="form-control" 
                                            placeholder="Ejm: Marco Romero Luján"
                                            disabled
                                        >
                                        <span class="text-danger errors" id="edit-namesLastnames-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="edit-dni">DNI <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            name="dni" 
                                            id="edit-dni"
                                            class="form-control" 
                                            placeholder="44444444"
                                            disabled
                                        >
                                        <span class="text-danger errors" id="edit-dni-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-success mb-3" id="changeTitular">Cambiar titular</button>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="edit-datebirth">F. Nacimiento</label>
                                        <div class="input-group date mt-0" id="datetimepicker1" data-target-input="nearest">
                                            <input 
                                                type="text"
                                                name="datebirth"
                                                id="edit-datebirth"
                                                class="form-control datetimepicker-input datebirth"
                                                data-target="#datetimepicker1"
                                                placeholder="dd-mm-aaaa"
                                            >
                                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <span class="text-danger errors" id="edit-datebirth-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="phoneNumber">Celular</label>
                                        <input 
                                            type="text" 
                                            name="phoneNumber" 
                                            id="edit-phoneNumber"
                                            class="form-control phoneNumber" 
                                            placeholder="999999999"
                                        >
                                        <span class="text-danger errors" id="edit-phoneNumber-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Instrucción</label>
                                        <select id="edit-grade" name="grade" class="form-control">
                                            <option value="" selected>Seleccionar grado</option>
                                            <option value="SIN NIVEL">SIN NIVEL</option>
                                            <option value="PRE-ESCOLAR">PRE-ESCOLAR</option>
                                            <option value="PRIMARIA">PRIMARIA</option>
                                            <option value="SECUNDARIA">SECUNDARIA</option>
                                            <option value="SUPERIOR">SUPERIOR</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="occupation">Ocupación</label>
                                        <select id="edit-occupation" class="form-control selectOccupation occupation" name="occupation">
                                            <option></option>
                                            <!--carga dinamica de ocupaciones-->
                                        </select>
                                        <span class="text-danger errors" id="edit-occupation-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-primary"
                                    onclick="stepper2.next()">Siguiente</button>
                            </div>
                        </div>
                        <div id="edit-directions-part" class="content" role="tabpanel" aria-labelledby="edit-directions-part-trigger">
                            <div id="editDirectionsContainer">
                                <template id="directionTemplate">
                                    <div class="card edit-directionFields">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-1 col-12 d-flex align-items-center justify-content-center removeDirectionFieldsContainer">
                                                    <button type="button" class="btn btn-danger w-100 d-none">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-11 col-12 mt-3">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>Dirección <span class="text-danger">*</span></label>
                                                                {{-- Enviar ids de las direcciones --}}
                                                                <input type="hidden" name="direction_ids[]">
                                                                {{----}}
                                                                <input 
                                                                    type="text" 
                                                                    name="directions[]" 
                                                                    class="form-control edit-directions" 
                                                                    placeholder="Ejm: Av. Piura #658"
                                                                >
                                                                <span class="text-danger errors edit-directions-error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-7">
                                                            <div class="form-group">
                                                                <label>Barrio <span class="text-danger">*</span></label>
                                                                <select name="zone_id[]" class="form-control edit-zoneSelect" value="">
                                                                    <option>Cargando barrios...</option>
                                                                </select>
                                                                <span class="text-danger errors edit-zone_id-error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-5">
                                                            <div class="form-group">
                                                                <label># Habitantes <span class="text-danger">*</span></label>
                                                                <input 
                                                                    type="number" 
                                                                    name="cant_beneficiaries[]" 
                                                                    class="form-control edit-cant_beneficiaries" 
                                                                    min="1" 
                                                                    placeholder="1"
                                                                >
                                                                <span class="text-danger errors edit-cant_beneficiaries-error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6">
                                                            <div class="form-group">
                                                                <label>Permanencia <span class="text-danger">*</span></label>
                                                                <div class="input-group mt-0">
                                                                    <input 
                                                                        type="number" 
                                                                        name="permanence[]" 
                                                                        class="form-control edit-permanence" 
                                                                        min="1" 
                                                                        placeholder="1"
                                                                    >
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text rounded-right" id="basic-addon2">años</span>
                                                                    </div>                                                            
                                                                    <span class="text-danger errors edit-permanence-error"></span>
                                                                </div>                                                        
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6">
                                                            <div class="form-group">
                                                                <label>Material de Predio <span class="text-danger">*</span></label>
                                                                <select name="material[]" class="form-control">
                                                                    <option value="" selected>Seleccionar</option>
                                                                    <option value="RUSTICO">RÚSTICO</option>
                                                                    <option value="NOBLE">NOBLE</option>
                                                                    <option value="MIXTO">MIXTO</option>
                                                                </select>
                                                                <span class="text-danger errors edit-material-error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6">
                                                            <div class="form-group">
                                                                <label>Sumideros <span class="text-danger">*</span></label>
                                                                <select name="drains[]" class="form-control">
                                                                    <option value="" selected>Seleccionar</option>
                                                                    <option value="1">SI</option>
                                                                    <option value="0">NO</option>
                                                                </select>
                                                                <span class="text-danger errors edit-drains-error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                    
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="row d-flex align-items-center justify-content-center">
                                <div class="col-md-2 col-12" id="editAddDirectionFieldsButton">
                                    <button type="button" class="btn btn-success w-100">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="button" class="btn btn-secondary"
                                    onclick="stepper2.previous()">Atrás</button>
                                <button disabled type="submit" class="btn btn-primary ml-1">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        //Stepper
        var stepper2 = new Stepper(document.querySelector('#edit-stepper'), {
            linear: false,
            animation: true
        });

        // Opciones de datePicker
        $('#editModal').on('shown.bs.modal', function() {
            moment.locale('es')
            $('#datetimepicker1').datetimepicker({
                format: 'DD-MM-YYYY', // solo fecha (usa 'L LT' para fecha y hora)
                locale: 'es',
                useCurrent: false
            });
        });

        // Opciones de Select2
        $('#editModal').on('shown.bs.modal', function() {
            $('.selectOccupation').select2({
                tags: true,
                placeholder: 'Selecciona o escribe',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#editModal'), // Esto es clave
                ajax: {
                    url: '/getOccupations', // Ruta en Laravel
                    dataType: 'json',
                    delay: 100, // Retraso en la búsqueda
                    data: function (params) {
                        return {
                            search: params.term // Enviar el término de búsqueda al backend
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data // Devuelve los resultados esperados por Select2
                        };
                    },
                    cache: true
                },
                language: {
                    searching: function () {
                        return 'Buscando...';
                    },
                    errorLoading: function () {
                        return 'Error al cargar los resultados';
                    }
                },
                editTag: function(params) {
                    const term = $.trim(params.term);

                    if (term === '' || term.length > 25) { // 🔹 Límite: 20 caracteres
                        return null; // Evita crear etiqueta si supera el límite
                    }

                    return {
                        id: term,
                        text: term
                    };
                }
            });

            // Workaround: elimina los eventos que bloquean el scroll
            $('.selectOccupation').on('select2:open', function(e) {
                $(e.target).parents().off('scroll.select2');
                $(window).off('scroll.select2');
            });
        });
    </script>
    {{-- @vite(['resources/js/clients/editClient.js']) --}}
@endpush