<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Crear contrato</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('contracts.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="create-associated_id">Buscar cliente o institución</label>
                                <select class="form-control" name="associated_id" id="create-associated_id">
                                    {{-- Autocomplete --}}
                                </select>
                                <span class="text-danger errors" id="create-associated_id-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="create-directionSelect">Elegir dirección</label>
                                <select id="create-directionSelect" name="direction_id" class="form-control">
                                    <option value="" selected>Seleccionar asociado para buscar direcciones</option>
                                    {{-- opciones del select --}}
                                </select>
                                <span class="text-danger errors" id="create-direction_id-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="create-selectService">Elegir servicio</label>
                                <select id="create-selectService" name="service_id" class="form-control selectService">
                                    {{-- opciones del backend --}}
                                </select>
                                <span class="text-danger errors" id="create-service_id-error"></span>
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
                                            <a class="nav-link active" id="create-existing-section-tab" data-toggle="pill" href="#create-existing-section" role="tab" aria-controls="create-existing-section" aria-selected="true">Existente</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="create-new-section-tab" data-toggle="pill" href="#create-new-section" role="tab" aria-controls="create-new-section" aria-selected="false">Nuevo</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-two-tabContent">
                                        <div class="tab-pane fade show active" id="create-existing-section" role="tabpanel" aria-labelledby="create-existing-section-tab">
                                            <div class="row">
                                                <div class="form-group col-md-6 col-7 ms-md-0 ms-4" id="fecha-instalacion">
                                                    <label for="create-start_date">Fecha de Inicio<span style="color: red">*</span></label>
                                                    <div class="input-group date mt-0" id="datetimepicker" data-target-input="nearest">
                                                        <input 
                                                            type="text"
                                                            name="start_date"
                                                            id="create-start_date"
                                                            class="form-control datetimepicker-input datebirth"
                                                            data-target="#datetimepicker"
                                                            placeholder="dd-mm-aaaa"
                                                        >
                                                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                    <span class="text-danger errors" id="create-start_date-error"></span>
                                                </div>
                                                <div class="col-md-6 col-7 ms-md-0 mt-md-0 ms-4 mt-2">
                                                    <input type="checkbox" class="cursor-pointer" id="create-debts_since" value="1"/>
                                                    <label for="create-debts_since" class="cursor-pointer">Con deuda desde:</label>
                                                    <div id="startDebtsContainer">
                                                        {{-- <input type="number" name="start_debt_date" class="form-control" min="1980" max="2050" placeholder="Ejm: 2000"> --}}
                                                    </div>
                                                </div>   
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <hr class="mt-4">
                                                <div class="col-md-6 col-8 ms-md-0 ms-4">
                                                    <label for="create-installation_cost">Costo de Instalación</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">S/.</span>
                                                        </div>
                                                        <input id="create-installation_cost" type="string" class="form-control" name="installation_cost" placeholder="00.00">
                                                        <span class="text-danger errors" id="create-installation_cost-error"></span>
                                                        {{-- <div class="input-group-append">
                                                            <span class="input-group-text">.00</span>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-8 ms-md-0 ms-4" id="monto-cuenta">
                                                    <label for="create-amount_payed">Monto cancelado</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">S/.</span>
                                                        </div>
                                                        <input id="create-amount_payed" type="string" class="form-control" name="amount_payed" placeholder="00.00">
                                                        <span class="text-danger errors" id="create-amount_payed-error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="create-new-section" role="tabpanel" aria-labelledby="create-new-section-tab">
                                            <div class="col-md-5 col-8 ms-md-0 ms-4" id="costo-instalacion">
                                                <label for="create-new_installation_cost">Costo de Instalación<span style="color: red">*</span></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">S/.</span>
                                                    </div>
                                                    <input type="string" id="create-new_installation_cost" class="form-control" name="new_installation_cost" placeholder="00.00">
                                                    <span class="text-danger errors" id="create-new_installation_cost-error"></span>
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
        $('#createModal').on('shown.bs.modal', function() {
            moment.locale('es')
            $('#datetimepicker').datetimepicker({
                format: 'DD-MM-YYYY', // solo fecha (usa 'L LT' para fecha y hora)
                locale: 'es',
                useCurrent: false
            });
        });
        
        // Opciones de Select2
        $('#createModal').on('shown.bs.modal', function() {
            $('#create-associated_id').select2({
                placeholder: 'Escribir nombre o DNI',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#createModal'), // Esto es clave
                ajax: {
                    url: '/getAssociateds', // Ruta en Laravel
                    dataType: 'json',
                    delay: 250, // Retraso en la búsqueda
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
                minimumInputLength: 2,
                language: {
                    inputTooShort: function () {
                        return 'Escribe al menos 2 caracteres';
                    },
                    noResults: function () {
                        return 'No se encontraron resultados';
                    },
                    searching: function () {
                        return 'Buscando...';
                    },
                    errorLoading: function () {
                        return 'Error al cargar los resultados';
                    }
                }
            });

            // Workaround: elimina los eventos que bloquean el scroll
            $('.selectOccupation').on('select2:open', function(e) {
                $(e.target).parents().off('scroll.select2');
                $(window).off('scroll.select2');
            });

            // --- LÓGICA DE LIMPIEZA DE ERRORES DEL SELECT2 ---
            const select2Element = $('#create-associated_id');
            // 1. Obtener el span de error específico (usando el ID del select)
            const errorSpan = document.getElementById(`${select2Element.attr('id')}-error`);
            const $errorSpan = $(errorSpan);
    
            // 2. Evento: Cuando el usuario selecciona una opción
            select2Element.on('select2:select', function() {
                select2Element.removeClass('is-invalid');
                if ($errorSpan.length) $errorSpan.text('');
            });
        });

        //agregar direcciones del asociado
        $('#create-associated_id').on('select2:select', function (e) {
            let associeatedId = e.params.data.id; // Select2 guarda el id aquí
            const directionSelect = document.querySelector('#create-directionSelect')

            if (associeatedId) {
                axios.get(`/clients/${associeatedId}`)
                    .then(response => {
                        const directions = response.data.directions
                        const filteredDirections = directions.filter(direction => direction.contracted !== 1)
                        directionSelect.innerHTML = '<option value="" selected>Seleccione la dirección</option>'
                        filteredDirections.forEach(direction => {
                            const option = document.createElement('option');
                            option.value = direction.id;
                            option.textContent = direction.name;
                            directionSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        directionSelect.innerHTML = '<option disabled selected value="" class="text-danger">Sin direcciones disponibles</option>'
                        console.error('Error al cargar direcciones:', error)
                    });
            }
        });
    </script>

    @vite(['resources/js/contracts/createContract.js'])
@endpush
