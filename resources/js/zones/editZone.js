import { resetModal } from '../helpers/resetModal'
import { cleanInputs } from '../helpers/cleanInputs'
import { showErrors } from '../helpers/showErrors'

const form = document.querySelector('#editForm')
const url = form.action

//asignacion de eventos a los botones de edit en el datatable
document.querySelectorAll('.btnEdit').forEach(btn => {
    btn.addEventListener('click', async function(e) {
        e.stopPropagation()
        const zoneId = this.getAttribute('data-id')
        const urlUpdate = `${url}/${zoneId}`

        axios.get(urlUpdate)
            .then(response => {
                form.setAttribute('data-id', response.data.data.id);

                const nameInput = document.getElementById('edit-name')
                nameInput.value = response.data.data.name
                $('#editModal').modal('show');                
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudo cargar la información de la zona', 'error');
            })
    })
})

//Enviar datos
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const form = e.target
    const formData = new FormData(form);
    const zoneId = form.dataset.id
    formData.append('_method', 'PUT')

    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor, espera mientras guardamos los datos.',
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    });

    axios.post(`${url}/${zoneId}`, formData)
        .then(response => {
            Swal.close();

            Swal.fire({
                title: 'Éxito',
                text: response.data.message, // Axios ya parsea JSON
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = url;
            });
        })
        .catch(error => {
            Swal.close();

            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al actualizar los datos. Por favor, revisa los campos.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                showErrors(errors, 'edit')
            }
        });
});

//Limpiar inputs al ingresar datos
cleanInputs(form)

//Limpiar modal
$('#editModal').on('hidden.bs.modal', () => {
    resetModal('editModal')
});