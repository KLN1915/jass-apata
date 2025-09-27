<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModalLabel">Detalles de asociado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="overflow-x: auto">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nombres</th>
                                <th scope="col">DNI</th>
                                <th scope="col">Celular</th>
                                <th scope="col">F. Nacimiento</th>
                                <th scope="col">Grado de inst.</th>
                                <th scope="col">Ocupación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="text-center" id="show_names_lastnames"></th>
                                <td class="text-center" id="show_dni"></td>
                                <td class="text-center" id="show_phone_number"></td>
                                <td class="text-center" id="show_datebirth"></td>
                                <td class="text-center" id="show_grade"></td>
                                <td class="text-center" id="show_occupation"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="showModalTitulars">
                    <!-- titulares -->
                </div>
                <div id="showModalDirections">
                    <!-- direcciones -->
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    {{-- @vite(['resources/js/clients/showClient.js']) --}}
@endpush