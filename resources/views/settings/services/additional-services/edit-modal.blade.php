<div class="modal fade" id="edit-AddServiceModal-{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="edit-AddServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-AddServiceModalLabel">Editar servicio adicional</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('additional-services.store') }}" method="POST" id="editServiceForm-{{ $service->id }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre de servicio</label>
                        <input value="{{ $service->name }}" type="text" name="name" class="form-control name edit-addService-name" placeholder="Ingrese el nombre del servicio">
                        <span class="text-danger errors" id="edit-name-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea name="description" class="form-control" id="addService-description" rows="2" style="resize: none" placeholder="Descripción opcional">{{ $service->description }}</textarea>
                    </div>              
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    {{-- <script>
        // import { resetModal } from '../../helpers/resetModal'
        // import { cleanInputs } from '../../helpers/cleanInputs';
        // import { showErrors } from '../helpers/showErrors';

        const form = document.getElementById(`editServiceForm-${ {{ $service->id }} }`)
        console.log(form);
        
        const url = form.action

        //forzar nombre de servicio en mayusculas
        const nameInput = form.querySelector('#edit-addService-name')
        nameInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.toUpperCase()
        })

        form.addEventListener('submit', (e)=>{
            e.preventDefault()
            const formData = new FormData(form)

            Swal.fire({
                    title: 'Guardando...',
                    text: 'Por favor, espera mientras guardamos los datos.',
                    allowOutsideClick: false,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                });
            
                axios.post(url, formData)
                    .then(response => {
                        Swal.close()
            
                        Swal.fire({
                            title: 'Éxito',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '/services';
                        });
                    })
                    .catch(error => {
                        Swal.close();
            
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al guardar los datos. Por favor, revisa los campos.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
            
                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors
                            
                            // form.querySelector('name-error').textContent = errors.name
                            // showErrors(errors, 'create')
                        }
                    });
        })

        //Limpiar inputs al ingresar datos
        cleanInputs(form)

        //Limpiar modal
        $('#addServiceModal').on('hidden.bs.modal', () => {
            resetModal('addServiceModal')
            // form.querySelector('#create-checkboxContainer').innerHTML = ''
            // form.querySelector('#create-finesContainer').innerHTML = ''
        });

    </script>    --}}
    {{-- @vite(['resources/js/settings/additional-services/editAddService.js']) --}}
@endpush