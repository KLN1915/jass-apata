<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServiceModalLabel">Crear servicio adicional</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('additional-services.store') }}" method="POST" id="addServiceForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre de servicio</label>
                        <input id="addService-name" type="text" name="name" class="form-control name" placeholder="Ingrese el nombre del servicio">
                        <span class="text-danger errors" id="create-name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea name="description" class="form-control" id="addService-description" rows="2" style="resize: none" placeholder="Descripción opcional"></textarea>
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
    @vite(['resources/js/settings/additional-services/createAddService.js'])
@endpush